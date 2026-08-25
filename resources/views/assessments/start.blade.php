@extends('layouts.yara')

@section('content')

<div class="min-h-screen bg-slate-50">

    <!-- Sticky progress header -->
    <div class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur-md">
        <div class="max-w-5xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Dashboard
                </a>

                <div class="flex items-center gap-3">
                    <span id="progress-label" class="text-xs font-semibold text-slate-400">0% complete</span>
                    <div class="w-40 h-2 rounded-full bg-slate-100 overflow-hidden">
                        <div id="progress-bar" class="h-full rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 transition-all duration-500 ease-out" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-12 space-y-10">

        <!-- Header -->
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 mb-4">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.05a7.002 7.002 0 015.95 5.95H18a1 1 0 110 2h-1.05A7.002 7.002 0 0111 16.95V18a1 1 0 11-2 0v-1.05A7.002 7.002 0 013.05 11H2a1 1 0 110-2h1.05A7.002 7.002 0 019 3.05V2a1 1 0 011-1z"/></svg>
                    AI Readiness Assessment
                </span>
            </div>

            <h1 class="text-4xl font-bold tracking-tight text-slate-900">
                Organizational Assessment
            </h1>

            <p class="mt-3 text-base text-slate-500 leading-relaxed">
                Complete the following assessment to evaluate your organization's AI readiness maturity. Your progress is saved automatically as you go.
            </p>
        </div>
@if(session('assessment_error'))

    <div
        id="assessment-error"
        class="rounded-2xl border border-red-200 bg-red-50 p-5"
    >
        <div class="flex items-start gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                !
            </div>

            <div>
                <p class="font-bold text-red-800">
                    Assessment incomplete
                </p>

                <p class="mt-1 text-sm text-red-700">
                    {{ session('assessment_error') }}
                </p>

                @if(session('missing_questions_count'))
                    <p class="mt-2 text-sm font-semibold text-red-800">
                        {{ session('missing_questions_count') }}
                        {{ session('missing_questions_count') == 1 ? 'question remains' : 'questions remain' }} unanswered.
                    </p>
                @endif
            </div>

        </div>
    </div>

@endif
{{-- Client-side incomplete assessment warning --}}
<div
    id="client-assessment-error"
    class="hidden rounded-2xl border border-red-200 bg-red-50 p-5"
>
    <div class="flex items-start gap-3">

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 font-bold text-red-600">
            !
        </div>

        <div>
            <p class="font-bold text-red-800">
                Assessment incomplete
            </p>

            <p id="client-assessment-error-message"
               class="mt-1 text-sm text-red-700">
            </p>
        </div>

    </div>
</div>
        <form method="POST" action="/assessment/submit" class="space-y-8" id="assessment-form">
            @csrf
            <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">

            @foreach($dimensions as $dimIndex => $dimension)

                <section class="group bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 overflow-hidden transition hover:shadow-md">

                    <!-- Dimension header -->
                    <div class="flex items-start gap-4 px-8 py-6 border-b border-slate-100 bg-gradient-to-br from-slate-50/80 to-white">
                        <div class="flex-none flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900 text-yellow-400 font-bold text-sm">
                            {{ str_pad($dimIndex + 1, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">
                                {{ $dimension->name }}
                            </h2>
                            @if($dimension->description)
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $dimension->description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">

                        @foreach($dimension->questions as $question)

<div
    id="question-{{ $question->id }}"
    class="assessment-question px-8 py-7 transition-colors duration-300"
    data-question-container="{{ $question->id }}"
>
                                <div class="flex items-center gap-2 mb-5">
                                    <h3 class="font-semibold text-[15px] text-slate-900 leading-snug">
                                        {{ $question->question_text }}
                                    </h3>

                                    @if($question->help_text)
                                        <div class="relative group/tip flex-none">
                                            <button type="button"
                                                    class="w-5 h-5 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold flex items-center justify-center transition hover:bg-yellow-200"
                                                    aria-label="More info"
                                            >
                                                ?
                                            </button>

                                            <div class="pointer-events-none opacity-0 group-hover/tip:opacity-100 group-hover/tip:pointer-events-auto transition-opacity duration-150 absolute left-0 top-7 z-20 w-72 bg-slate-950 text-white text-xs leading-relaxed rounded-xl p-4 shadow-xl">
                                                {{ $question->help_text }}
                                                <div class="absolute -top-1 left-2 w-2 h-2 bg-slate-950 rotate-45"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <span
                                        class="answer-status ml-auto flex-none inline-flex items-center gap-1 text-xs font-medium text-emerald-600 opacity-0 transition-opacity duration-300"
                                        data-status-for="{{ $question->id }}"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        Saved
                                    </span>
                                </div>

                                <div class="grid gap-2.5 sm:grid-cols-2" data-question-group="{{ $question->id }}">

                                    @foreach($question->answerOptions as $option)

                                        <label class="answer-option relative flex items-center gap-3 rounded-xl border border-slate-200 p-3.5 cursor-pointer transition hover:border-yellow-300 hover:bg-yellow-50/40 has-[:checked]:border-yellow-400 has-[:checked]:bg-yellow-50 has-[:checked]:ring-1 has-[:checked]:ring-yellow-400">

                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}]"
                                                value="{{ $option->id }}"
                                                data-question-id="{{ $question->id }}"
                                                @checked(
                                                    isset($savedAnswers) &&
                                                    (string) $savedAnswers->get($question->id) === (string) $option->id
                                                )
                                                class="assessment-answer peer sr-only"
                                            >

                                            <span class="flex-none w-4.5 h-4.5 rounded-full border-2 border-slate-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 flex items-center justify-center transition">
                                                <svg class="w-2.5 h-2.5 text-white opacity-0 peer-checked:opacity-100 transition" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="4"/>
                                                </svg>
                                            </span>

                                            <span class="text-sm text-slate-700 peer-checked:text-slate-900 peer-checked:font-medium">
                                                {{ $option->label }}
                                            </span>

                                        </label>

                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    </div>

                </section>

            @endforeach

            <div class="flex items-center justify-between pt-2 pb-8">
                <p class="text-sm text-slate-400">
                    Answers save automatically — you can submit once every question is answered.
                </p>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 bg-slate-950 text-white px-8 py-4 rounded-xl font-semibold shadow-sm transition hover:bg-slate-800 hover:shadow-md active:scale-[0.98]"
                >
                    Complete Assessment
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12" />
                    </svg>
                </button>
            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const answers = document.querySelectorAll('.assessment-answer');
    const totalQuestions = document.querySelectorAll('[data-question-group]').length;
    const progressBar = document.getElementById('progress-bar');
    const progressLabel = document.getElementById('progress-label');
const form = document.getElementById('assessment-form');
const clientError = document.getElementById('client-assessment-error');
const clientErrorMessage = document.getElementById('client-assessment-error-message');

    function updateProgress() {
        const answeredGroups = new Set();
        document.querySelectorAll('.assessment-answer:checked').forEach(function (radio) {
            answeredGroups.add(radio.dataset.questionId);
        });
        const pct = totalQuestions ? Math.round((answeredGroups.size / totalQuestions) * 100) : 0;
        progressBar.style.width = pct + '%';
        progressLabel.textContent = pct + '% complete';
    }

    updateProgress();

    answers.forEach(function (radio) {

        radio.addEventListener('change', async function () {

            const questionId = this.dataset.questionId;
            const answerOptionId = this.value;
            const questionContainer = document.querySelector(
    '[data-question-container="' + questionId + '"]'
);

if (questionContainer) {
    questionContainer.classList.remove(
        'bg-red-50',
        'ring-1',
        'ring-inset',
        'ring-red-200'
    );
}
            const statusEl = document.querySelector('[data-status-for="' + questionId + '"]');

            updateProgress();

            try {

                const response = await fetch(
                    "{{ route('assessment.save-answer', $assessment) }}",
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            answer_option_id: answerOptionId
                        })
                    }
                );

                if (!response.ok) {
                    throw new Error('Unable to save answer');
                }

                if (statusEl) {
                    statusEl.classList.remove('opacity-0');
                    statusEl.classList.add('opacity-100');
                    clearTimeout(statusEl._hideTimeout);
                    statusEl._hideTimeout = setTimeout(function () {
                        statusEl.classList.remove('opacity-100');
                        statusEl.classList.add('opacity-0');
                    }, 1800);
                }

            } catch (error) {
                console.error('Autosave failed:', error);
            }

        });

    });
    /*
 * Validate completion in the browser before allowing
 * the final form submission.
 *
 * This avoids a full page reload just to tell the user
 * that some questions are unanswered.
 */
form.addEventListener('submit', function (event) {

    const questionGroups = document.querySelectorAll('[data-question-group]');
    const missingQuestions = [];

    /*
     * Remove previous missing-question highlighting.
     */
    document.querySelectorAll('[data-question-container]').forEach(function (container) {
        container.classList.remove(
            'bg-red-50',
            'ring-1',
            'ring-inset',
            'ring-red-200'
        );
    });

    /*
     * Find every question that has no selected answer.
     */
    questionGroups.forEach(function (group) {

        const checkedAnswer = group.querySelector('.assessment-answer:checked');

        if (!checkedAnswer) {

            const questionId = group.dataset.questionGroup;

            missingQuestions.push(questionId);

            const container = document.querySelector(
                '[data-question-container="' + questionId + '"]'
            );

            if (container) {
                container.classList.add(
                    'bg-red-50',
                    'ring-1',
                    'ring-inset',
                    'ring-red-200'
                );
            }
        }
    });

    /*
     * Everything is complete.
     * Do NOT prevent submission — let Laravel finish
     * and calculate the assessment normally.
     */
    if (missingQuestions.length === 0) {
        return;
    }

    /*
     * Some questions are missing.
     * Stop the form before it reaches Laravel.
     */
    event.preventDefault();

    const count = missingQuestions.length;

    clientErrorMessage.textContent =
        count === 1
            ? '1 question remains unanswered. Please complete it before submitting.'
            : count + ' questions remain unanswered. Please complete them before submitting.';

    clientError.classList.remove('hidden');

    /*
     * Scroll directly to the first unanswered question.
     */
    const firstMissing = document.querySelector(
        '[data-question-container="' + missingQuestions[0] + '"]'
    );

    if (firstMissing) {
        firstMissing.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
});

});
</script>
@endsection