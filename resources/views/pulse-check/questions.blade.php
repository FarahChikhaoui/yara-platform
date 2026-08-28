<x-app-layout>

<div
    class="min-h-screen bg-slate-50"
    style="font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;"
>{{-- ========================================================= --}}
{{-- YARA HEADER --}}
{{-- ========================================================= --}}

<header class="border-b-4 border-yellow-400 bg-slate-950 text-white">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-8 py-5">

        {{-- Brand --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3">

            <img
                src="{{ asset('assets/yellomind_logo.png') }}"
                alt="Yellomind Logo"
                class="h-10 w-10 object-contain"
            >

            <div>
                <h1 class="text-xl font-bold">
                    YARA
                </h1>

                <p class="text-sm text-slate-300">
    Yellomind · AI Readiness Assessment
</p>
            </div>

        </a>


        {{-- Right side --}}
        <div class="flex items-center gap-5">

            @auth

                <x-user-dropdown button-text-class="text-slate-300" />

            @else

                <a
                    href="{{ route('login') }}"
                    class="text-sm font-semibold text-slate-300 transition hover:text-white"
                >
                    Log in
                </a>

                <a
                    href="{{ route('register') }}"
                    class="rounded-xl bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-yellow-300"
                >
                    Create account
                </a>

            @endauth

        </div>

    </div>

</header>
        {{-- Pulse Check intro --}}
<section class="border-b border-slate-200 bg-white">

    <div class="mx-auto max-w-5xl px-6 py-10">

        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                    Free AI Readiness Diagnostic
                </p>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">
                    AI Readiness Pulse Check
                </h1>

                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    Select the answer that best reflects your organization's current situation.
                </p>

            </div>


            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-950 text-lg font-bold text-yellow-400">
                    {{ $questions->count() }}
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                        Questions
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-950">
                        Quick diagnostic
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>

        <main class="mx-auto max-w-5xl px-6 py-10">

            @if($questions->isEmpty())

                <div class="rounded-3xl border border-yellow-200 bg-yellow-50 p-8 text-center">

                    <h2 class="text-xl font-bold text-slate-950">
                        No Pulse Check questions selected
                    </h2>

                    <p class="mt-3 text-slate-600">
                        Select Pulse Check questions from Admin → Questions.
                    </p>

                </div>

            @else
{{-- Incomplete Pulse Check warning --}}
<div
    id="pulseCheckError"
    class="mb-6 hidden rounded-2xl border border-red-200 bg-red-50 p-5"
>
    <div class="flex items-start gap-3">

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 font-bold text-red-600">
            !
        </div>

        <div>
            <p class="font-bold text-red-800">
                Pulse Check incomplete
            </p>

            <p
                id="pulseCheckErrorMessage"
                class="mt-1 text-sm text-red-700"
            ></p>
        </div>

    </div>
</div>
                <form
                    action="{{ route('pulse.submit', $token) }}"
                    method="POST"
                    id="pulseCheckForm"
                >
                    @csrf

                    {{-- Overall progress --}}
                    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-700">
                                Assessment progress
                            </p>

                            <p class="text-sm font-bold text-slate-950">
                                <span id="answeredCount">0</span>
                                /
                                {{ $questions->count() }}
                                answered
                            </p>
                        </div>

                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div
                                id="progressBar"
                                class="h-full rounded-full bg-yellow-400 transition-all duration-300"
                                style="width: 0%"
                            ></div>
                        </div>

                    </div>

                    <div class="space-y-6">

                        @foreach($questions as $question)

                            <section
                                class="question-card rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition md:p-8"
                                data-question="{{ $question->id }}"
                            >

                                <div class="flex items-start gap-4">

                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-950 font-bold text-white">
                                        {{ $loop->iteration }}
                                    </span>

                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-wrap items-center gap-3">
                                            <p class="text-xs font-bold uppercase tracking-wider text-yellow-600">
                                                Question {{ $loop->iteration }} of {{ $questions->count() }}
                                            </p>

                                            @if($question->dimension)
                                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-800">
                                                    {{ $question->dimension->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <h2 class="mt-3 text-xl font-bold leading-8 text-slate-950">
                                            {{ $question->question_text }}
                                        </h2>

                                    </div>

                                </div>

                                <div class="mt-6 space-y-3">

                                    @foreach($question->answerOptions as $option)

                                        <label
                                            class="answer-option flex cursor-pointer items-start gap-4 rounded-2xl border border-slate-200 p-5 transition hover:border-yellow-400 hover:bg-yellow-50"
                                        >
                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}]"
                                                value="{{ $option->id }}"
                                                class="answer-radio mt-1 h-5 w-5 border-slate-300 text-yellow-500 focus:ring-yellow-400"
                                            >

                                            <span class="leading-7 text-slate-700">
                                                {{ $option->label }}
                                            </span>
                                        </label>

                                    @endforeach

                                </div>
<div class="question-error mt-4 hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
    <strong>! Answer required.</strong>
    Please select an option before viewing your result.
</div>
                            </section>

                        @endforeach

                    </div>

                    <div class="mt-8 rounded-3xl bg-slate-950 p-7 text-white">

                        <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                            <div>
                                <p class="text-sm font-bold uppercase tracking-wider text-yellow-400">
                                    Your instant result
                                </p>

                                <p class="mt-2 text-slate-300">
                                    Complete every question to generate your AI readiness signal.
                                </p>
                            </div>

                            <button
                                type="submit"
                                id="submitButton"
                                class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-7 py-4 font-bold text-slate-950 transition hover:bg-yellow-300"
                            >
                                View My Pulse Result

                                <svg
                                    class="ml-3 h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </button>

                        </div>

                    </div>

                </form>

            @endif

        </main>

    </div>

   <script>
    const form = document.getElementById('pulseCheckForm');
    const radios = document.querySelectorAll('.answer-radio');
    const questionCards = document.querySelectorAll('.question-card');
    const answeredCount = document.getElementById('answeredCount');
    const progressBar = document.getElementById('progressBar');
    const pulseCheckError = document.getElementById('pulseCheckError');
    const pulseCheckErrorMessage = document.getElementById('pulseCheckErrorMessage');

    const totalQuestions = {{ $questions->count() }};


    /*
     * Update progress indicator.
     */
    function updateProgress() {

        const answeredQuestions = new Set();

        document.querySelectorAll('.answer-radio:checked').forEach(radio => {
            answeredQuestions.add(radio.name);
        });

        const answered = answeredQuestions.size;

        const percentage = totalQuestions > 0
            ? (answered / totalQuestions) * 100
            : 0;

        answeredCount.textContent = answered;
        progressBar.style.width = percentage + '%';
    }


    /*
     * Handle answer selection.
     */
    radios.forEach(radio => {

        radio.addEventListener('change', function () {

            const card = this.closest('.question-card');

            /*
             * Remove previous selected styling.
             */
            card.querySelectorAll('.answer-option').forEach(option => {

                option.classList.remove(
                    'border-yellow-400',
                    'bg-yellow-50',
                    'ring-2',
                    'ring-yellow-100'
                );

            });


            /*
             * Highlight selected answer.
             */
            this.closest('.answer-option').classList.add(
                'border-yellow-400',
                'bg-yellow-50',
                'ring-2',
                'ring-yellow-100'
            );


            /*
             * If this question was previously marked as
             * unanswered, remove the warning styling now.
             */
           card.classList.remove(
    'border-red-300',
    'bg-red-50',
    'ring-2',
    'ring-red-100'
);

card.querySelector('.question-error').classList.add('hidden');

updateProgress();

            /*
             * If all questions have now been answered,
             * hide the incomplete warning automatically.
             */
            const answeredQuestions = new Set();

            document.querySelectorAll('.answer-radio:checked').forEach(answer => {
                answeredQuestions.add(answer.name);
            });

            if (answeredQuestions.size === totalQuestions) {
                pulseCheckError.classList.add('hidden');
            }

        });

    });


    /*
     * Validate the Pulse Check before submitting.
     *
     * If questions are unanswered, prevent the form from
     * submitting so the page does NOT reload.
     */
    form.addEventListener('submit', function (event) {

        const missingCards = [];


        /*
         * Clear previous warning styling.
         */
       questionCards.forEach(card => {

    card.classList.remove(
        'border-red-300',
        'bg-red-50',
        'ring-2',
        'ring-red-100'
    );

    card.querySelector('.question-error').classList.add('hidden');
});


        /*
         * Find unanswered questions.
         */
        questionCards.forEach(card => {

            const checkedAnswer = card.querySelector(
                '.answer-radio:checked'
            );

          if (!checkedAnswer) {

    missingCards.push(card);

    card.classList.add(
        'border-red-300',
        'ring-2',
        'ring-red-100'
    );
card.querySelector('.question-error').classList.remove('hidden');
}

        });


        /*
         * Everything is answered.
         *
         * Allow normal Laravel submission so the Pulse
         * Check result can be calculated normally.
         */
        if (missingCards.length === 0) {
            return;
        }


        /*
         * Prevent submission/reload.
         */
        event.preventDefault();


        const count = missingCards.length;

        pulseCheckErrorMessage.textContent =
            count === 1
                ? '1 question remains unanswered. Please complete it before viewing your result.'
                : count + ' questions remain unanswered. Please complete them before viewing your result.';


        /*
         * Show warning.
         */
        pulseCheckError.classList.remove('hidden');


        /*
         * Scroll to first unanswered question.
         */
        missingCards[0].scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

    });


    /*
     * Initialize progress when the page loads.
     */
    updateProgress();
</script>

</x-app-layout>