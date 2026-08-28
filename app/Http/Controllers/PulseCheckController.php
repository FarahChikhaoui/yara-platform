<?php

namespace App\Http\Controllers;

use App\Models\PulseCheck;
use App\Models\PulseCheckResponse;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PulseCheckController extends Controller
{
    public function intro()
    {
        return view('pulse-check.intro');
    }

    public function start()
    {
        $pulseCheck = PulseCheck::create([
            'session_token' => (string) Str::uuid(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('pulse.questions', [
            'token' => $pulseCheck->session_token,
        ]);
    }

    public function questions(string $token)
    {
        $pulseCheck = PulseCheck::where('session_token', $token)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $questions = Question::with([
            'answerOptions',
            'dimension',
        ])
            ->where('include_in_pulse', true)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('pulse-check.questions', compact(
            'pulseCheck',
            'questions',
            'token'
        ));
    }

    public function submit(Request $request, string $token)
    {
        $pulseCheck = PulseCheck::where('session_token', $token)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $questions = Question::with([
            'answerOptions',
            'dimension',
        ])
            ->where('include_in_pulse', true)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $questionIds = $questions->pluck('id')->all();

        $validated = $request->validate([
            'answers' => [
                'required',
                'array',
            ],

            'answers.*' => [
                'required',
                'integer',
                'exists:answer_options,id',
            ],
        ]);

        /*
         * Require one answer for every Pulse Check question.
         */
        foreach ($questionIds as $questionId) {
            if (!array_key_exists($questionId, $validated['answers'])) {
                return back()
                    ->withErrors([
                        'answers' => 'Please answer every Pulse Check question.',
                    ])
                    ->withInput();
            }
        }

        /*
         * Remove previous answers in case the request is submitted twice.
         */
        PulseCheckResponse::where(
            'pulse_check_id',
            $pulseCheck->id
        )->delete();

       $totalWeightedScore = 0;
$totalQuestionWeight = 0;
$dimensionScores = [];

        foreach ($questions as $question) {
            $answerOptionId = $validated['answers'][$question->id];

            /*
             * Ensure the selected option really belongs to this question.
             */
            $selectedOption = $question->answerOptions
                ->firstWhere('id', (int) $answerOptionId);

            if (!$selectedOption) {
                return back()
                    ->withErrors([
                        'answers' => 'One of the selected answers is invalid.',
                    ])
                    ->withInput();
            }

            PulseCheckResponse::create([
                'pulse_check_id' => $pulseCheck->id,
                'question_id' => $question->id,
                'answer_option_id' => $selectedOption->id,
            ]);

            /*
             * Convert the existing 1–4 scale to 0–100:
             *
             * 1 => 0
             * 2 => 33.33
             * 3 => 66.67
             * 4 => 100
             */
            $normalizedScore = (($selectedOption->score - 1) / 3) * 100;

          $questionWeight = (float) $question->weight ?? 1;

$totalWeightedScore += $normalizedScore * $questionWeight;
$totalQuestionWeight += $questionWeight;

if ($question->dimension_id) {

    if (!isset($dimensionScores[$question->dimension_id])) {
        $dimensionScores[$question->dimension_id] = [
            'weighted_score' => 0,
            'total_weight' => 0,
        ];
    }

    $dimensionScores[$question->dimension_id]['weighted_score']
        += $normalizedScore * $questionWeight;

    $dimensionScores[$question->dimension_id]['total_weight']
        += $questionWeight;
}
        }
       $overallScore = $totalQuestionWeight > 0
    ? $totalWeightedScore / $totalQuestionWeight
    : 0;

$dimensionAverages = collect($dimensionScores)
    ->map(function (array $values) {
        return $values['total_weight'] > 0
            ? $values['weighted_score'] / $values['total_weight']
            : 0;
    });

        $strongestDimensionId = $dimensionAverages->isNotEmpty()
            ? $dimensionAverages->sortDesc()->keys()->first()
            : null;

        $weakestDimensionId = $dimensionAverages->isNotEmpty()
            ? $dimensionAverages->sort()->keys()->first()
            : null;

        $readinessSignal = $this->determineReadinessSignal($overallScore);

        $pulseCheck->update([
            'status' => 'completed',
            'overall_score' => round($overallScore, 2),
            'readiness_signal' => $readinessSignal,
            'strongest_dimension_id' => $strongestDimensionId,
            'weakest_dimension_id' => $weakestDimensionId,
            'completed_at' => now(),
        ]);

        return redirect()->route('pulse.results', [
            'token' => $pulseCheck->session_token,
        ]);
    }

    public function results(string $token)
    {
        $pulseCheck = PulseCheck::with([
            'responses.answerOption',
            'responses.question.dimension',
            'strongestDimension',
            'weakestDimension',
        ])
            ->where('session_token', $token)
            ->where('status', 'completed')
            ->firstOrFail();

        $summary = $this->buildExecutiveSummary($pulseCheck);

        return view('pulse-check.results', compact(
            'pulseCheck',
            'summary'
        ));
    }

    private function determineReadinessSignal(float $score): string
    {
        return match (true) {
            $score < 25 => 'Foundational',
            $score < 50 => 'Emerging',
            $score < 75 => 'Developing',
            default => 'Advanced',
        };
    }

    private function buildExecutiveSummary(
        PulseCheck $pulseCheck
    ): string {
        $strongest = $pulseCheck->strongestDimension?->name
            ?? 'selected AI capabilities';

        $weakest = $pulseCheck->weakestDimension?->name
            ?? 'foundational AI capabilities';

        return match ($pulseCheck->readiness_signal) {
            'Foundational' =>
                "The Pulse Check indicates that the organization is at an early stage of AI readiness. Initial attention should focus on establishing core foundations, particularly in {$weakest}, before attempting to scale AI initiatives.",

            'Emerging' =>
                "The organization shows early signs of AI adoption, with relative strength in {$strongest}. However, important gaps remain in {$weakest}, and a more structured strategy, governance model and capability-building approach will be required.",

            'Developing' =>
                "The organization demonstrates several established AI capabilities, particularly in {$strongest}. The main opportunity is to address remaining gaps in {$weakest} and create a more consistent foundation for scaling AI across the organization.",

            default =>
                "The organization demonstrates a comparatively strong AI readiness signal, with notable capability in {$strongest}. Continued attention should be given to {$weakest} to maintain balance, strengthen governance and support sustainable AI adoption.",
        };
    }
}