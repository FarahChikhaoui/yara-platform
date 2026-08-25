<x-app-layout>

    @php
        $score = (float) $pulseCheck->overall_score;

        $signalClasses = match($pulseCheck->readiness_signal) {
            'Foundational' => 'bg-red-100 text-red-700 border-red-200',
            'Emerging' => 'bg-orange-100 text-orange-700 border-orange-200',
            'Developing' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            default => 'bg-green-100 text-green-700 border-green-200',
        };
    @endphp

    <div class="min-h-screen bg-slate-50">

        <section class="bg-slate-950 text-white">
            <div class="mx-auto max-w-6xl px-6 py-12">

                <p class="text-sm font-bold uppercase tracking-[0.18em] text-yellow-400">
                    Free AI Readiness Diagnostic
                </p>

                <h1 class="mt-3 text-4xl font-bold">
                    Your AI Readiness Signal
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-300">
                    This result provides an initial indication based on a limited
                    set of diagnostic questions.
                </p>

            </div>
        </section>

        <main class="mx-auto max-w-6xl px-6 py-10">

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="grid lg:grid-cols-[0.7fr_1.3fr]">

                    <div class="flex flex-col items-center justify-center bg-slate-950 px-8 py-12 text-center text-white">

                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                            Readiness signal
                        </p>

                        <div class="mt-6 flex h-52 w-52 items-center justify-center rounded-full border-[18px] border-yellow-400 bg-slate-900">

                            <div>
                                <p class="text-6xl font-bold">
                                    {{ number_format($score, 0) }}
                                </p>

                                <p class="mt-1 text-sm text-slate-400">
                                    out of 100
                                </p>
                            </div>

                        </div>

                        <span class="mt-7 inline-flex rounded-full border px-4 py-2 text-sm font-bold {{ $signalClasses }}">
                            {{ $pulseCheck->readiness_signal }}
                        </span>

                    </div>

                    <div class="px-8 py-10 md:px-12">

                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-yellow-600">
                            Executive interpretation
                        </p>

                        <h2 class="mt-3 text-3xl font-bold text-slate-950">
                            What this signal suggests
                        </h2>

                        <p class="mt-5 text-lg leading-8 text-slate-600">
                            {{ $summary }}
                        </p>

                        <div class="mt-8 grid gap-4 md:grid-cols-2">

                            <div class="rounded-2xl border border-green-200 bg-green-50 p-6">

                                <p class="text-xs font-bold uppercase tracking-wider text-green-700">
                                    Relative strength
                                </p>

                                <p class="mt-3 text-xl font-bold text-slate-950">
                                    {{ $pulseCheck->strongestDimension?->name ?? 'Not available' }}
                                </p>

                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                    This capability received the strongest result among the areas included in the Pulse Check.
                                </p>

                            </div>

                            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-6">

                                <p class="text-xs font-bold uppercase tracking-wider text-orange-700">
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

            <section class="mt-8 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">

                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                    <p class="text-sm font-bold uppercase tracking-[0.18em] text-yellow-600">
                        Understanding your result
                    </p>

                    <h2 class="mt-3 text-2xl font-bold text-slate-950">
                        This is an initial signal, not a full diagnostic.
                    </h2>

                    <div class="mt-6 space-y-4 text-slate-600">

                        <div class="flex gap-3">
                            <span class="font-bold text-green-600">✓</span>
                            <p>
                                Based only on the selected Pulse Check questions.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <span class="font-bold text-green-600">✓</span>
                            <p>
                                Does not include country benchmarking or industry context.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <span class="font-bold text-green-600">✓</span>
                            <p>
                                Does not provide a detailed roadmap, investment estimate or full capability analysis.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="rounded-3xl bg-slate-950 p-8 text-white shadow-sm">

                    <p class="text-sm font-bold uppercase tracking-[0.18em] text-yellow-400">
                        Recommended next step
                    </p>

                    <h2 class="mt-3 text-3xl font-bold">
                        Unlock the complete picture.
                    </h2>

                    <p class="mt-4 leading-7 text-slate-300">
                        Create a free account to complete the full AI Readiness Assessment and receive a detailed analysis of your organization's maturity, strengths and priority gaps.
                    </p>

                    <ul class="mt-6 space-y-3 text-slate-200">
                        <li>✓ Full capability assessment</li>
                                                <li>✓ Detailed dimension scoring</li>
<li>✓ Country benchmark</li>
                        <li>✓ AI-powered executive summary</li>
                        <li>✓ Personalized recommendations</li>
                        <li>✓ Downloadable assessment report</li>
                    </ul>

                    <a
                        href="{{ route('register') }}"
                        class="mt-8 inline-flex w-full justify-center rounded-xl bg-yellow-400 px-6 py-4 font-bold text-slate-950 transition hover:bg-yellow-300"
                    >
                        Create Account and Continue
                    </a>

                    <a
                        href="{{ url('/') }}"
                        class="mt-3 inline-flex w-full justify-center rounded-xl border border-slate-700 px-6 py-3 font-semibold text-white transition hover:bg-slate-900"
                    >
                        Return Home
                    </a>

                </div>

            </section>

        </main>

    </div>

</x-app-layout>