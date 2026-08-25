@extends('layouts.yara')

@section('content')
    <div class="bg-slate-50 py-12 md:py-16">

        <div class="mx-auto max-w-6xl px-6">

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="grid lg:grid-cols-[1.15fr_0.85fr]">

                    {{-- Left side --}}
                    <div class="px-8 py-12 md:px-12 md:py-16">

                        <span class="inline-flex rounded-full bg-yellow-100 px-4 py-2 text-xs font-bold uppercase tracking-wider text-yellow-800">
                            Free · No account required
                        </span>

                        <p class="mt-8 text-sm font-bold uppercase tracking-[0.18em] text-yellow-600">
                            AI Readiness Pulse Check
                        </p>

                        <h1 class="mt-3 max-w-3xl text-4xl font-bold leading-tight text-slate-950 md:text-5xl">
                            Get an initial view of your organization’s AI readiness.
                        </h1>

                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                            Complete a short diagnostic to identify high-level strengths,
                            critical capability gaps and whether a deeper assessment is warranted.
                        </p>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-2xl font-bold text-slate-950">15–20</p>
                                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Questions
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-2xl font-bold text-slate-950">&lt; 10 min</p>
                                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Completion time
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-2xl font-bold text-slate-950">Instant</p>
                                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Readiness signal
                                </p>
                            </div>

                        </div>

                        <form
                            action="{{ route('pulse.start') }}"
                            method="POST"
                            class="mt-10"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-7 py-4 font-bold text-white transition hover:bg-slate-800"
                            >
                                Start the Free Pulse Check

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
                        </form>

                        <p class="mt-4 text-sm text-slate-500">
                            Your responses remain anonymous unless you later choose to create an account.
                        </p>

                    </div>

                    {{-- Right side --}}
                    <div class="bg-slate-950 px-8 py-12 text-white md:px-10 md:py-16">

                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-yellow-400">
                            What you receive
                        </p>

                        <h2 class="mt-3 text-3xl font-bold">
                            A fast signal for better decisions.
                        </h2>

                        <div class="mt-10 space-y-6">

                            <div class="flex gap-4">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-yellow-400 font-bold text-slate-950">
                                    1
                                </span>

                                <div>
                                    <h3 class="font-bold">
                                        Simplified readiness indication
                                    </h3>

                                    <p class="mt-1 leading-6 text-slate-300">
                                        Understand your current AI maturity at a high level.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-yellow-400 font-bold text-slate-950">
                                    2
                                </span>

                                <div>
                                    <h3 class="font-bold">
                                        Key strengths and capability gaps
                                    </h3>

                                    <p class="mt-1 leading-6 text-slate-300">
                                        See where your organization appears strongest and where attention may be needed.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-yellow-400 font-bold text-slate-950">
                                    3
                                </span>

                                <div>
                                    <h3 class="font-bold">
                                        Recommended next step
                                    </h3>

                                    <p class="mt-1 leading-6 text-slate-300">
                                        Receive guidance on whether the full Digital Self-Assessment is appropriate.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="mt-12 rounded-2xl border border-slate-700 bg-slate-900 p-6">
                            <p class="text-xs font-bold uppercase tracking-wider text-yellow-400">
                                Important
                            </p>

                            <p class="mt-2 leading-7 text-slate-300">
                                This Pulse Check provides an initial signal. It is not a substitute for the full assessment, benchmarking and transformation roadmap.
                            </p>
                        </div>

                    </div>

                </div>

            </section>

        </div>

    </div>

@endsection