<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\MaturityLevel;
use App\Services\GroqAIService;
use Illuminate\Support\Facades\Log;
use Throwable;

class ConsultantController extends Controller
{
   public function dashboard()
{
  $user = auth()->user();

    abort_unless(
        $user && $user->isConsultant(),
        403
    );

    /*
     * Consultants only work on paid
     * Transformation engagements.
     */
   $assessments = Assessment::with([
        'company',
        'roadmapPreference',
    ])
    ->where('status', 'completed')
    ->where('engagement_type', 'transformation')
    ->where('payment_status', 'paid')

    // Only Transformation requests assigned to this consultant
    ->where('assigned_consultant_id', $user->id)

    ->whereIn('transformation_status', [
        'submitted',
        'in_review',
        'roadmap_ready',
    ])
    ->latest('paid_at')
    ->get();

    /*
     * Transformation workflow counters.
     */
    $submittedCount = $assessments
        ->where('transformation_status', 'submitted')
        ->count();

    $inReviewCount = $assessments
        ->where('transformation_status', 'in_review')
        ->count();

    $roadmapReadyCount = $assessments
        ->where('transformation_status', 'roadmap_ready')
        ->count();

    /*
     * Still useful for displaying the organization's
     * current maturity level in the Transformation table.
     */
    $maturityLevels = MaturityLevel::orderBy('min_score')
        ->get();

    return view('consultant.dashboard', compact(
        'assessments',
        'maturityLevels',
        'submittedCount',
        'inReviewCount',
        'roadmapReadyCount'
    ));

    }

   public function review(Assessment $assessment)
{
    $user = auth()->user();

    // Consultant/reviewer only
    abort_unless($user->isConsultant(), 403);

    // Only completed assessments can be reviewed
    abort_unless($assessment->status === 'completed', 404);
// Consultants only review paid Transformation engagements
abort_unless(
    $assessment->engagement_type === 'transformation',
    403
);
// Only the consultant assigned to this assessment can access it
abort_unless(
    $assessment->assigned_consultant_id === $user->id,
    403,
    'This assessment is not assigned to you.'
);
abort_unless(
    $assessment->payment_status === 'paid',
    403
);

abort_unless(
    in_array($assessment->transformation_status, [
        'submitted',
        'in_review',
        'roadmap_ready',
    ], true),
    403
);
$assessment->load([
    'company',
    'roadmapPreference',
    'transformationRoadmap.initiatives',
]);
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

public function generateRoadmap(
    Assessment $assessment,
    GroqAIService $ai
) {
    $user = auth()->user();

$this->authorizeAssignedConsultant($assessment);

    /*
     * Only completed and paid Transformation engagements
     * can generate a roadmap.
     */
    abort_unless(
        $assessment->status === 'completed',
        404
    );

    abort_unless(
        $assessment->engagement_type === 'transformation',
        403
    );

    abort_unless(
        $assessment->payment_status === 'paid',
        403
    );

    abort_unless(
    in_array(
        $assessment->transformation_status,
        ['submitted', 'in_review'],
        true
    ),
    409
);

    /*
     * Client Transformation Brief.
     */
    $preference = $assessment->roadmapPreference;

    abort_unless($preference, 409);

    $targetMaturity = $preference->target_maturity;
    $timeline = $preference->timeframe;
    $budget = $preference->budget_level;
    $strategicPriorities = $preference->strategic_priorities ?? [];

    /*
     * Load assessment/company information.
     */
    $assessment->load('company');

    /*
     * Load assessment responses.
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

                // Convert 1–4 answer score to 0–100.
                $normalizedScore =
                    (($answerScore - 1) / 3) * 100;

                $weightedScore +=
                    $normalizedScore * $questionWeight;

                $totalWeight += $questionWeight;
            }

            return $totalWeight > 0
                ? $weightedScore / $totalWeight
                : 0;
        });

    /*
     * Determine maturity level for every dimension.
     */
    $maturityLevels = MaturityLevel::orderBy('min_score')->get();

    $dimensionMaturityLevels = $dimensionScores->map(
        function ($score) use ($maturityLevels) {

            return $maturityLevels->first(
                function ($level) use ($score) {
                    return $score >= $level->min_score
                        && $score <= $level->max_score;
                }
            );
        }
    )->map(function ($level) {

        return $level
            ? ($level->label ?? $level->name)
            : null;
    });

    /*
     * Determine current overall maturity.
     */
    $currentMaturity = $maturityLevels->first(
        function ($level) use ($assessment) {

            return $assessment->company_score >= $level->min_score
                && $assessment->company_score <= $level->max_score;
        }
    );

    /*
     * Structured evidence supplied to the AI.
     *
     * YARA calculates readiness.
     * The AI interprets the evidence and designs
     * the Transformation Roadmap.
     */
   
    /*
 * Detailed assessment evidence.
 *
 * The roadmap AI needs the actual questions and selected
 * answers — not only the final dimension scores.
 *
 * This allows recommendations to address specific capability
 * gaps identified during the assessment.
 */
$assessmentEvidence = $responses
    ->filter(function ($response) {
        return $response->question
            && $response->answerOption;
    })
    ->map(function ($response) {
        return [
            'dimension' =>
                $response->question->dimension?->name,

            'question' =>
                $response->question->text
                ?? $response->question->question
                ?? null,

            'selected_answer' =>
                $response->answerOption->text
                ?? $response->answerOption->label
                ?? null,

            'answer_score' =>
                $response->answerOption->score,
        ];
    })
    ->values()
    ->toArray();
    $roadmapContext = [
        'organization' => $assessment->company?->name,
        'industry' => $assessment->company?->industry,
        'country' => $assessment->company?->country,

        'organizational_readiness_score' =>
            round((float) $assessment->company_score, 1),

        'current_maturity' => [
            'level' => $currentMaturity?->level,

            'label' => $currentMaturity
                ? ($currentMaturity->label ?? $currentMaturity->name)
                : null,
        ],

        'dimension_scores' => $dimensionScores
            ->map(fn ($score) => round($score, 1))
            ->toArray(),

        'dimension_maturity_labels' =>
            $dimensionMaturityLevels->toArray(),
'assessment_evidence' => $assessmentEvidence,
        'client_transformation_brief' => [
            'target_maturity' => $targetMaturity,
            'timeline_horizon' => $timeline,

            /*
             * IMPORTANT:
             * This is the TOTAL roadmap budget,
             * not the budget for each initiative.
             */
            'total_budget' => $budget,

            'strategic_priorities' =>
                $strategicPriorities,
        ],
    ];

    /*
     * AI prompt.
     */
    $prompt = <<<PROMPT
You are a senior AI transformation consultant.

Your task is to generate a professional DRAFT AI Transformation Roadmap
for consultant review.

This roadmap will NOT be delivered directly to the client.

A human consultant will review, edit, add, remove and refine initiatives
before the roadmap is finalized.

Use ONLY the assessment evidence and client Transformation Brief
provided below.

==================================================
ASSESSMENT EVIDENCE
==================================================

{$this->prettyJson($roadmapContext)}

==================================================
CORE RULES
==================================================

1. Treat the organizational readiness score and dimension scores
   as authoritative YARA assessment results.

2. Do NOT recalculate assessment scores.

3. The roadmap must help the organization progress from its
   current maturity toward the CLIENT'S TARGET MATURITY.

4. Generate between 3 and 6 DISTINCT initiatives.

5. Initiatives must be specific and implementation-oriented.

BAD:
"Improve AI Governance."

GOOD:
"Establish an AI Governance Council and Decision Rights Framework."

6. Prioritize initiatives using:
   - weak assessment dimensions,
   - client-selected strategic priorities,
   - dependencies between capabilities,
   - implementation feasibility,
   - target maturity.

7. Do NOT blindly select only the lowest-scoring dimensions.

A moderately scoring dimension may require priority if it is:
   - explicitly selected by the client,
   - a prerequisite for another initiative,
   - necessary to reach the target maturity.

8. Avoid duplicate or overlapping initiatives.

==================================================
BUDGET RULES
==================================================

The client's budget is the TOTAL investment envelope for the
ENTIRE roadmap.

It is NOT a per-initiative budget.

The combined estimated investment of all initiatives must remain
realistically compatible with the client's total budget.

Allocate portions of the total budget across initiatives.

Do not copy the client's full budget range into every initiative.

Investment estimates must be realistic directional estimates.

Examples:

If total budget is "$10,000 - $50,000", initiatives might have
investment estimates such as:

"$3,000 - $7,000"
"$5,000 - $10,000"
"$8,000 - $15,000"

depending on the number and complexity of initiatives.

If the client selected "Prefer not to say", provide qualitative
investment estimates such as:

"Low"
"Medium"
"Medium-High"

==================================================
TIMELINE RULES
==================================================

The client's timeline is the TOTAL roadmap horizon.

It is NOT the duration of every initiative.

Sequence initiatives logically within that horizon.

Initiatives may overlap where appropriate.

For a 6-month roadmap, examples include:

"Months 1-2"
"Months 2-4"
"Months 3-6"

Do NOT simply write "6 months" for every initiative.

Account for dependencies.

Foundation initiatives should generally occur before capabilities
that depend on them.

==================================================
PRIORITY RULES
==================================================

Each initiative must have a priority:

"high"
"medium"
or
"low"

Use "high" selectively.

Strategic priorities selected by the client should receive meaningful
attention, but they do not automatically need to become separate
initiatives if they are already addressed by another initiative.

==================================================
BUSINESS RATIONALE
==================================================

For each initiative, explain WHY it belongs in this roadmap.

The rationale should connect the recommendation to:

- assessment evidence,
- maturity gaps,
- strategic priorities,
- dependencies,
- target maturity,
- business value.

Do not invent unsupported company facts.

==================================================
EFFORT AND IMPACT
==================================================

Effort must be one of:

"low"
"medium"
"high"

Impact must be one of:

"low"
"medium"
"high"

==================================================
STANDARDS
==================================================

Where genuinely relevant, reference recognized frameworks or standards,
for example:

- ISO/IEC 42001
- NIST AI RMF
- ISO/IEC 27001

Do NOT force a standard reference where none is useful.

==================================================
EXECUTIVE SUMMARY
==================================================

Write a concise executive summary explaining:

- the organization's current transformation situation,
- the most important capability gaps,
- how the roadmap supports the target maturity,
- how sequencing reflects the client's timeline and investment capacity.

Do not exaggerate certainty.
==================================================
IMPLEMENTATION DETAIL
==================================================

Each initiative must contain enough implementation detail
to serve as a professional consultant working draft.

For every initiative:

1. Provide 3 to 5 concrete recommended actions.

2. Describe the expected organizational outcome.

3. Provide 2 to 4 measurable success metrics.

4. Identify dependencies where relevant.

5. Recommendations must reflect specific assessment evidence.
Do not generate generic consulting advice that could apply
unchanged to any organization.

6. Where an assessment answer reveals a specific missing
capability, control, process or governance mechanism,
translate that evidence into an actionable recommendation.

7. Avoid repeating the same rationale, actions or outcomes
across initiatives.
==================================================
OUTPUT FORMAT
==================================================

Return VALID JSON ONLY.

No Markdown.
No code fences.
No commentary outside the JSON.

Use EXACTLY this structure:

{
  "summary": "Concise executive summary",

  "actions": [
    {
      "priority_rank": 1,
      "priority": "high",
      "title": "Specific initiative title",
      "dimension": "Relevant assessment dimension",

      "description":
        "Concrete implementation-oriented description of the initiative",

      "business_rationale":
        "Explain why this initiative is required based on the assessment evidence and transformation objectives",

      "recommended_actions": [
        "Specific implementation action 1",
        "Specific implementation action 2",
        "Specific implementation action 3"
      ],

      "expected_outcome":
        "Specific organizational capability or improvement expected from this initiative",

      "success_metrics": [
        "Measurable success indicator 1",
        "Measurable success indicator 2"
      ],

      "dependencies": [
        "Any prerequisite initiative or capability"
      ],

      "business_impact": "high",
      "effort": "medium",
      "timeline": "Months 1-2",
      "investment": "$5,000 - $10,000",
      "standard_reference": "ISO/IEC 42001"
    }
  ]
}

priority_rank must begin at 1.

Return 3 to 6 actions.

Order actions according to recommended implementation sequence.

PROMPT;

    /*
     * Ask AI to generate the draft.
     */
    try {

        $raw = trim($ai->generate($prompt));

        /*
         * Defensive cleanup in case the model wraps
         * the JSON in Markdown code fences.
         */
        $raw = preg_replace(
            '/^```(?:json)?\s*|\s*```$/i',
            '',
            $raw
        );

        $generated = json_decode($raw, true);

        if (
            !is_array($generated)
            || !isset($generated['actions'])
            || !is_array($generated['actions'])
            || count($generated['actions']) < 1
        ) {
            throw new \RuntimeException(
                'AI returned an invalid roadmap structure.'
            );
        }

    } catch (\Throwable $e) {

        \Log::error('Transformation roadmap generation failed', [
            'assessment_id' => $assessment->id,
            'consultant_id' => $user->id,
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' =>
                'The AI roadmap could not be generated. Please try again.',
        ], 500);
    }

   /*
 * Create the roadmap on first generation,
 * or reuse the existing draft during regeneration.
 */
$roadmap = $assessment->transformationRoadmap;

if (!$roadmap) {

    $roadmap = $assessment
        ->transformationRoadmap()
        ->create([
            'consultant_id' => $user->id,
            'status' => 'draft',
            'generated_at' => now(),
        ]);

} else {

    /*
     * Regeneration is only allowed while
     * the roadmap is still a draft.
     */
    abort_unless(
        $roadmap->status === 'draft',
        409
    );

    /*
     * Replace the previous AI-generated initiatives.
     *
     * Later, once consultant editing is implemented,
     * we can protect manually edited drafts from
     * accidental regeneration.
     */
    $roadmap->initiatives()->delete();

    $roadmap->update([
        'consultant_id' => $user->id,
        'generated_at' => now(),
    ]);
}

    /*
     * Convert AI actions into editable database initiatives.
     */
    foreach ($generated['actions'] as $index => $action) {

        /*
         * Normalize AI values.
         */
        $priority = strtolower(
            trim($action['priority'] ?? 'medium')
        );

        if (!in_array($priority, ['low', 'medium', 'high'], true)) {
            $priority = 'medium';
        }

        $effort = strtolower(
            trim($action['effort'] ?? 'medium')
        );

        if (!in_array($effort, ['low', 'medium', 'high'], true)) {
            $effort = 'medium';
        }

        $impact = strtolower(
            trim($action['business_impact'] ?? 'medium')
        );

        if (!in_array($impact, ['low', 'medium', 'high'], true)) {
            $impact = 'medium';
        }

        $roadmap->initiatives()->create([
            'title' =>
                $action['title'] ?? 'Transformation Initiative',

            'description' =>
                $action['description'] ?? '',

          'business_rationale' =>
    $action['business_rationale'] ?? null,

'recommended_actions' =>
    is_array($action['recommended_actions'] ?? null)
        ? $action['recommended_actions']
        : [],

'expected_outcome' =>
    $action['expected_outcome'] ?? null,

'success_metrics' =>
    is_array($action['success_metrics'] ?? null)
        ? $action['success_metrics']
        : [],

'dependencies' =>
    is_array($action['dependencies'] ?? null)
        ? $action['dependencies']
        : [],

'dimension' =>
    $action['dimension'] ?? null,

            'priority' =>
                $priority,

            /*
             * AI "timeline" maps to our database "phase".
             */
            'phase' =>
                $action['timeline'] ?? null,

            'effort' =>
                $effort,

            'impact' =>
                $impact,

            'investment' =>
                $action['investment'] ?? null,

            'standard_reference' =>
                $action['standard_reference'] ?? null,

            /*
             * Human-owned field.
             * AI deliberately does not populate this.
             */
            'consultant_guidance' =>
                null,

            /*
             * We use sort_order rather than storing
             * a duplicate priority_rank column.
             */
            'sort_order' =>
                (int) ($action['priority_rank'] ?? ($index + 1)),
        ]);
    }

    /*
     * Roadmap development has now begun.
     */
    $assessment->update([
        'transformation_status' => 'in_review',
        'reviewed_by' => $user->id,
    ]);

    /*
     * Load initiatives in their recommended order.
     */
    $roadmap->load([
        'initiatives' => function ($query) {
            $query->orderBy('sort_order');
        },
    ]);

    /*
     * Return JSON.
     *
     * The frontend updates only the roadmap workspace.
     * The page does NOT reload.
     */
    return response()->json([
        'success' => true,

        'message' =>
            'AI roadmap draft generated successfully.',

        'roadmap' => [
            'id' => $roadmap->id,
            'status' => $roadmap->status,

            'summary' =>
                $generated['summary'] ?? null,

            'context' => [
                'current_readiness' =>
                    $assessment->company_score,

                'current_maturity' =>
                    $currentMaturity
                        ? ($currentMaturity->label ?? $currentMaturity->name)
                        : null,

                'target_maturity' =>
                    $targetMaturity,

                'timeline' =>
                    $timeline,

                'budget' =>
                    $budget,

                'strategic_priorities' =>
                    $strategicPriorities,
            ],

            'initiatives' =>
                $roadmap->initiatives
                    ->map(function ($initiative) {

                        return [
                            'id' =>
                                $initiative->id,

                            'title' =>
                                $initiative->title,

                            'description' =>
                                $initiative->description,

                           'business_rationale' =>
    $initiative->business_rationale,

'recommended_actions' =>
    $initiative->recommended_actions ?? [],

'expected_outcome' =>
    $initiative->expected_outcome,

'success_metrics' =>
    $initiative->success_metrics ?? [],

'dependencies' =>
    $initiative->dependencies ?? [],

'dimension' =>
    $initiative->dimension,

                            'priority' =>
                                $initiative->priority,

                            'phase' =>
                                $initiative->phase,

                            'effort' =>
                                $initiative->effort,

                            'impact' =>
                                $initiative->impact,

                            'investment' =>
                                $initiative->investment,

                            'standard_reference' =>
                                $initiative->standard_reference,

                            'consultant_guidance' =>
                                $initiative->consultant_guidance,
                        ];
                    })
                    ->values(),
        ],
    ]);
}

public function updateRoadmapInitiative(
    Request $request,
    \App\Models\RoadmapInitiative $initiative
) {
    $user = auth()->user();

    abort_unless($user->isConsultant(), 403);

    $initiative->load('transformationRoadmap.assessment');

    $roadmap = $initiative->transformationRoadmap;
    $assessment = $roadmap?->assessment;

    abort_unless($roadmap && $assessment, 404);
    
$this->authorizeAssignedConsultant($assessment);
    abort_unless(
        $assessment->engagement_type === 'transformation',
        403
    );

    // Finalized roadmaps cannot be edited.
    abort_if(
        $roadmap->status === 'ready' || $roadmap->finalized_at !== null,
        409,
        'This roadmap has already been finalized.'
    );

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:5000',

        'business_rationale' => 'nullable|string|max:5000',

        'recommended_actions' => 'nullable|array|max:10',
        'recommended_actions.*' => 'nullable|string|max:1000',

        'expected_outcome' => 'nullable|string|max:3000',

        'success_metrics' => 'nullable|array|max:10',
        'success_metrics.*' => 'nullable|string|max:1000',

        'dependencies' => 'nullable|array|max:10',
        'dependencies.*' => 'nullable|string|max:500',

'dimension' => 'required|string|max:255',
        'priority' => 'required|in:high,medium,low',

        'phase' => 'nullable|string|max:255',

        'investment' => 'nullable|string|max:255',

        'effort' => 'nullable|in:low,medium,high',

        'impact' => 'nullable|in:low,medium,high',

        'standard_reference' => 'nullable|string|max:500',

        'consultant_guidance' => 'nullable|string|max:5000',
    ]);

    // Clean empty array items.
    foreach ([
        'recommended_actions',
        'success_metrics',
        'dependencies',
    ] as $field) {

        $validated[$field] = collect($validated[$field] ?? [])
            ->filter(fn ($value) =>
                is_string($value) && trim($value) !== ''
            )
            ->map(fn ($value) => trim($value))
            ->values()
            ->all();
    }

    $initiative->update($validated);

    $initiative->refresh();

    return response()->json([
        'success' => true,
        'message' => 'Initiative updated successfully.',

        'initiative' => [
            'id' => $initiative->id,
            'title' => $initiative->title,
            'description' => $initiative->description,

            'business_rationale' =>
                $initiative->business_rationale,

            'recommended_actions' =>
                $initiative->recommended_actions ?? [],

            'expected_outcome' =>
                $initiative->expected_outcome,

            'success_metrics' =>
                $initiative->success_metrics ?? [],

            'dependencies' =>
                $initiative->dependencies ?? [],

            'dimension' => $initiative->dimension,
            'priority' => $initiative->priority,
            'phase' => $initiative->phase,
            'investment' => $initiative->investment,
            'effort' => $initiative->effort,
            'impact' => $initiative->impact,

            'standard_reference' =>
                $initiative->standard_reference,

            'consultant_guidance' =>
                $initiative->consultant_guidance,
        ],
    ]);
}
public function deleteRoadmapInitiative(
    \App\Models\RoadmapInitiative $initiative
) {
    $user = auth()->user();

    abort_unless($user->isConsultant(), 403);

    /*
     * Load the roadmap and assessment so we can verify
     * that this initiative belongs to a Transformation roadmap.
     */
    $initiative->load('transformationRoadmap.assessment');

    $roadmap = $initiative->transformationRoadmap;
    $assessment = $roadmap?->assessment;

    abort_unless($roadmap && $assessment, 404);
// Only the assigned consultant may delete this initiative
$this->authorizeAssignedConsultant($assessment);
    abort_unless(
        $assessment->engagement_type === 'transformation',
        403
    );

    /*
     * A finalized roadmap is locked.
     */
    abort_if(
        $roadmap->status === 'ready' || $roadmap->finalized_at !== null,
        409,
        'This roadmap has already been finalized.'
    );

    /*
     * Don't allow the consultant to accidentally leave
     * the roadmap completely empty.
     */
    abort_if(
        $roadmap->initiatives()->count() <= 1,
        409,
        'A roadmap must contain at least one initiative.'
    );

    $initiativeId = $initiative->id;

    $initiative->delete();

    /*
     * Re-number remaining initiatives.
     */
    $roadmap->initiatives()
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get()
        ->values()
        ->each(function ($item, $index) {
            $item->update([
                'sort_order' => $index + 1,
            ]);
        });

    return response()->json([
        'success' => true,
        'message' => 'Initiative removed successfully.',
        'deleted_id' => $initiativeId,
    ]);
}
public function createRoadmapInitiative(
    Request $request,
    Assessment $assessment
) {
    $user = auth()->user();

    abort_unless($user->isConsultant(), 403);
// Only the assigned consultant may add initiatives
$this->authorizeAssignedConsultant($assessment);
    /*
     * Transformation engagements only.
     */
    abort_unless(
        $assessment->engagement_type === 'transformation',
        403
    );

    /*
     * Get the existing roadmap.
     */
    $roadmap = $assessment->transformationRoadmap;

    abort_unless($roadmap, 404);

    /*
     * Finalized roadmaps are locked.
     */
    abort_if(
        $roadmap->status === 'ready' || $roadmap->finalized_at !== null,
        409,
        'This roadmap has already been finalized.'
    );

    /*
     * Validate consultant-created initiative.
     */
    $validated = $request->validate([

        'title' => 'required|string|max:255',

        'description' => 'required|string|max:5000',

        'business_rationale' => 'nullable|string|max:5000',

        'recommended_actions' => 'nullable|array|max:10',
        'recommended_actions.*' => 'nullable|string|max:1000',

        'expected_outcome' => 'nullable|string|max:3000',

        'success_metrics' => 'nullable|array|max:10',
        'success_metrics.*' => 'nullable|string|max:1000',

        'dependencies' => 'nullable|array|max:10',
        'dependencies.*' => 'nullable|string|max:500',

'dimension' => 'required|string|max:255',
        'priority' => 'required|in:high,medium,low',

        'phase' => 'nullable|string|max:255',

        'investment' => 'nullable|string|max:255',

        'effort' => 'nullable|in:low,medium,high',

        'impact' => 'nullable|in:low,medium,high',

        'standard_reference' => 'nullable|string|max:500',

        'consultant_guidance' => 'nullable|string|max:5000',
    ]);

    /*
     * Clean list fields.
     */
    foreach ([
        'recommended_actions',
        'success_metrics',
        'dependencies',
    ] as $field) {

        $validated[$field] = collect($validated[$field] ?? [])
            ->filter(fn ($value) =>
                is_string($value) && trim($value) !== ''
            )
            ->map(fn ($value) => trim($value))
            ->values()
            ->all();
    }

    /*
     * New consultant initiative goes at the end.
     */
    $nextSortOrder =
        ((int) $roadmap->initiatives()->max('sort_order')) + 1;

    $validated['sort_order'] = $nextSortOrder;

    /*
     * Create through the relationship so
     * transformation_roadmap_id is set automatically.
     */
    $initiative = $roadmap
        ->initiatives()
        ->create($validated);

    return response()->json([
        'success' => true,

        'message' => 'Initiative added successfully.',

        'initiative' => [
            'id' => $initiative->id,
            'title' => $initiative->title,
            'description' => $initiative->description,

            'business_rationale' =>
                $initiative->business_rationale,

            'recommended_actions' =>
                $initiative->recommended_actions ?? [],

            'expected_outcome' =>
                $initiative->expected_outcome,

            'success_metrics' =>
                $initiative->success_metrics ?? [],

            'dependencies' =>
                $initiative->dependencies ?? [],

            'dimension' => $initiative->dimension,
            'priority' => $initiative->priority,

            'phase' => $initiative->phase,
            'investment' => $initiative->investment,

            'effort' => $initiative->effort,
            'impact' => $initiative->impact,

            'standard_reference' =>
                $initiative->standard_reference,

            'consultant_guidance' =>
                $initiative->consultant_guidance,

            'sort_order' =>
                $initiative->sort_order,
        ],
    ]);
}
public function finalizeRoadmap(Assessment $assessment)
{
    $user = auth()->user();

    /*
     * Consultant only.
     */
    abort_unless($user->isConsultant(), 403);
// Only the assigned consultant may finalize this roadmap
$this->authorizeAssignedConsultant($assessment);
    /*
     * Transformation engagement only.
     */
    abort_unless(
        $assessment->engagement_type === 'transformation',
        403
    );

    /*
     * Assessment must be completed and paid.
     */
    abort_unless(
        $assessment->status === 'completed',
        409,
        'The assessment must be completed before finalizing the roadmap.'
    );

    abort_unless(
        $assessment->payment_status === 'paid',
        409,
        'The Transformation engagement must be paid before finalizing the roadmap.'
    );

    /*
     * Roadmap must already exist.
     */
    $roadmap = $assessment->transformationRoadmap;

    abort_unless(
        $roadmap,
        404,
        'No roadmap exists for this assessment.'
    );

    /*
     * Prevent double finalization.
     */
    abort_if(
        $roadmap->status === 'ready' || $roadmap->finalized_at !== null,
        409,
        'This roadmap has already been finalized.'
    );

    /*
     * A roadmap cannot be finalized without initiatives.
     */
    abort_if(
        $roadmap->initiatives()->count() === 0,
        409,
        'Add at least one initiative before finalizing the roadmap.'
    );

    /*
     * Final consultant approval.
     */
    $roadmap->update([
        'status' => 'ready',
        'finalized_at' => now(),
        'consultant_id' => $user->id,
    ]);

    /*
     * Transformation workflow is now complete.
     *
     * We use "completed" here so this is clearly different from
     * "in_review", which represents consultant work in progress.
     */
    $assessment->update([
    'transformation_status' => 'roadmap_ready',
    'reviewed_by' => $user->id,
    'reviewed_at' => now(),
]);

    $roadmap->refresh();

    /*
     * JSON because finalization will happen without a page reload.
     */
    return response()->json([
        'success' => true,

        'message' => 'Roadmap finalized successfully.',

        'roadmap' => [
            'id' => $roadmap->id,
            'status' => $roadmap->status,
            'finalized_at' => $roadmap->finalized_at?->toISOString(),
        ],

        'transformation_status' =>
            $assessment->transformation_status,
    ]);
}
public function updateRoadmapGuidance(Request $request, Assessment $assessment)
{
    // Only the assigned consultant may edit roadmap guidance
    $this->authorizeAssignedConsultant($assessment);
    $roadmap = $assessment->transformationRoadmap;

    if (!$roadmap) {
        return response()->json([
            'message' => 'Roadmap not found.',
        ], 404);
    }

    /*
     * A finalized roadmap is locked.
     */
    if ($roadmap->status === 'ready') {
        return response()->json([
            'message' => 'This roadmap has already been finalized.',
        ], 422);
    }

    $validated = $request->validate([
        'consultant_notes' => [
            'nullable',
            'string',
            'max:10000',
        ],

        'risks_dependencies' => [
            'nullable',
            'string',
            'max:10000',
        ],
    ]);

    $roadmap->update([
        'consultant_notes' =>
            $validated['consultant_notes'] ?? null,

        'risks_dependencies' =>
            $validated['risks_dependencies'] ?? null,
    ]);

    return response()->json([
        'success' => true,
    ]);
}
public function submitReview(Request $request, Assessment $assessment)
{
    $user = auth()->user();

    // Only consultants/reviewers can submit reviews
    abort_unless($user->isConsultant(), 403);
    // Only the assigned consultant may submit the review
$this->authorizeAssignedConsultant($assessment);

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
private function authorizeAssignedConsultant(
    Assessment $assessment
): void {
    $user = auth()->user();

    /*
     * Consultant accounts only.
     */
    abort_unless(
        $user && $user->isConsultant(),
        403
    );

    /*
     * A consultant may only access Transformation
     * engagements explicitly assigned to them.
     */
    abort_unless(
        (int) $assessment->assigned_consultant_id === (int) $user->id,
        403,
        'This Transformation request is not assigned to you.'
    );
}
private function prettyJson(array $data): string
{
    return json_encode(
        $data,
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE
    );
}
}