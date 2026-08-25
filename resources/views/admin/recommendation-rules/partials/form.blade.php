@if ($errors->any())
    <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
        <p class="font-semibold text-red-700">
            Please correct the following errors:
        </p>

        <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $selectedDimensionId = old(
        'dimension_id',
        $recommendationRule?->dimension_id
    );

    $selectedQuestionId = old(
        'question_id',
        $recommendationRule?->question_id
    );
@endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Dimension
        </label>

        <select
            name="dimension_id"
            id="dimension_id"
            required
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
            <option value="">Select a dimension</option>

            @foreach($dimensions as $dimension)
                <option
                    value="{{ $dimension->id }}"
                    {{ (string) $selectedDimensionId === (string) $dimension->id ? 'selected' : '' }}
                >
                    {{ $dimension->code }} — {{ $dimension->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Trigger Question
        </label>

        <select
            name="question_id"
            id="question_id"
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
            <option value="">Dimension-level rule</option>

            @foreach($questions as $question)
                <option
                    value="{{ $question->id }}"
                    data-dimension-id="{{ $question->dimension_id }}"
                    {{ (string) $selectedQuestionId === (string) $question->id ? 'selected' : '' }}
                >
                    {{ $question->dimension->code ?? 'N/A' }}
                    — {{ $question->question_text }}
                </option>
            @endforeach
        </select>

        <p class="mt-2 text-xs text-slate-500">
            Select a specific question for answer-level personalization, or leave empty for a general dimension rule.
        </p>
    </div>

</div>

<div class="grid grid-cols-1 gap-6 md:grid-cols-3">

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Trigger when answer score is at most
        </label>

        <select
            name="max_answer_score"
            required
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
            @foreach([1, 2, 3, 4] as $score)
                <option
                    value="{{ $score }}"
                    {{ (string) old('max_answer_score', $recommendationRule?->max_answer_score ?? 2) === (string) $score ? 'selected' : '' }}
                >
                    {{ $score }}
                </option>
            @endforeach
        </select>

        <p class="mt-2 text-xs text-slate-500">
            Example: value 2 means the rule is triggered by answer scores 1 or 2.
        </p>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Business Impact
        </label>

        <select
            name="business_impact"
            required
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
            @foreach(['Low', 'Medium', 'High', 'Critical'] as $impact)
                <option
                    value="{{ $impact }}"
                    {{ old('business_impact', $recommendationRule?->business_impact ?? 'Medium') === $impact ? 'selected' : '' }}
                >
                    {{ $impact }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Effort
        </label>

        <select
            name="effort"
            required
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
            @foreach(['Low', 'Medium', 'High'] as $effort)
                <option
                    value="{{ $effort }}"
                    {{ old('effort', $recommendationRule?->effort ?? 'Medium') === $effort ? 'selected' : '' }}
                >
                    {{ $effort }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">
        Action Title
    </label>

    <input
        type="text"
        name="action_title"
        required
        value="{{ old('action_title', $recommendationRule?->action_title) }}"
        placeholder="Example: Establish an executive AI sponsor"
        class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
    >
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">
        Action Description
    </label>

    <textarea
        name="action_description"
        rows="4"
        required
        placeholder="Describe what the organization should implement."
        class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
    >{{ old('action_description', $recommendationRule?->action_description) }}</textarea>
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">
        Business Rationale
    </label>

    <textarea
        name="business_rationale"
        rows="4"
        placeholder="Explain why this action is required and what risk or value it addresses."
        class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
    >{{ old('business_rationale', $recommendationRule?->business_rationale) }}</textarea>
</div>
<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">
        Category
    </label>

    <select
        name="category"
        class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
    >
        <option value="">Select a category</option>

        @foreach([
            'Strategy',
            'Governance',
            'Data',
            'Technology',
            'Risk',
            'People',
            'Adoption'
        ] as $category)

            <option
                value="{{ $category }}"
                {{ old('category', $recommendationRule?->category) === $category ? 'selected' : '' }}
            >
                {{ $category }}
            </option>

        @endforeach

    </select>

    <p class="mt-2 text-xs text-slate-500">
        Used for reporting, analytics and AI insight generation.
    </p>
</div>

<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">
        Expected Business Outcomes
    </label>

    <textarea
        name="expected_outcomes"
        rows="4"
        placeholder="Example: Improved executive alignment, reduced compliance risk, higher AI adoption..."
        class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
    >{{ old('expected_outcomes', $recommendationRule?->expected_outcomes) }}</textarea>

    <p class="mt-2 text-xs text-slate-500">
        Describe the measurable business benefits expected after implementing this recommendation.
    </p>
</div>


<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Minimum Timeline — Months
        </label>

        <input
            type="number"
            name="timeline_min_months"
            min="0"
            value="{{ old('timeline_min_months', $recommendationRule?->timeline_min_months) }}"
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Maximum Timeline — Months
        </label>

        <input
            type="number"
            name="timeline_max_months"
            min="0"
            value="{{ old('timeline_max_months', $recommendationRule?->timeline_max_months) }}"
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
    </div>

</div>

<div class="grid grid-cols-1 gap-6 md:grid-cols-3">

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Minimum Investment
        </label>

        <input
            type="number"
            name="investment_min"
            min="0"
            step="0.01"
            value="{{ old('investment_min', $recommendationRule?->investment_min) }}"
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Maximum Investment
        </label>

        <input
            type="number"
            name="investment_max"
            min="0"
            step="0.01"
            value="{{ old('investment_max', $recommendationRule?->investment_max) }}"
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Currency
        </label>

        <select
            name="currency"
            required
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
            @foreach(['USD', 'EUR', 'GBP', 'AED', 'SAR'] as $currency)
                <option
                    value="{{ $currency }}"
                    {{ old('currency', $recommendationRule?->currency ?? 'USD') === $currency ? 'selected' : '' }}
                >
                    {{ $currency }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<div class="grid grid-cols-1 gap-6 md:grid-cols-3">

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Priority Weight
        </label>

        <input
            type="number"
            name="priority_weight"
            min="1"
            required
            value="{{ old('priority_weight', $recommendationRule?->priority_weight ?? 1) }}"
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >

        <p class="mt-2 text-xs text-slate-500">
            Higher values increase the action’s ranking.
        </p>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Dependency Order
        </label>

        <input
            type="number"
            name="dependency_order"
            min="1"
            required
            value="{{ old('dependency_order', $recommendationRule?->dependency_order ?? 1) }}"
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >

        <p class="mt-2 text-xs text-slate-500">
            Lower values are scheduled earlier in the roadmap.
        </p>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Standard Reference
        </label>

        <input
            type="text"
            name="standard_reference"
            value="{{ old('standard_reference', $recommendationRule?->standard_reference) }}"
            placeholder="Example: ISO 42001 — Clause 5"
            class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
        >
    </div>

</div>

<label class="flex items-center gap-3">
    <input
        type="checkbox"
        name="is_active"
        value="1"
        {{ old('is_active', $recommendationRule?->is_active ?? true) ? 'checked' : '' }}
        class="rounded border-slate-300 text-yellow-500 focus:ring-yellow-400"
    >

    <span class="text-sm font-medium text-slate-700">
        Active recommendation rule
    </span>
</label>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dimensionSelect = document.getElementById('dimension_id');
        const questionSelect = document.getElementById('question_id');

        if (!dimensionSelect || !questionSelect) {
            return;
        }

        const filterQuestions = () => {
            const dimensionId = dimensionSelect.value;

            Array.from(questionSelect.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const belongsToDimension =
                    !dimensionId ||
                    option.dataset.dimensionId === dimensionId;

                option.hidden = !belongsToDimension;

                if (!belongsToDimension && option.selected) {
                    questionSelect.value = '';
                }
            });
        };

        dimensionSelect.addEventListener('change', filterQuestions);

        filterQuestions();
    });
</script>