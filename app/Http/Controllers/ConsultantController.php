<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\MaturityLevel;

class ConsultantController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        /*
         * Only consultant/reviewer accounts
         * should access this workspace.
         */
        abort_unless($user->isConsultant(), 403);

        /*
         * Consultants review completed assessments.
         */
        $assessments = Assessment::with('company')
            ->where('status', 'completed')
            ->whereNotNull('company_score')
            ->latest()
            ->get();

            $awaitingReviewCount = $assessments
    ->filter(function ($assessment) {
        return $assessment->review_status !== 'reviewed';
    })
    ->count(); 
        /*
         * Maturity levels are needed so we can display
         * the maturity corresponding to each company score.
         */
        $maturityLevels = MaturityLevel::orderBy('min_score')
            ->get();

       return view('consultant.dashboard', compact(
    'assessments',
    'maturityLevels',
    'awaitingReviewCount'
));
    }

   public function review(Assessment $assessment)
{
    $user = auth()->user();

    // Consultant/reviewer only
    abort_unless($user->isConsultant(), 403);

    // Only completed assessments can be reviewed
    abort_unless($assessment->status === 'completed', 404);

    $assessment->load('company');

    /*
     * Load all responses with their question,
     * dimension and selected answer.
     */
    $responses = \App\Models\Response::with([
        'answerOption',
        'question.dimension',
    ])
        ->where('assessment_id', $assessment->id)
        ->get();

    /*
     * Calculate organizational readiness score
     * for each dimension.
     */
    $dimensionScores = $responses
        ->filter(function ($response) {
            return $response->question
                && $response->question->dimension
                && $response->answerOption;
        })
        ->groupBy(function ($response) {
            return $response->question->dimension->name;
        })
        ->map(function ($group) {

            $weightedScore = 0;
            $totalWeight = 0;

            foreach ($group as $response) {

                $answerScore = $response->answerOption?->score;
                $questionWeight = $response->question?->weight ?? 1;

                if ($answerScore === null) {
                    continue;
                }

                // Convert 1–4 answer score to 0–100
                $normalizedScore = (($answerScore - 1) / 3) * 100;

                $weightedScore += $normalizedScore * $questionWeight;
                $totalWeight += $questionWeight;
            }

            return $totalWeight > 0
                ? $weightedScore / $totalWeight
                : 0;
        });

    /*
     * Determine maturity level for every dimension.
     */
    $maturityLevels = \App\Models\MaturityLevel::orderBy('min_score')->get();

    $dimensionMaturityLevels = $dimensionScores->map(
        function ($score) use ($maturityLevels) {

            return $maturityLevels->first(function ($level) use ($score) {
                return $score >= $level->min_score
                    && $score <= $level->max_score;
            });
        }
    );

    return view('consultant.review', compact(
        'assessment',
        'responses',
        'dimensionScores',
        'dimensionMaturityLevels'
    ));
}

public function submitReview(Request $request, Assessment $assessment)
{
    $user = auth()->user();

    // Only consultants/reviewers can submit reviews
    abort_unless($user->isConsultant(), 403);

    // Only completed assessments can be reviewed
    abort_unless($assessment->status === 'completed', 404);

    $validated = $request->validate([
        'consultant_notes' => 'required|string|max:5000',
    ]);

    $assessment->update([
        'consultant_notes' => $validated['consultant_notes'],
        'review_status' => 'reviewed',
        'reviewed_by' => $user->id,
        'reviewed_at' => now(),
    ]);

    return redirect()
        ->route('consultant.assessments.review', $assessment)
        ->with('success', 'Assessment review submitted successfully.');
}
}