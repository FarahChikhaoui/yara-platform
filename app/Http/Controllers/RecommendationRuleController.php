<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\Question;
use App\Models\RecommendationRule;
use Illuminate\Http\Request;

class RecommendationRuleController extends Controller
{
    public function index()
    {
        $rules = RecommendationRule::with(['dimension', 'question'])
            ->orderBy('dependency_order')
            ->orderByDesc('priority_weight')
            ->get();

        return view('admin.recommendation-rules.index', compact('rules'));
    }

    public function create()
    {
        $dimensions = Dimension::orderBy('code')->get();

        $questions = Question::with('dimension')
            ->orderBy('dimension_id')
            ->orderBy('order')
            ->get();

        return view(
            'admin.recommendation-rules.create',
            compact('dimensions', 'questions')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validateRule($request);

        $validated['is_active'] = $request->has('is_active');

        RecommendationRule::create($validated);

        return redirect()
            ->route('recommendation-rules.index')
            ->with('success', 'Recommendation rule created successfully.');
    }

    public function edit(RecommendationRule $recommendationRule)
    {
        $dimensions = Dimension::orderBy('code')->get();

        $questions = Question::with('dimension')
            ->orderBy('dimension_id')
            ->orderBy('order')
            ->get();

        return view(
            'admin.recommendation-rules.edit',
            compact('recommendationRule', 'dimensions', 'questions')
        );
    }

    public function update(
        Request $request,
        RecommendationRule $recommendationRule
    ) {
        $validated = $this->validateRule($request);

        $validated['is_active'] = $request->has('is_active');

        $recommendationRule->update($validated);

        return redirect()
            ->route('recommendation-rules.index')
            ->with('success', 'Recommendation rule updated successfully.');
    }

    public function destroy(RecommendationRule $recommendationRule)
    {
        $recommendationRule->delete();

        return redirect()
            ->route('recommendation-rules.index')
            ->with('success', 'Recommendation rule deleted successfully.');
    }

    private function validateRule(Request $request): array
    {
        return $request->validate([
            'dimension_id' => [
                'required',
                'exists:dimensions,id',
            ],

            'question_id' => [
                'nullable',
                'exists:questions,id',
            ],

            'max_answer_score' => [
                'required',
                'integer',
                'between:1,4',
            ],

            'action_title' => [
                'required',
                'string',
                'max:255',
            ],

            'action_description' => [
                'required',
                'string',
            ],

            'business_rationale' => [
                'nullable',
                'string',
            ],
'category' => [
    'nullable',
    'string',
    'max:100',
],

'expected_outcomes' => [
    'nullable',
    'string',
],
            'business_impact' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'effort' => [
                'required',
                'in:Low,Medium,High',
            ],

            'timeline_min_months' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'timeline_max_months' => [
                'nullable',
                'integer',
                'gte:timeline_min_months',
            ],

            'investment_min' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'investment_max' => [
                'nullable',
                'numeric',
                'gte:investment_min',
            ],

            'currency' => [
                'required',
                'string',
                'size:3',
            ],

            'priority_weight' => [
                'required',
                'integer',
                'min:1',
            ],

            'dependency_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'standard_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
    }
}