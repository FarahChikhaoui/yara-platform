<x-app-layout>

    @php
        $score = (float) $pulseCheck->overall_score;
        $gaugeDegrees = max(0, min(100, $score)) * 3.6;

        $signalClasses = match($pulseCheck->readiness_signal) {
            'Foundational' => 'bg-red-100 text-red-700 border-red-200',
            'Emerging' => 'bg-orange-100 text-orange-700 border-orange-200',
            'Developing' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            default => 'bg-green-100 text-green-700 border-green-200',
        };

        $cta = match($pulseCheck->readiness_signal) {

            'Foundational' => [
                'eyebrow' => 'Recommended next step',
                'title' => 'Build the foundations for AI readiness.',
                'description' => 'Your Pulse Check suggests that important foundations are still developing. Complete the full assessment to identify your most critical capability gaps and understand where to focus first.',
                'button' => 'Identify Your Priority Gaps',
            ],

            'Emerging' => [
                'eyebrow' => 'Recommended next step',
                'title' => 'Turn early progress into structured readiness.',
                'description' => 'Your organization shows signs of AI adoption, but readiness may still be uneven. The full assessment will reveal where capabilities are established and where gaps could limit your ability to scale.',
                'button' => 'Get Your Full Readiness Assessment',
            ],

            'Developing' => [
                'eyebrow' => 'Recommended next step',
                'title' => 'Find what is limiting your next stage of AI maturity.',
                'description' => 'You already have meaningful AI capabilities in place. Complete the full assessment to uncover remaining weaknesses, validate your strengths and identify what may be preventing more consistent AI adoption.',
                'button' => 'Explore Your Remaining Gaps',
            ],

            default => [
                'eyebrow' => 'Recommended next step',
                'title' => 'Validate how strong your AI readiness really is.',
                'description' => 'Your Pulse Check indicates strong AI readiness. The full assessment will validate that signal across the complete capability framework and show how your organization compares with its country benchmark.',
                'button' => 'Benchmark Your AI Readiness',
            ],
        };
    @endphp

    <div class="min-h-screen bg-slate-50">

        <section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white">

            <div class="pointer-events-none absolute -top-32 -right-32 h-96 w-96 rounded-full bg-yellow-400/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-yellow-400/5 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 24px 24px;"></div>

            <div class="relative mx-auto max-w-6xl px-6 py-14">

                <p class="flex items-center gap-2 text-sm font-bold uppercase tracking-[0.18em] text-yellow-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Free AI Readiness Diagnostic
                </p>

                <h1 class="mt-3 text-4xl font-bold tracking-tight md:text-5xl">
                    Your AI Readiness Signal
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-300">
                    This result provides an initial indication based on a limited
                    set of diagnostic questions.
                </p>

            </div>
        </section>

        <main class="mx-auto max-w-6xl px-6 py-10">

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/50">

                <div class="grid lg:grid-cols-[0.7fr_1.3fr]">

                    <div class="relative flex flex-col items-center justify-center overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 px-8 py-12 text-center text-white">

                        <div class="pointer-events-none absolute top-0 left-1/2 h-64 w-64 -translate-x-1/2 rounded-full bg-yellow-400/10 blur-3xl"></div>

                        <p class="relative text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Readiness signal
                        </p>

                        <div
                            class="relative mt-6 h-52 w-52 rounded-full shadow-[0_0_40px_-8px_rgba(250,204,21,0.35)]"
                            style="
                                background:
                                    conic-gradient(
                                        #facc15 0deg {{ $gaugeDegrees }}deg,
                                        #1e293b {{ $gaugeDegrees }}deg 360deg
                                    );
                            "
                        >
                            <div class="absolute inset-[18px] flex items-center justify-center rounded-full bg-slate-950 ring-1 ring-white/5">
                                <div>
                                    <p class="text-6xl font-bold text-white">
                                        {{ number_format($score, 0) }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">
                                        out of 100
                                    </p>
                                </div>
                            </div>
                        </div>

                        <span class="relative mt-7 inline-flex rounded-full border px-4 py-2 text-sm font-bold shadow-sm {{ $signalClasses }}">
                            {{ $pulseCheck->readiness_signal }}
                        </span>

                    </div>

                    <div class="px-8 py-10 md:px-12">

                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-yellow-600">
                            Executive interpretation
                        </p>

                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
                            What this signal suggests
                        </h2>

                        <p class="mt-5 text-lg leading-8 text-slate-600">
                            {{ $summary }}
                        </p>

                        <div class="mt-8 grid gap-4 md:grid-cols-2">

                            <div class="group rounded-2xl border border-green-200 bg-gradient-to-br from-green-50 to-white p-6 transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-lg hover:shadow-green-100">

                                <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-green-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    Relative strength
                                </p>

                                <p class="mt-3 text-xl font-bold text-slate-950">
                                    {{ $pulseCheck->strongestDimension?->name ?? 'Not available' }}
                                </p>

                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                    This capability received the strongest result among the areas included in the Pulse Check.
                                </p>

                            </div>

                            <div class="group rounded-2xl border border-orange-200 bg-gradient-to-br from-orange-50 to-white p-6 transition-all duration-200 hover:-translate-y-1 hover:border-orange-300 hover:shadow-lg hover:shadow-orange-100">

                                <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-orange-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    Main attention area
                                </p>

                                <p class="mt-3 text-xl font-bold text-slate-950">
                                    {{ $pulseCheck->weakestDimension?->name ?? 'Not available' }}
                                </p>

                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                    This area may require deeper investigation through the complete assessment.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            <section class="mt-8 space-y-8">

    {{-- Understanding the result --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm sm:p-10">

        <div class="max-w-2xl">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-yellow-600">
                Understanding your result
            </p>

            <h2 class="mt-3 text-2xl font-bold text-slate-950">
                This is an initial signal, not a full diagnostic.
            </h2>
        </div>

        <div class="mt-7 grid gap-4 sm:grid-cols-3">

            <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-5 transition hover:border-slate-200 hover:bg-slate-50">
                <span class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-green-100 text-green-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </span>
                <p class="text-sm leading-6 text-slate-600">
                    Based only on the selected Pulse Check questions.
                </p>
            </div>

            <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-5 transition hover:border-slate-200 hover:bg-slate-50">
                <span class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-green-100 text-green-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </span>
                <p class="text-sm leading-6 text-slate-600">
                    Does not include country benchmarking or industry context.
                </p>
            </div>

            <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-5 transition hover:border-slate-200 hover:bg-slate-50">
                <span class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-green-100 text-green-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </span>
                <p class="text-sm leading-6 text-slate-600">
                    Does not provide a detailed roadmap, investment estimate or full capability analysis.
                </p>
            </div>

        </div>

    </div>


    {{-- Personalized next step --}}
    <div class="overflow-hidden rounded-3xl bg-slate-950 text-white shadow-xl shadow-slate-900/10">

        <div class="grid lg:grid-cols-[1.05fr_0.95fr]">

            {{-- Personalized recommendation --}}
            <div class="px-8 py-10 sm:px-10 lg:py-12">

                <p class="inline-flex items-center gap-2 rounded-full bg-yellow-400/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-yellow-400">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    {{ $cta['eyebrow'] }}
                </p>

                <h2 class="mt-4 max-w-xl text-3xl font-bold leading-tight">
                    {{ $cta['title'] }}
                </h2>

                <p class="mt-5 max-w-2xl leading-7 text-slate-300">
                    {{ $cta['description'] }}
                </p>

            </div>


            {{-- Full assessment benefits --}}
            <div class="border-t border-slate-800/80 bg-slate-900/40 px-8 py-10 sm:px-10 lg:border-l lg:border-t-0 lg:py-12">

                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Your full assessment includes
                </p>

                <ul class="mt-6 grid gap-3.5 text-slate-200 sm:grid-cols-2 lg:grid-cols-1">

                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-yellow-400/15 text-yellow-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-sm">Full AI readiness assessment</span>
                    </li>

                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-yellow-400/15 text-yellow-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-sm">Detailed capability scoring</span>
                    </li>

                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-yellow-400/15 text-yellow-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-sm">Organization maturity level</span>
                    </li>

                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-yellow-400/15 text-yellow-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-sm">Strategic strengths and priority gaps</span>
                    </li>

                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-yellow-400/15 text-yellow-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-sm">Country readiness benchmark</span>
                    </li>

                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-yellow-400/15 text-yellow-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-sm">Progress tracking across assessments</span>
                    </li>

                </ul>

            </div>

        </div>


        {{-- Actions --}}
        <div class="border-t border-slate-800/80 bg-slate-900/20 px-8 py-6 sm:px-10">

            <div class="flex flex-col gap-3 sm:flex-row">

                <a
                    href="{{ route('register') }}"
                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-yellow-400 px-6 py-4 font-bold text-slate-950 shadow-sm shadow-yellow-400/20 transition hover:-translate-y-0.5 hover:bg-yellow-300 hover:shadow-md hover:shadow-yellow-400/30 active:translate-y-0"
                >
                    {{ $cta['button'] }}

                    <svg
                        class="h-5 w-5"
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
                </a>

                <a
                    href="{{ url('/') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-8 py-4 font-semibold text-white transition hover:border-slate-600 hover:bg-slate-900"
                >
                    Return Home
                </a>

            </div>

        </div>

    </div>

</section>

        </main>

    </div>

</x-app-layout>
