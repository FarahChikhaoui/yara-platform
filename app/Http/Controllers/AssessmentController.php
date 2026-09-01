<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\CountryAIReadinessScore;
use App\Models\Dimension;
use App\Models\MaturityLevel;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Services\GroqAIService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\BenchmarkDataset;

class AssessmentController extends Controller
{
  public function start()
{
    /*
     * Determine which journey brought the client here.
     *
     * - Normal assessment journey:
     *      self_assessment
     *
     * - Transformation journey:
     *      transformation + planning
     *
     * "planning" means the client intends to purchase Transformation,
     * NOT that payment has been completed.
     */
    $isTransformationJourney =
        session('assessment_intent') === 'transformation';


    /*
     * Resume an existing unfinished assessment ONLY if it belongs
     * to the same journey.
     *
     * This prevents "Start Transformation" from accidentally resuming
     * an unfinished normal self-assessment, and vice versa.
     */
    $assessment = Assessment::where(
            'company_id',
            auth()->user()->company_id
        )
        ->where('user_id', auth()->id())
        ->where('status', 'in_progress')
        ->where(
            'engagement_type',
            $isTransformationJourney
                ? 'transformation'
                : 'self_assessment'
        )
        ->latest()
        ->first();


    /*
     * No unfinished assessment exists for this journey,
     * so create the correct type.
     */
    if (!$assessment) {
        $assessment = Assessment::create([
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->id(),
            'title' => 'AI Readiness Assessment',
            'status' => 'in_progress',

            'framework_version' => config('yara.framework_version'),

            'engagement_type' => $isTransformationJourney
                ? 'transformation'
                : 'self_assessment',

            'transformation_status' => $isTransformationJourney
                ? 'planning'
                : null,
        ]);
    }


    $dimensions = Dimension::with([
        'questions' => function ($query) {
            $query->where('is_active', true)
                ->orderBy('order')
                ->with([
                    'answerOptions' => function ($answerQuery) {
                        $answerQuery->orderBy('score');
                    },
                ]);
        },
    ])->get();


    return view(
        'assessments.start',
        compact('dimensions', 'assessment')
    );
}
public function startTransformationFromAssessment(Assessment $assessment)
{
    /*
     * Only the client who owns this assessment
     * may start Transformation from it.
     */
    $this->authorizeClientAssessment($assessment);

    /*
     * Only a completed assessment can be used
     * as the basis for a Transformation Roadmap.
     */
    abort_unless(
        $assessment->status === 'completed',
        422
    );

    /*
     * IMPORTANT:
     * Do NOT convert the assessment into a Transformation
     * engagement yet.
     *
     * At this stage, the client is only exploring/configuring
     * the paid Transformation service.
     *
     * The assessment becomes a Transformation engagement
     * only after successful payment.
     */
    return redirect()
        ->route('transformation.brief', $assessment);
}
public function transformationBrief(Assessment $assessment)
{
    /*
     * Only the client who owns the assessment
     * may access its Transformation brief.
     */
    $this->authorizeClientAssessment($assessment);

    /*
     * A Transformation Roadmap can only be built
     * from a completed readiness assessment.
     */
    abort_unless(
        $assessment->status === 'completed',
        404
    );

    /*
     * If this Transformation has already been purchased,
     * the client should no longer edit the planning brief.
     */
    abort_if(
        $assessment->payment_status === 'paid',
        409,
        'This Transformation Roadmap has already been purchased.'
    );

    /*
     * Determine the organization's current
     * maturity level.
     */
    $maturityLevels = MaturityLevel::orderBy('level')->get();

    $currentMaturity = $maturityLevels->first(
        function ($level) use ($assessment) {
            return $assessment->company_score >= $level->min_score
                && $assessment->company_score <= $level->max_score;
        }
    );

    $currentLevelValue = $currentMaturity->level ?? 0;

    /*
     * The Transformation target must represent
     * progress beyond the current maturity level.
     */
    $targetLevelOptions = $maturityLevels
        ->filter(
            fn ($level) => $level->level > $currentLevelValue
        )
        ->values();

    /*
     * Load an existing draft brief if the client
     * previously started the Transformation journey
     * but did not complete payment.
     */
    $preference = $assessment->roadmapPreference;

    return view(
        'assessments.transformation-brief',
        compact(
            'assessment',
            'currentMaturity',
            'currentLevelValue',
            'targetLevelOptions',
            'preference'
        )
    );
}

   public function startTransformation()
{
    /*
     * Starting Transformation is its own journey.
     *
     * Do not reuse a previous self-assessment here.
     * The Transformation journey starts with its own
     * prerequisite readiness assessment.
     */
    session([
        'assessment_intent' => 'transformation',
    ]);

    return redirect()->route('assessment.start');
}
public function resume(Assessment $assessment)
{
   // Only the client who owns this assessment may resume it
$this->authorizeClientAssessment($assessment);

    abort_unless($assessment->status === 'in_progress', 404);

    $dimensions = Dimension::with([
        'questions' => function ($query) {
            $query->where('is_active', true)
                ->orderBy('order')
                ->with([
                    'answerOptions' => function ($answerQuery) {
                        $answerQuery->orderBy('score');
                    },
                ]);
        },
    ])->get();

    $savedAnswers = Response::where('assessment_id', $assessment->id)
        ->pluck('answer_option_id', 'question_id');

    return view(
        'assessments.start',
        compact('dimensions', 'assessment', 'savedAnswers')
    );
}
public function saveAnswer(Request $request, Assessment $assessment)
{
   // -------------------------------------------------
// ACCESS CONTROL
// -------------------------------------------------

// Only the client who owns this assessment
// may save or change its answers.
$this->authorizeClientAssessment($assessment);

    // Only unfinished assessments can be modified
    abort_unless(
        $assessment->status === 'in_progress',
        422
    );


    // -------------------------------------------------
    // VALIDATION
    // -------------------------------------------------

    $validated = $request->validate([
        'question_id' => 'required|exists:questions,id',
        'answer_option_id' => 'required|exists:answer_options,id',
    ]);


    // -------------------------------------------------
    // SAVE / UPDATE ANSWER
    // -------------------------------------------------

    Response::updateOrCreate(
        [
            'assessment_id' => $assessment->id,
            'question_id' => $validated['question_id'],
        ],
        [
            'answer_option_id' => $validated['answer_option_id'],
        ]
    );


    return response()->json([
        'success' => true,
    ]);
}
    public function submit(Request $request)
{
    $request->validate([
        'assessment_id' => 'required|exists:assessments,id',
        'answers' => 'required|array',
        'answers.*' => 'required|exists:answer_options,id',
    ]);

    $assessment = Assessment::with('company')
        ->findOrFail($request->assessment_id);
// Security: only the owner may submit this assessment.
$this->authorizeClientAssessment($assessment);

    // -------------------------------------------------
    // REQUIRE ALL ACTIVE QUESTIONS TO BE ANSWERED
    // -------------------------------------------------

    $activeQuestionIds = \App\Models\Question::where('is_active', true)
        ->pluck('id');

    $submittedQuestionIds = collect(
        array_keys($request->answers ?? [])
    )->map(function ($id) {
        return (int) $id;
    });


    $missingQuestionIds = $activeQuestionIds->diff(
        $submittedQuestionIds
    );


    if ($missingQuestionIds->isNotEmpty()) {

        return back()
    ->withInput()
    ->with(
        'assessment_error',
        'Please answer all questions before submitting the assessment.'
    )
    ->with(
        'missing_questions_count',
        $missingQuestionIds->count()
    );
    }
        
        /*
         * Prevent duplicate responses if the form is accidentally submitted twice.
         */
        Response::where('assessment_id', $assessment->id)->delete();

        foreach ($request->answers as $questionId => $answerOptionId) {
            Response::create([
                'assessment_id' => $assessment->id,
                'question_id' => $questionId,
                'answer_option_id' => $answerOptionId,
            ]);
        }

        $responses = Response::with(['answerOption', 'question'])
            ->where('assessment_id', $assessment->id)
            ->get();

        [$companyScore] = $this->computeWeightedScore($responses);

       $countryBenchmark = null;

/*
 * Use the benchmark dataset explicitly activated
 * by the YARA administrator.
 */
$activeBenchmarkDataset = BenchmarkDataset::where(
    'is_active',
    true
)->first();

if (
    $assessment->company?->country
    && $activeBenchmarkDataset
) {
    $countryBenchmark = CountryAIReadinessScore::whereRaw(
        'LOWER(TRIM(country)) = ?',
        [strtolower(trim($assessment->company->country))]
    )
        ->where('year', $activeBenchmarkDataset->year)
        ->first();
}

$countryScore = $countryBenchmark?->score;
$countryYear = $countryBenchmark?->year;

        /*
         * YARA Composite Score
         *
         * Organizational readiness remains the primary component.
         * Country AI readiness provides external contextual adjustment.
         *
         * 80% = Organization AI Readiness
         * 20% = Country AI Readiness
         *
         * If no country benchmark is available, the company score
         * becomes the composite score.
         */
        $combinedScore = $countryScore !== null
            ? ($companyScore * 0.80) + ($countryScore * 0.20)
            : $companyScore;

        $assessment->update([
            'status' => 'completed',
            'company_score' => round($companyScore, 2),
            'country_ai_score' => $countryScore !== null
                ? round($countryScore, 2)
                : null,
            'country_ai_year' => $countryYear,
            'combined_score' => round($combinedScore, 2),
        ]);

if ($assessment->engagement_type === 'transformation') {
    return redirect()
        ->route('assessment.results', $assessment)
        ->with('show_transformation_brief', true);
}

return redirect()
    ->route('assessment.results', $assessment);
}
public function generateAiSummary(
    Request $request,
    Assessment $assessment,
    GroqAIService $ai
) {
    /*
 * Security:
 * only the client who owns this assessment
 * may generate its AI strategic analysis.
 */
$this->authorizeClientAssessment($assessment);

    /*
     * AI analysis only makes sense once
     * the assessment has been completed.
     */
    abort_unless(
        $assessment->status === 'completed',
        404
    );

    /*
     * Load the organization.
     */
    $assessment->load('company');

    /*
     * Retrieve all responses with the question,
     * dimension and selected answer.
     */
    $responses = \App\Models\Response::with([
        'answerOption',
        'question.dimension',
    ])
        ->where('assessment_id', $assessment->id)
        ->get();

    /*
     * Calculate the score of each assessment dimension.
     * (shared helper — see computeDimensionScores())
     */
    $dimensionScores = $this->computeDimensionScores($responses)
        ->map(fn ($score) => round($score, 1));

    /*
     * Determine the overall maturity level.
     */
    $maturityLevels = MaturityLevel::orderBy('min_score')->get();

    $overallMaturity = $maturityLevels->first(
        function ($level) use ($assessment) {
            return $assessment->company_score >= $level->min_score
                && $assessment->company_score <= $level->max_score;
        }
    );

    /*
     * Build structured assessment data.
     *
     * The AI receives calculated YARA results.
     * It does NOT calculate the official scores itself.
     */
    $assessmentData = [
        'organization' => $assessment->company?->name,
        'industry' => $assessment->company?->industry,
        'country' => $assessment->company?->country,

        'organizational_readiness_score' =>
            round((float) $assessment->company_score, 1),

        'country_ai_readiness_score' =>
            $assessment->country_ai_score !== null
                ? round((float) $assessment->country_ai_score, 1)
                : null,

        'country_benchmark_year' =>
            $assessment->country_ai_year,

        'yara_composite_score' =>
            $assessment->combined_score !== null
                ? round((float) $assessment->combined_score, 1)
                : null,

        'overall_maturity' =>
            $overallMaturity
                ? ($overallMaturity->label ?? $overallMaturity->name)
                : null,

        'dimension_scores' =>
            $dimensionScores->toArray(),
    ];

    /*
     * Prompt grounding rules are important:
     * the model must analyze YARA data rather than
     * invent organization-specific information.
     */
 $prompt = <<<PROMPT
You are the strategic AI analysis engine of YARA, an organizational AI readiness assessment platform.

Your purpose is NOT to summarize or repeat assessment scores.
Your purpose is to interpret the relationship between the organization's readiness dimensions and turn the assessment into useful strategic decision support.

Use ONLY the supplied YARA assessment data.

STRICT RULES:
- Never invent facts about the organization.
- Never invent technologies, projects, budgets, employees, policies, regulations, or business circumstances.
- Never invent information about the country's AI ecosystem.
- Treat all supplied YARA scores and maturity levels as authoritative.
- Do not recalculate the official scores.
- Base strengths and weaknesses only on the supplied dimension scores.
- Recommendations must logically follow from the assessment results.
- Distinguish evidence from interpretation.
- Avoid generic statements such as "continue improving AI readiness."
- Do not simply list or repeat all scores.
- Do not use Markdown.
- Return ONLY valid JSON.
- Do not include ```json or code fences.
Do not infer that a specific policy, process, technology, control,
team or governance mechanism exists or does not exist unless the
assessment data explicitly establishes that fact.

Use language such as "suggests", "indicates", or "may reflect" when
interpreting scores.

Do not provide an action plan or roadmap.
Do not recommend budgets, timelines or implementation activities.
The purpose of this analysis is diagnosis and strategic interpretation only.

The readiness_pattern must compare or connect at least two dimensions.
Do not simply identify another high or low score.

The headline must be a single sentence (max ~18 words) that states the
single most important takeaway from this assessment — the one thing an
executive should remember if they read nothing else. It must be specific
to this organization's data, not a generic statement.

Analyze relationships between dimensions where useful. For example, identify situations where stronger adoption or strategy is not matched by governance, data, security, workforce, or operational readiness.

Return EXACTLY this JSON structure:

{
    "headline": "Single-sentence executive takeaway grounded in the data above.",

    "priority_risk": {
        "title": "Short descriptive title",
        "dimension": "Relevant dimension name",
        "insight": "Explain what the assessment evidence suggests and why this weakness matters strategically."
    },

    "strategic_strength": {
        "title": "Short descriptive title",
        "dimension": "Relevant dimension name",
        "insight": "Explain what the assessment evidence suggests and how this strength could support future AI development."
    },

    "readiness_pattern": {
        "title": "Short descriptive title",
        "insight": "Identify the most meaningful relationship, imbalance or dependency between multiple readiness dimensions."
    }
}

Note: "dimension" values in priority_risk and strategic_strength must exactly match one of the dimension names supplied in dimension_scores below, so they can be cross-referenced with the organization's action plan.

ASSESSMENT DATA:
PROMPT;

$prompt .= "\n" . json_encode(
    $assessmentData,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
);

    /*
     * Send the grounded prompt to the LLM.
     *
     * Network/API failures (timeouts, rate limits, provider outages) are
     * common with third-party LLM calls and previously had no handling
     * here — an exception would have surfaced as a raw 500 error page.
     */
    try {
        $summary = $ai->generate($prompt);
    } catch (Throwable $e) {
        Log::error('AI executive summary generation failed', [
            'assessment_id' => $assessment->id,
            'message' => $e->getMessage(),
        ]);

        $message = 'The AI analysis service is temporarily unavailable. Please try again in a moment.';

        if ($request->wantsJson()) {
    return response()->json([
        'success' => false,
        'message' => $message,
    ], 503);
}

return back()->with(
    'error',
    $message
);
       
    }

    $analysis = json_decode($summary, true);

    if (
        json_last_error() !== JSON_ERROR_NONE ||
        !is_array($analysis) ||
        empty($analysis['priority_risk']) ||
        empty($analysis['strategic_strength']) ||
        empty($analysis['readiness_pattern'])
    ) {
        Log::warning('AI executive summary returned unexpected format', [
            'assessment_id' => $assessment->id,
            'raw' => $summary ?? null,
        ]);

        $message = 'The AI analysis could not be generated in the expected format. Please try again.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return back()->with(
            'error',
            $message
        );
    }

    /*
     * Save the generated analysis, plus when it was generated so the
     * results view can flag it as stale if the assessment is later
     * recalculated (e.g. resubmitted with different answers).
     *
     * Requires an `ai_summary_generated_at` (nullable timestamp) column
     * on the assessments table, cast to `datetime` on the Assessment model.
     */
$assessment->update([
    'ai_executive_summary' => json_encode(
        $analysis,
        JSON_UNESCAPED_UNICODE
    ),
    'ai_summary_generated_at' => now(),
]);

   if ($request->wantsJson()) {
    return response()->json([
        'success' => true,
        'message' => 'AI strategic analysis generated successfully.',
        'html' => $this->renderAiSummaryResults($assessment, $analysis),
    ]);
}

    return redirect(
        '/assessment/results/' . $assessment->id
    )->with(
        'success',
'AI strategic analysis generated successfully.'    );
}

/**
 * Generate a personalized, AI-authored transformation roadmap.
 *
 * Unlike generateAiSummary() (pure diagnosis, no action plan), this is
 * explicitly the action-plan generator. It's gated behind a short
 * questionnaire (target maturity level, budget, timeline, optional focus
 * areas) submitted from the roadmap planning form on the results page,
 * so the AI can tailor actions to what the organization can actually
 * commit to — instead of a generic, one-size-fits-all plan.
 *
 * Requires the following nullable columns on `assessments`:
 *   - ai_roadmap (json)
 *   - roadmap_inputs (json)
 *   - roadmap_generated_at (timestamp)
 */
public function generateRoadmap(
    Request $request,
    Assessment $assessment,
    GroqAIService $ai
) {
   /*
 * Security:
 * only the client who owns this assessment
 * may generate its Transformation roadmap.
 */
$this->authorizeClientAssessment($assessment);

    /*
     * A roadmap only makes sense once
     * the assessment has been completed.
     */
    abort_unless(
        $assessment->status === 'completed',
        404
    );

    $assessment->load('company');

    /*
     * Determine the organization's current maturity level so the
     * chosen target level can be validated against it — a target
     * must represent genuine forward progress.
     */
    $maturityLevels = MaturityLevel::orderBy('min_score')->get();

    $currentMaturity = $maturityLevels->first(
        function ($level) use ($assessment) {
            return $assessment->company_score >= $level->min_score
                && $assessment->company_score <= $level->max_score;
        }
    );

    $currentLevelValue = $currentMaturity->level ?? 0;

    $validLevels = $maturityLevels
        ->pluck('level')
        ->filter(fn ($level) => $level > $currentLevelValue)
        ->values();

    /*
     * Basic shape validation first. Feasibility (is this target level
     * realistic given the stated budget/timeline?) is checked separately
     * below via ->after(), so field errors and the feasibility error can
     * both surface together in one pass instead of the user fixing one
     * only to hit the other on the next submit.
     */
    $validator = Validator::make($request->all(), [
        'target_level' => ['required', 'integer'],
        'budget' => 'required|string|max:100',
        'timeline' => 'required|string|max:100',
        'focus_areas' => 'nullable|array|max:3',
        'focus_areas.*' => 'string|max:150',
    ]);

    $validator->after(function ($validator) use ($request, $validLevels, $currentLevelValue) {
        $targetLevel = (int) $request->input('target_level');

        if (!$validLevels->contains($targetLevel)) {
            $validator->errors()->add(
                'target_level',
                'Please select a target maturity level above your current level.'
            );

            return;
        }

        /*
         * Guard against unrealistic combinations — e.g. an organization
         * at Level 1 asking to reach Level 4 in 3 months on "Under
         * $10,000". Rather than silently sending that to the AI (which
         * would have to either invent a fictional shortcut or ignore the
         * constraints), we cap how far a single roadmap cycle can
         * realistically move based on the stated budget and timeline,
         * and ask for either a more realistic target or a bigger
         * budget/timeline instead.
         */
        $levelJump = $targetLevel - $currentLevelValue;

        $maxFeasibleJump = app(self::class)->maxFeasibleLevelJump(
            $request->input('budget'),
            $request->input('timeline')
        );

        if ($levelJump > $maxFeasibleJump) {
            $suggestedLevel = $currentLevelValue + $maxFeasibleJump;

            $validator->errors()->add(
                'target_level',
                "Reaching Level {$targetLevel} from your current Level {$currentLevelValue} isn't realistic with the timeline and budget you selected. Based on those constraints, Level {$suggestedLevel} is a more achievable target for this cycle — or choose a longer timeline / larger budget to aim higher."
            );
        }
    });

    if ($validator->fails()) {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Please adjust your roadmap inputs below.',
                'errors' => $validator->errors(),
            ], 422);
        }

        return back()
            ->withInput()
            ->withErrors($validator)
            ->with('roadmap_error', 'Please adjust your roadmap inputs below.');
    }

    $validated = $validator->validated();

    $targetLevel = $maturityLevels->firstWhere('level', (int) $validated['target_level']);
    $targetLevelLabel = $targetLevel
        ? 'Level ' . $targetLevel->level . ' — ' . ($targetLevel->label ?? $targetLevel->name)
        : 'Level ' . $validated['target_level'];

    /*
     * Retrieve all responses with the question,
     * dimension and selected answer.
     */
    $responses = \App\Models\Response::with([
        'answerOption',
        'question.dimension',
    ])
        ->where('assessment_id', $assessment->id)
        ->get();

    $dimensionScores = $this->computeDimensionScores($responses)
        ->map(fn ($score) => round($score, 1));

    $dimensionMaturityLevels = $dimensionScores->map(
        function ($score) {
            return MaturityLevel::where('min_score', '<=', $score)
                ->where('max_score', '>=', $score)
                ->first();
        }
    )->map(function ($level) {
        return $level ? ($level->label ?? $level->name) : null;
    });

    /*
     * Build structured assessment + questionnaire data.
     *
     * As with the executive summary, the AI receives calculated YARA
     * results and the client's stated preferences — it does not
     * calculate scores or invent facts about the organization.
     */
    $roadmapContext = [
        'organization' => $assessment->company?->name,
        'industry' => $assessment->company?->industry,
        'country' => $assessment->company?->country,

        'organizational_readiness_score' =>
            round((float) $assessment->company_score, 1),

        'yara_composite_score' =>
            $assessment->combined_score !== null
                ? round((float) $assessment->combined_score, 1)
                : null,

        'current_maturity' => [
            'level' => $currentLevelValue,
            'label' => $currentMaturity
                ? ($currentMaturity->label ?? $currentMaturity->name)
                : null,
        ],

        'dimension_scores' => $dimensionScores->toArray(),
        'dimension_maturity_labels' => $dimensionMaturityLevels->toArray(),

        'client_preferences' => [
            'target_maturity_level' => $targetLevelLabel,
            'budget_range' => $validated['budget'],
            'timeline_horizon' => $validated['timeline'],
            'requested_focus_areas' => $validated['focus_areas'] ?? [],
        ],
    ];

$prompt = <<<PROMPT
You are the transformation roadmap engine of YARA, an organizational AI readiness assessment platform.

Your task is to produce a prioritized, actionable transformation roadmap that helps the organization progress from its current AI maturity level toward its stated target level, while respecting its stated budget, timeline, and requested focus areas.

The roadmap must resemble a realistic professional AI transformation plan: specific enough to guide executive decision-making and implementation planning, but never based on invented facts about the organization.

==================================================
1. EVIDENCE VS PROFESSIONAL KNOWLEDGE
==================================================

Use the supplied YARA assessment data as the ONLY source of evidence about the organization's CURRENT STATE.

You MAY use established professional knowledge of:
- AI governance
- AI strategy
- data management and data governance
- AI security and resilience
- responsible AI and ethics
- workforce readiness and adoption
- change management
- operating models
- AI risk management
- organizational AI transformation

to DESIGN appropriate recommendations.

However, general professional knowledge must NEVER be presented as evidence about the organization.

Never claim that a particular technology, policy, team, process, control, role, dataset, committee, platform, framework, or initiative currently exists or is absent unless the supplied assessment data explicitly establishes this.

GOOD:
"Establish a data catalog and data ownership model to strengthen Data Readiness."

BAD:
"The organization currently lacks a data catalog."

GOOD:
"Introduce clearly defined accountability for Responsible AI."

BAD:
"The organization has no Responsible AI owner."

Recommendations may introduce specific practices, processes, controls, capabilities, governance mechanisms, or technologies when they are reasonable responses to the assessed readiness gap.

==================================================
2. INTERPRETING THE ASSESSMENT
==================================================

Treat all supplied YARA scores and maturity levels as authoritative.

Do NOT recalculate:
- dimension scores
- maturity levels
- overall scores
- country scores
- combined scores

Every roadmap action must address a dimension represented in dimension_scores.

Interpret the assessment as a maturity PROFILE, not simply a ranking.

Consider:
- weakest dimensions
- distance from the target maturity
- requested focus areas
- dependencies between capabilities
- whether a capability is foundational for another capability
- existing strengths that should be preserved rather than unnecessarily rebuilt
- the organization's budget and timeline

Do not automatically create one action for every weak dimension.

Do not automatically create one action for every requested focus area.

The roadmap should select the smallest set of initiatives that provides the strongest realistic path toward the target maturity.

==================================================
3. PRIORITIZATION
==================================================

Prioritize initiatives using the following logic:

1. Material readiness gaps that could block progression toward the target.
2. Foundational capabilities required by other improvements.
3. Requested focus areas that are below the target maturity.
4. Other dimensions where improvement materially supports the transformation.
5. Dimensions already at or above the target should normally NOT receive a dedicated action.

A requested focus area does NOT automatically require an action if it is already sufficiently mature.

A very weak non-focus dimension must not be ignored simply because it was not selected by the client.

When two dimensions have similar scores, consider dependencies and transformation value rather than arbitrarily ranking one above the other.

Sequence initiatives logically.

For example, foundational data, governance, risk, or organizational capabilities may need to begin before capabilities that depend upon them.

However, actions MAY overlap where this is realistic.

Avoid multiple actions that substantially address the same underlying problem.

==================================================
4. ACTION DESIGN
==================================================

Produce between 3 and 6 actions.

Prefer 3-4 strong initiatives when budget or timeline is constrained.

Do NOT generate extra actions merely to reach a higher number.

Each action must:
- address a meaningful assessed gap or transformation need;
- represent a distinct initiative;
- be concrete and implementation-oriented;
- be achievable at an appropriate scope within the client's constraints;
- clearly contribute toward the target maturity;
- avoid unsupported assumptions about the organization.

IMPORTANT:

Match the SCOPE of the recommendation to the available budget and timeline.

For constrained budgets or short timelines, recommend scoped foundations, pilots, initial operating models, minimum viable capabilities, priority controls, or phased implementation rather than unrealistic enterprise-wide transformation.

For example, prefer:

"Establish an initial data catalog for priority AI-relevant data domains"

over:

"Deploy a complete enterprise-wide data management platform"

when the budget or timeline cannot reasonably support the larger initiative.

Do not use words such as:
- enterprise-wide
- comprehensive
- full-scale
- organization-wide
- complete transformation

unless the supplied budget and timeline reasonably support that scope.

Do not recommend unnecessary work for dimensions already sufficiently mature relative to the target unless that work is clearly required as a dependency.

==================================================
5. BUSINESS RATIONALE
==================================================

For every action, explain WHY it was selected.

The rationale should connect the recommendation to one or more of:

- the relevant YARA dimension score;
- its maturity level;
- the target maturity;
- its relative weakness;
- a requested focus area;
- a dependency on another capability;
- its role in enabling progression toward the target.

Do not invent internal problems to justify recommendations.

Do not simply repeat the description.

Do not repeatedly restate the same score and maturity information when it adds no value.

The rationale should explain the transformation significance of the initiative.

==================================================
6. BUDGET
==================================================

The budget_range represents the TOTAL budget available for the COMPLETE roadmap, NOT the budget available for each individual action.

This rule is mandatory.

Individual action investment estimates must be portions of the total roadmap budget.

The combined investment of ALL proposed actions must remain compatible with budget_range.

Before producing the response, internally evaluate the investment estimates of all actions together.

If the combined roadmap would exceed the client's maximum budget:
- reduce the scope of individual initiatives;
- use phased or foundational implementations;
- lower individual investment estimates where professionally reasonable;
- or remove a lower-priority action.

NEVER produce a roadmap whose proposed actions clearly require more than the client's stated maximum budget.

Do not allocate the full client budget independently to each action.

Investment estimates must be realistic relative to the scope being proposed.

Avoid false precision.

Use reasonable ranges such as:
"$5,000 - $10,000"
"$10,000 - $15,000"

when appropriate.

If the supplied information does not justify a monetary estimate, a short qualitative estimate such as "Low investment" or "Moderate investment" may be used.

==================================================
7. TIMELINE
==================================================

timeline_horizon represents the overall implementation horizon for the COMPLETE roadmap.

All initiatives must collectively fit within this horizon.

Individual initiatives may:
- run sequentially;
- overlap;
- run in parallel where appropriate.

Do not interpret timeline_horizon as the amount of time available separately for every action.

Use implementation windows that communicate sequencing when useful.

For example:

"Months 1-2"
"Months 2-4"
"Months 3-6"

is preferable to giving every action an unrelated duration when sequencing matters.

The final roadmap must form a coherent implementation path within the client's overall horizon.

==================================================
8. BUSINESS IMPACT AND EFFORT
==================================================

business_impact represents the expected strategic importance of addressing the assessed gap.

It must be exactly one of:
Low
Medium
High
Critical

Do not label every initiative Critical.

Use Critical only when the assessed gap represents a major barrier to progression toward the target or a particularly important transformation dependency.

effort represents the relative implementation effort for the proposed scoped initiative.

It must be exactly one of:
Low
Medium
High

Effort should reflect the actual scope proposed, not the theoretical difficulty of transforming the entire dimension.

==================================================
9. STANDARDS AND FRAMEWORKS
==================================================

standard_reference may contain a recognized professional standard or framework when it is clearly and directly relevant to the recommended action.

Examples of potentially relevant references include established AI governance, AI risk management, information security, data governance, or responsible AI frameworks.

However:

- Do NOT add a standard merely to make the roadmap appear more professional.
- Do NOT invent standard names or numbers.
- Do NOT claim that the organization is certified or compliant.
- Do NOT imply that adopting the recommendation automatically creates compliance.
- If there is no clearly relevant reference, return an empty string.

==================================================
10. EXECUTIVE SUMMARY
==================================================

The summary must explain the overall transformation logic, not simply list the actions.

It should briefly identify:
- the major readiness gaps being addressed;
- the overall sequencing or strategic approach;
- how the plan supports movement toward the target;
- how the plan respects the client's major constraints.

Do not promise that completing the roadmap will automatically cause the organization's maturity score or level to increase.

Use language such as:
"supports progression toward"
"builds the capabilities needed for"
"addresses key gaps associated with"

rather than guaranteeing a future maturity result.

==================================================
11. OUTPUT REQUIREMENTS
==================================================

Each action's "dimension" MUST exactly match one of the dimension names supplied in dimension_scores.

Never modify, abbreviate, translate, normalize, or invent dimension names.

priority_rank must:
- start at 1;
- be sequential;
- reflect the recommended implementation priority.

"title" should be concise, professional, and specific.

"description" should explain WHAT should be implemented.

"business_rationale" should explain WHY it matters based on the assessment.

"business_impact" must be exactly:
Low | Medium | High | Critical

"effort" must be exactly:
Low | Medium | High

"timeline" must be a short human-readable implementation window consistent with timeline_horizon.

"investment" must be a short human-readable estimate consistent with budget_range AND with the combined roadmap budget.

"standard_reference" must contain a recognized relevant standard/framework or an empty string.

Do not use Markdown.

Return ONLY valid JSON.

Do not include:
- comments;
- explanations outside the JSON;
- code fences;
- introductory text;
- concluding text.

==================================================
12. REQUIRED JSON STRUCTURE
==================================================

Return EXACTLY this structure:

{
    "summary": "A concise 2-3 sentence executive explanation of the transformation strategy, major priorities, sequencing, and how the roadmap supports progression toward the target within the client's constraints.",

    "actions": [
        {
            "priority_rank": 1,
            "title": "Specific, concise initiative title",
            "dimension": "Exact dimension name from dimension_scores",
            "description": "Describe the concrete capability, process, practice, control, or scoped initiative the organization should establish or improve.",
            "business_rationale": "Explain why this initiative is prioritized based on the assessment evidence and how it supports progression toward the target maturity level.",
            "business_impact": "Low | Medium | High | Critical",
            "effort": "Low | Medium | High",
            "timeline": "Short implementation window",
            "investment": "Short estimate or range",
            "standard_reference": "Relevant recognized standard/framework, or empty string"
        }
    ]
}

==================================================
13. FINAL INTERNAL VALIDATION
==================================================

Before returning the JSON, internally verify ALL of the following:

1. Every action corresponds to an exact dimension in dimension_scores.

2. Every action is justified by the supplied assessment or requested focus areas.

3. No statement presents invented information as fact about the organization.

4. Actions are distinct and do not substantially duplicate one another.

5. Weak and foundational capabilities receive appropriate priority.

6. Requested focus areas meaningfully influence the roadmap without overriding more critical readiness gaps.

7. Dimensions already at or above the target have not received unnecessary initiatives.

8. Initiative scope is realistic for the client's budget and timeline.

9. The TOTAL estimated investment across all actions is compatible with the client's TOTAL budget_range.

10. The COMPLETE sequence of actions fits within timeline_horizon.

11. The roadmap does not guarantee that implementation will automatically increase the organization's maturity level.

12. Standards/frameworks are included only when clearly relevant and confidently recognized.

13. All enum values exactly match the permitted values.

14. priority_rank is sequential starting at 1.

15. The output is valid JSON and contains no text outside the required JSON object.

If any check fails, revise the roadmap internally before returning the final JSON.

ASSESSMENT AND PREFERENCE DATA:
PROMPT;

$prompt .= "\n" . json_encode(
    $roadmapContext,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
);

    try {
        $roadmapResponse = $ai->generate($prompt);
    } catch (Throwable $e) {
        Log::error('AI roadmap generation failed', [
            'assessment_id' => $assessment->id,
            'message' => $e->getMessage(),
        ]);

        $message = 'The AI roadmap service is temporarily unavailable. Please try again in a moment.';

        /*
         * In local/debug environments, surface the real exception
         * message so connectivity/config problems (timeouts, SSL/cURL
         * issues, bad API keys, etc.) are visible right on the page
         * instead of only in storage/logs/laravel.log.
         */
        if (config('app.debug')) {
            $message .= ' Debug: ' . $e->getMessage();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 503);
        }

        return back()->withInput()->with('roadmap_error', $message);
    }

    $roadmap = json_decode($roadmapResponse, true);

    if (
        json_last_error() !== JSON_ERROR_NONE ||
        !is_array($roadmap) ||
        empty($roadmap['actions']) ||
        !is_array($roadmap['actions'])
    ) {
        Log::warning('AI roadmap returned unexpected format', [
            'assessment_id' => $assessment->id,
            'raw' => $roadmapResponse ?? null,
        ]);

        $message = 'The AI roadmap could not be generated in the expected format. Please try again.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return back()->withInput()->with(
            'roadmap_error',
            $message
        );
    }

    $roadmapInputs = [
        'target_level' => (int) $validated['target_level'],
        'target_level_label' => $targetLevelLabel,
        'budget' => $validated['budget'],
        'timeline' => $validated['timeline'],
        'focus_areas' => $validated['focus_areas'] ?? [],
    ];

    $assessment->update([
        'ai_roadmap' => json_encode($roadmap, JSON_UNESCAPED_UNICODE),
        'roadmap_inputs' => json_encode($roadmapInputs, JSON_UNESCAPED_UNICODE),
        'roadmap_generated_at' => now(),
    ]);

    if ($request->wantsJson()) {
    return response()->json([
        'success' => true,
        'message' => 'Your personalized roadmap has been generated.',
        'html' => $this->renderRoadmapResults($roadmap, $roadmapInputs),
        'action_count' => count($roadmap['actions']),
    ]);
}

return redirect()
    ->route('assessment.results', $assessment->id)
    ->with(
        'success',
        'Your personalized roadmap has been generated.'
    );
}

    function results(Assessment $assessment)
    {
        /*
 * Security:
 * only the client who owns this assessment
 * may view its results.
 */
$this->authorizeClientAssessment($assessment);

       $assessment->load([
    'company',
    'transformationRoadmap.initiatives',
]);

        $responses = Response::with([
            'answerOption',
            'question.dimension',
        ])
            ->where('assessment_id', $assessment->id)
            ->get();

        [$averageScore, $totalScore] = $this->computeWeightedScore($responses);

        /*
         * Maturity level is based ONLY on organizational readiness,
         * not on the country-adjusted composite score.
         */
        $maturityLevel = MaturityLevel::where(
            'min_score',
            '<=',
            $averageScore
        )
            ->where('max_score', '>=', $averageScore)
            ->first();

        $dimensionScores = $this->computeDimensionScores($responses);

        $dimensionMaturityLevels = $dimensionScores->map(
            function ($score) {
                return MaturityLevel::where('min_score', '<=', $score)
                    ->where('max_score', '>=', $score)
                    ->first();
            }
        );

        $topDimensions = $dimensionScores
            ->sortDesc()
            ->take(3);

        $priorityDimensions = $dimensionScores
            ->sort()
            ->take(3);

        $countryGap = $assessment->country_ai_score !== null
            ? $averageScore - $assessment->country_ai_score
            : null;

        $assessedDimensionCount = $dimensionScores->count();

        $totalDimensionCount = Dimension::count();

        $answeredQuestionCount = $responses->count();

        $totalActiveQuestionCount =
            \App\Models\Question::where('is_active', true)->count();

        $highestDimensionName =
            $dimensionScores->sortDesc()->keys()->first();

        $highestDimensionScore =
            $dimensionScores->sortDesc()->first();

        $lowestDimensionName =
            $dimensionScores->sort()->keys()->first();

        $lowestDimensionScore =
            $dimensionScores->sort()->first();

        $recommendations = $assessment->recommendations()
            ->with('dimension')
            ->orderBy('priority_rank')
            ->get();

        /*
         * Full ordered maturity level list — used by the results view to
         * populate the roadmap planning form's "target level" dropdown
         * with only the levels above the organization's current one.
         */
        $allMaturityLevels = MaturityLevel::orderBy('level')->get();
/*
 * SWOT analysis
 *
 * Built from deterministic assessment evidence.
 * No AI-generated or unsupported external claims are used here.
 */

        return view('assessments.results', compact(
            'assessment',
            'responses',
            'totalScore',
            'averageScore',
            'maturityLevel',
            'dimensionScores',
            'dimensionMaturityLevels',
            'topDimensions',
            'priorityDimensions',
            'countryGap',
            'assessedDimensionCount',
            'totalDimensionCount',
            'answeredQuestionCount',
            'totalActiveQuestionCount',
            'highestDimensionName',
            'highestDimensionScore',
            'lowestDimensionName',
            'lowestDimensionScore',
            'recommendations',
            'allMaturityLevels'
            
        ));
    }
public function countryInsights(Assessment $assessment)
{
    /*
 * Security:
 * only the client who owns this assessment
 * may access its country insights.
 */
$this->authorizeClientAssessment($assessment);

    $assessment->load('company');

    $countryName = $assessment->company?->country;

    abort_if(!$countryName, 404);


    // -------------------------------------------------
    // ALL HISTORICAL DATA FOR THIS COUNTRY
    // -------------------------------------------------

    $countryHistory = CountryAIReadinessScore::whereRaw(
        'LOWER(TRIM(country)) = ?',
        [strtolower(trim($countryName))]
    )
        ->orderBy('year')
        ->get();


    abort_if($countryHistory->isEmpty(), 404);


    // -------------------------------------------------
    // AVAILABLE Ypublic EARS
    // -------------------------------------------------

    $availableYears = $countryHistory
        ->pluck('year')
        ->filter()
        ->unique()
        ->sortDesc()
        ->values();


    // -------------------------------------------------
    // SELECTED YEAR
    // -------------------------------------------------

    /*
     * If ?year=2024 exists in the URL, use 2024.
     * Otherwise use the latest available year.
     */

    $requestedYear = request()->query('year');

    if (
        $requestedYear &&
        $availableYears->contains(
            fn ($year) => (string) $year === (string) $requestedYear
        )
    ) {
        $selectedYear = $requestedYear;
    } else {
        $selectedYear = $availableYears->first();
    }


    // -------------------------------------------------
    // COUNTRY RECORD FOR SELECTED YEAR
    // -------------------------------------------------

    $country = $countryHistory
        ->first(function ($item) use ($selectedYear) {
            return (string) $item->year === (string) $selectedYear;
        });


    abort_if(!$country, 404);


    // -------------------------------------------------
    // ORGANIZATION + COUNTRY SCORES
    // -------------------------------------------------

    $organizationScore = $assessment->company_score !== null
        ? (float) $assessment->company_score
        : null;

    $countryScore = $country->score !== null
        ? (float) $country->score
        : null;


    $benchmarkGap = (
        $organizationScore !== null &&
        $countryScore !== null
    )
        ? round($organizationScore - $countryScore, 1)
        : null;


    // -------------------------------------------------
    // ORGANIZATION % ABOVE / BELOW BENCHMARK
    // -------------------------------------------------

    $benchmarkGapPercent = (
        $benchmarkGap !== null &&
        $countryScore !== null &&
        $countryScore > 0
    )
        ? round(($benchmarkGap / $countryScore) * 100, 1)
        : null;


    // -------------------------------------------------
    // RANK FOR SELECTED YEAR
    // -------------------------------------------------

    $currentRank =
        $country->final_rank ??
        $country->official_rank;


    // -------------------------------------------------
    // TOTAL COUNTRIES IN SELECTED YEAR
    // -------------------------------------------------

    $totalCountriesForYear = CountryAIReadinessScore::where(
        'year',
        $selectedYear
    )
        ->distinct('country')
        ->count('country');


    // -------------------------------------------------
    // GLOBAL POSITION / PERCENTILE
    // -------------------------------------------------

    /*
     * Example:
     * rank 20 of 100
     * means the country is approximately in the top 20%.
     */

    $topPercent = (
        $currentRank !== null &&
        $totalCountriesForYear > 0
    )
        ? round(
            ($currentRank / $totalCountriesForYear) * 100,
            1
        )
        : null;


    // -------------------------------------------------
    // PREVIOUS AVAILABLE YEAR
    // -------------------------------------------------

    $previousCountryRecord = $countryHistory
        ->filter(function ($item) use ($selectedYear) {
            return (int) $item->year < (int) $selectedYear;
        })
        ->sortByDesc('year')
        ->first();


    $previousRank = $previousCountryRecord
        ? (
            $previousCountryRecord->final_rank ??
            $previousCountryRecord->official_rank
        )
        : null;


    // -------------------------------------------------
    // RANK MOVEMENT
    // -------------------------------------------------

    /*
     * Smaller rank number = better.
     *
     * Previous: #70
     * Current:  #60
     *
     * Movement = +10
     */

    $rankMovement = (
        $currentRank !== null &&
        $previousRank !== null
    )
        ? $previousRank - $currentRank
        : null;


    // -------------------------------------------------
    // SCORE MOVEMENT
    // -------------------------------------------------

    $previousScore = (
        $previousCountryRecord &&
        $previousCountryRecord->score !== null
    )
        ? (float) $previousCountryRecord->score
        : null;


    $scoreMovement = (
        $countryScore !== null &&
        $previousScore !== null
    )
        ? round($countryScore - $previousScore, 1)
        : null;


    // -------------------------------------------------
    // HISTORICAL CHART DATA
    // -------------------------------------------------

    $historyChart = $countryHistory
        ->filter(function ($item) {
            return $item->year !== null &&
                   $item->score !== null;
        })
        ->map(function ($item) {

            return [
                'year' => (string) $item->year,

                'score' => round(
                    (float) $item->score,
                    1
                ),

                'rank' =>
                    $item->final_rank ??
                    $item->official_rank,
            ];
        })
        ->values();


    // -------------------------------------------------
    // ALL COUNTRIES FOR COMPARISON
    // -------------------------------------------------

    /*
     * We only need unique country names here.
     * Actual comparison data will be loaded below.
     */

    $countries = CountryAIReadinessScore::select('id', 'country')
    ->orderBy('country')
    ->get()
    ->unique('country')
    ->values();


    // -------------------------------------------------
    // SELECTED COMPARISON COUNTRY
    // -------------------------------------------------

    $comparisonCountryName =
        request()->query('compare');


    $comparisonCountry = null;
    $comparisonHistory = collect();
    $comparisonScore = null;
    $comparisonRank = null;


    if ($comparisonCountryName) {

        $comparisonHistory =
            CountryAIReadinessScore::whereRaw(
                'LOWER(TRIM(country)) = ?',
                [
                    strtolower(
                        trim($comparisonCountryName)
                    )
                ]
            )
                ->orderBy('year')
                ->get();


        /*
         * Find the comparison country's record
         * for the SAME selected year.
         */

        $comparisonCountry =
            $comparisonHistory->first(
                function ($item) use ($selectedYear) {

                    return (string) $item->year
                        === (string) $selectedYear;
                }
            );


        if ($comparisonCountry) {

            $comparisonScore =
                $comparisonCountry->score !== null
                    ? (float) $comparisonCountry->score
                    : null;


            $comparisonRank =
                $comparisonCountry->final_rank ??
                $comparisonCountry->official_rank;
        }
    }


    // -------------------------------------------------
    // COMPARISON HISTORICAL CHART
    // -------------------------------------------------

    $comparisonHistoryChart =
        $comparisonHistory
            ->filter(function ($item) {
                return $item->year !== null &&
                       $item->score !== null;
            })
            ->map(function ($item) {

                return [
                    'year' => (string) $item->year,

                    'score' => round(
                        (float) $item->score,
                        1
                    ),

                    'rank' =>
                        $item->final_rank ??
                        $item->official_rank,
                ];
            })
            ->values();


    // -------------------------------------------------
    // COUNTRY-TO-COUNTRY SCORE GAP
    // -------------------------------------------------

    $countryComparisonGap = (
        $countryScore !== null &&
        $comparisonScore !== null
    )
        ? round(
            $countryScore - $comparisonScore,
            1
        )
        : null;


    // -------------------------------------------------
    // SEND EVERYTHING TO VIEW
    // -------------------------------------------------

    return view(
        'assessments.country-insights',
        compact(
            'assessment',

            'country',
            'countryHistory',

            'availableYears',
            'selectedYear',

            'organizationScore',
            'countryScore',

            'benchmarkGap',
            'benchmarkGapPercent',

            'currentRank',
            'totalCountriesForYear',
            'topPercent',

            'previousCountryRecord',
            'previousRank',

            'rankMovement',
            'previousScore',
            'scoreMovement',

            'historyChart',

            'countries',

            'comparisonCountryName',
            'comparisonCountry',
            'comparisonHistory',
            'comparisonScore',
            'comparisonRank',
            'comparisonHistoryChart',
            'countryComparisonGap'
        )
    );
}

    /**
     * Render the "results" portion of the AI executive summary (headline,
     * priority risk / strength / readiness pattern cards, country context)
     * as a standalone HTML fragment so it can be swapped into the results
     * page via AJAX without a full page reload.
     */
    private function renderAiSummaryResults(Assessment $assessment, array $analysis): string
    {
        $responses = \App\Models\Response::with([
            'answerOption',
            'question.dimension',
        ])
            ->where('assessment_id', $assessment->id)
            ->get();

        $dimensionScores = $this->computeDimensionScores($responses);

        $assessedDimensionCount = $dimensionScores->count();
        $totalDimensionCount = Dimension::count();

        $roadmapData = null;

        if (!empty($assessment->ai_roadmap)) {
            $decodedRoadmap = json_decode($assessment->ai_roadmap, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedRoadmap)) {
                $roadmapData = $decodedRoadmap;
            }
        }

        $roadmapActions = collect($roadmapData['actions'] ?? []);

        $relatedActionCount = 0;

        if (!empty($analysis['priority_risk']['dimension']) && $roadmapActions->count()) {
            $relatedActionCount = $roadmapActions
                ->filter(function ($action) use ($analysis) {
                    return !empty($action['dimension'])
                        && strcasecmp(
                            trim($action['dimension']),
                            trim($analysis['priority_risk']['dimension'])
                        ) === 0;
                })
                ->count();
        }

       

if ($assessment->ai_summary_generated_at && $assessment->updated_at) {
    $summaryIsStale = $assessment->updated_at->gt($assessment->ai_summary_generated_at);
}

$summaryIsStale = false;

return view('assessments.partials.ai-summary-results', [
    'aiAnalysis' => $analysis,
    'assessedDimensionCount' => $assessedDimensionCount,
    'totalDimensionCount' => $totalDimensionCount,
    'relatedActionCount' => $relatedActionCount,
    'summaryIsStale' => $summaryIsStale,
])->render();
    }

    /**
     * Render the generated roadmap (inputs recap, strategy summary, and
     * action cards) as a standalone HTML fragment so it can be swapped
     * into the results page via AJAX without a full page reload.
     */
   private function renderRoadmapResults(array $roadmap, array $roadmapInputs): string
{
    $roadmapActions = collect($roadmap['actions'] ?? []);

    return view('assessments.partials.roadmap-results', [
        'roadmapData' => $roadmap,
        'roadmapActions' => $roadmapActions,
        'roadmapActionCount' => $roadmapActions->count(),
        'roadmapInputs' => $roadmapInputs,
    ])->render();
}

    /**
     * Normalize a set of responses into a single weighted 0–100 score.
     *
     * Answer scores are on a 1–4 scale and are converted to 0–100 before
     * being weighted by each question's configured weight:
     *   1 -> 0, 2 -> 33.33, 3 -> 66.67, 4 -> 100
     *
     * Returns [averageScore, totalWeightedScore] — the second value is
     * kept around only because the results() view historically expected
     * a raw "totalScore" alongside the average, for backward compatibility.
     *
     * @param  Collection  $responses
     * @return array{0: float, 1: float}
     */
    private function computeWeightedScore(Collection $responses): array
    {
        $totalWeightedScore = 0;
        $totalQuestionWeight = 0;

        foreach ($responses as $response) {
            $answerScore = $response->answerOption?->score;
            $questionWeight = $response->question?->weight ?? 1;

            if ($answerScore === null) {
                continue;
            }

            $normalizedScore = (($answerScore - 1) / 3) * 100;

            $totalWeightedScore += $normalizedScore * $questionWeight;
            $totalQuestionWeight += $questionWeight;
        }

        $averageScore = $totalQuestionWeight > 0
            ? $totalWeightedScore / $totalQuestionWeight
            : 0;

        return [$averageScore, $totalWeightedScore];
    }

    public function comparison()
{
    $user = auth()->user();

    /*
     * Retrieve completed assessments belonging to
     * the current user's organization.
     *
     * Oldest -> newest makes selecting the latest
     * and immediately previous assessment straightforward.
     */
    $completedAssessments = Assessment::where(
            'company_id',
            $user->company_id
        )
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->whereNotNull('company_score')
        ->orderBy('created_at')
        ->get();

    /*
     * Progress comparison requires at least
     * two completed assessments.
     */
    abort_if(
        $completedAssessments->count() < 2,
        404,
        'At least two completed assessments are required for comparison.'
    );

    /*
 * Default comparison:
 * latest completed assessment vs immediately previous assessment.
 *
 * The user may override either assessment through
 * ?previous=ID&current=ID
 */
$currentAssessment = $completedAssessments->last();

$previousAssessment = $completedAssessments
    ->slice(-2, 1)
    ->first();


if (request()->filled('previous') && request()->filled('current')) {

    $selectedPrevious = $completedAssessments->firstWhere(
        'id',
        (int) request('previous')
    );

    $selectedCurrent = $completedAssessments->firstWhere(
        'id',
        (int) request('current')
    );

    /*
     * Only allow assessments belonging to the collection
     * we already authorized above.
     */
    if (
        $selectedPrevious
        && $selectedCurrent
        && $selectedPrevious->id !== $selectedCurrent->id
    ) {
        $previousAssessment = $selectedPrevious;
        $currentAssessment = $selectedCurrent;
    }
}/*
 * Always compare chronologically:
 * older assessment -> newer assessment.
 */
if (
    $previousAssessment->created_at->gt($currentAssessment->created_at)
) {
    [$previousAssessment, $currentAssessment] = [
        $currentAssessment,
        $previousAssessment,
    ];
}
/*
 * Detect whether the two assessments were completed
 * using different versions of the YARA framework.
 */
$frameworkVersionsDiffer =
    $previousAssessment->framework_version !== null
    && $currentAssessment->framework_version !== null
    && $previousAssessment->framework_version
        !== $currentAssessment->framework_version;

    /*
     * Load the responses for both assessments
     * using the same relationships used by Results.
     */
    $currentResponses = Response::with([
        'answerOption',
        'question.dimension',
    ])
        ->where('assessment_id', $currentAssessment->id)
        ->get();

    $previousResponses = Response::with([
        'answerOption',
        'question.dimension',
    ])
        ->where('assessment_id', $previousAssessment->id)
        ->get();

    /*
     * Reuse YARA's existing scoring engine.
     * We do NOT create separate comparison scoring logic.
     */
    $currentDimensionScores =
        $this->computeDimensionScores($currentResponses);

    $previousDimensionScores =
        $this->computeDimensionScores($previousResponses);

    /*
     * Build one comparison collection.
     *
     * union() allows us to keep dimensions that may exist
     * in only one of the two assessments.
     */
    $dimensionNames = $currentDimensionScores
        ->keys()
        ->merge($previousDimensionScores->keys())
        ->unique()
        ->values();

    $dimensionComparison = $dimensionNames
        ->map(function ($dimensionName) use (
            $currentDimensionScores,
            $previousDimensionScores
        ) {
            $current = $currentDimensionScores->get($dimensionName);
            $previous = $previousDimensionScores->get($dimensionName);

            return [
                'dimension' => $dimensionName,

                'previous_score' => $previous !== null
                    ? round($previous, 1)
                    : null,

                'current_score' => $current !== null
                    ? round($current, 1)
                    : null,

                'change' => (
                    $current !== null &&
                    $previous !== null
                )
                    ? round($current - $previous, 1)
                    : null,
            ];
        });

    /*
     * Overall organizational score movement.
     *
     * We deliberately compare company_score,
     * NOT combined_score, because country benchmark
     * changes should not be interpreted as organizational
     * improvement or decline.
     */
    $overallChange = round(
        $currentAssessment->company_score
            - $previousAssessment->company_score,
        1
    );

    return view(
        'assessments.comparison',
        compact(
            'completedAssessments',
            'currentAssessment',
            'previousAssessment',
            'currentDimensionScores',
            'previousDimensionScores',
            'dimensionComparison',
            'overallChange',
                'frameworkVersionsDiffer'

        )
    );
}
public function simulator()
{
    $user = auth()->user();

    /*
     * Use the user's latest completed Full Assessment
     * as the simulator baseline.
     *
     * The simulator itself remains an independent tool:
     * it is not attached to one assessment through the URL.
     */
    $assessment = Assessment::where(
            'company_id',
            $user->company_id
        )
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->whereNotNull('company_score')
        ->latest('created_at')
        ->first();

    abort_if(
        !$assessment,
        404,
        'Complete an AI Readiness Assessment before using the simulator.'
    );

    /*
     * Load the responses from the latest assessment
     * to establish the organization's current baseline.
     */
    $responses = Response::with([
        'answerOption',
        'question.dimension',
    ])
        ->where('assessment_id', $assessment->id)
        ->get();

    /*
     * Current capability scores.
     */
    $dimensionScores = $this->computeDimensionScores($responses);

    /*
     * Determine the scoring weight represented
     * by each capability.
     */
    $dimensionWeights = $responses
        ->filter(function ($response) {
            return $response->question
                && $response->question->dimension
                && $response->answerOption;
        })
        ->groupBy(function ($response) {
            return $response->question->dimension->name;
        })
        ->map(function ($group) {
            return $group->sum(function ($response) {
                return $response->question?->weight ?? 1;
            });
        });

    $totalWeight = $dimensionWeights->sum();

    /*
     * Dataset used by the interactive simulator.
     */
    $dimensions = $dimensionScores
        ->map(function ($score, $dimensionName) use (
            $dimensionWeights,
            $totalWeight
        ) {
            $weight = $dimensionWeights->get($dimensionName, 0);

            return [
                'name' => $dimensionName,
                'current_score' => round($score, 1),

                'weight' => $weight,

                'weight_percentage' => $totalWeight > 0
                    ? ($weight / $totalWeight) * 100
                    : 0,
            ];
        })
        ->values();

    return view(
        'assessments.simulator',
        compact(
            'assessment',
            'dimensions'
        )
    );
}
    /**
     * Compute a weighted 0–100 score per dimension, keyed by dimension name.
     *
     * This exact logic previously existed, duplicated near-verbatim, in both
     * results() and generateAiSummary() — extracted here so the two entry
     * points can never silently drift out of sync with each other.
     *
     * @param  Collection  $responses
     * @return \Illuminate\Support\Collection<string, float>
     */
    private function computeDimensionScores(Collection $responses): Collection
    {
        return $responses
            ->filter(function ($response) {
                return $response->question
                    && $response->question->dimension
                    && $response->answerOption;
            })
            ->groupBy(function ($response) {
                return $response->question->dimension->name;
            })
            ->map(function ($group) {
                $dimensionWeightedScore = 0;
                $dimensionQuestionWeight = 0;

                foreach ($group as $response) {
                    $answerScore = $response->answerOption?->score;
                    $questionWeight = $response->question?->weight ?? 1;

                    if ($answerScore === null) {
                        continue;
                    }

                    $normalizedScore = (($answerScore - 1) / 3) * 100;

                    $dimensionWeightedScore +=
                        $normalizedScore * $questionWeight;

                    $dimensionQuestionWeight += $questionWeight;
                }

                return $dimensionQuestionWeight > 0
                    ? $dimensionWeightedScore / $dimensionQuestionWeight
                    : 0;
            });
    }

    /**
     * How many maturity levels a roadmap can realistically target in one
     * cycle, given a stated budget range and timeline horizon.
     *
     * Used to reject unrealistic combinations — e.g. "Level 1 → Level 4
     * in 3 months on Under $10,000" — before they ever reach the AI,
     * instead of letting the model either invent a fictional shortcut or
     * silently ignore the stated constraints.
     *
     * The more constrained of the two inputs (smaller budget or shorter
     * timeline) governs how far the organization can realistically move
     * — a bigger budget can't compensate for too little time, and a
     * longer timeline can't compensate for too little budget.
     */
    public function maxFeasibleLevelJump(?string $budget, ?string $timeline): int
    {
        $budgetTiers = [
            'Under $10,000' => 0,
            '$10,000 - $50,000' => 1,
            '$50,000 - $200,000' => 2,
            '$200,000+' => 3,
            'Prefer not to say' => 1,
        ];

        $timelineTiers = [
            '3 months' => 0,
            '6 months' => 1,
            '12 months' => 2,
            '18-24 months' => 3,
        ];

        $budgetTier = $budgetTiers[$budget] ?? 1;
        $timelineTier = $timelineTiers[$timeline] ?? 1;

        return min($budgetTier, $timelineTier) + 1;
    }

public function saveRoadmapPreferences(
    Request $request,
    Assessment $assessment
) {
    /*
     * Security:
     * only the client who owns this assessment
     * may save its Transformation brief.
     */
    $this->authorizeClientAssessment($assessment);

    /*
     * Only a completed readiness assessment
     * can be used for a Transformation Roadmap.
     */
    abort_unless(
        $assessment->status === 'completed',
        404
    );

    /*
     * Once payment has been completed, the brief
     * becomes part of the submitted engagement
     * and can no longer be edited by the client.
     */
    abort_if(
        $assessment->payment_status === 'paid',
        409,
        'This Transformation Roadmap has already been purchased.'
    );

    /*
     * Validate the Transformation brief.
     */
    $validated = $request->validate([
        'target_level' => [
            'required',
            'integer',
            'min:1',
            'max:5',
        ],

        'budget' => [
            'required',
            'string',
            \Illuminate\Validation\Rule::in([
                'Under $10,000',
                '$10,000 - $50,000',
                '$50,000 - $200,000',
                '$200,000+',
                'Prefer not to say',
            ]),
        ],

        'timeline' => [
            'required',
            'string',
            'in:3 months,6 months,12 months,18-24 months',
        ],
    ]);

    /*
     * Save the client's Transformation brief.
     *
     * Saving this information does NOT convert
     * the assessment into a paid Transformation
     * engagement.
     */
    $assessment->roadmapPreference()->updateOrCreate(
        [
            'assessment_id' => $assessment->id,
        ],
        [
            'target_maturity' => 'Level ' . $validated['target_level'],

            'timeframe' => $validated['timeline'],

            'budget_level' => $validated['budget'],

            'strategic_priorities' => [],

            'constraints' => null,
        ]
    );

    /*
     * Continue to the YARA payment summary.
     *
     * The assessment remains a normal completed
     * readiness assessment until Stripe confirms
     * successful payment.
     */
    return redirect()->route(
        'transformation.payment.page',
        $assessment
    );

}
public function transformationSubmitted(Assessment $assessment)
{
   /*
 * Security:
 * only the client who owns this assessment
 * may view its Transformation submission status.
 */
$this->authorizeClientAssessment($assessment);

    /*
     * This page only exists for the
     * Transformation Roadmap service.
     */
    abort_unless(
        $assessment->engagement_type === 'transformation',
        404
    );

    /*
     * The client should only reach this page
     * after submitting the planning brief.
     */
    abort_unless(
        in_array($assessment->transformation_status, [
            'submitted',
            'in_review',
            'roadmap_ready',
        ]),
        404
    );

    return view(
        'assessments.transformation-submitted',
        compact('assessment')
    );
}
    
    public function downloadTransformationRoadmapPdf(Assessment $assessment)
{
    /*
     * Security:
     * only the client who owns this assessment
     * may download its Transformation roadmap.
     */
    $this->authorizeClientAssessment($assessment);

    /*
     * Only Transformation engagements can
     * have a Transformation roadmap PDF.
     */
    abort_unless(
        $assessment->engagement_type === 'transformation',
        404
    );

    /*
     * The PDF is a FINAL expert-reviewed deliverable.
     * It must not be downloadable while the roadmap
     * is still being prepared.
     */
    abort_unless(
        $assessment->transformation_status === 'roadmap_ready',
        404
    );

    /*
     * Load everything needed by the PDF.
     */
  $assessment->load([
    'company',
    'roadmapPreference',
    'transformationRoadmap.initiatives',
]);

    $roadmap = $assessment->transformationRoadmap;

    abort_unless($roadmap, 404);
    /*
 * Current maturity is based only on the organization's
 * readiness score, using the same logic as the Results page.
 */
$currentMaturity = MaturityLevel::where(
    'min_score',
    '<=',
    $assessment->company_score
)
    ->where(
        'max_score',
        '>=',
        $assessment->company_score
    )
    ->first();

/*
 * Decode the consultant-generated AI Assessment Brief.
 */
$aiBrief = null;

if ($assessment->ai_executive_summary) {
    $decodedBrief = json_decode(
        $assessment->ai_executive_summary,
        true
    );

    if (is_array($decodedBrief)) {
        $aiBrief = $decodedBrief;
    }
}

   $pdf = Pdf::loadView(
    'assessments.transformation-roadmap-pdf',
    compact(
        'assessment',
        'roadmap',
        'currentMaturity',
        'aiBrief'
    )
);

    /*
     * Produce a clean filename such as:
     * YARA-Transformation-Roadmap-Acme.pdf
     */
    $companyName = $assessment->company?->name ?? 'Organization';

    $safeCompanyName = preg_replace(
        '/[^A-Za-z0-9\-]+/',
        '-',
        $companyName
    );

    $safeCompanyName = trim($safeCompanyName, '-');

    return $pdf->download(
        'YARA-Transformation-Roadmap-'
        . $safeCompanyName
        . '.pdf'
    );
}


    private function authorizeClientAssessment(Assessment $assessment): void
{
    $user = auth()->user();

    abort_unless(
        $user &&
        $user->role === 'client' &&
        (int) $assessment->user_id === (int) $user->id,
        403,
        'This assessment does not belong to you.'
    );
}
}