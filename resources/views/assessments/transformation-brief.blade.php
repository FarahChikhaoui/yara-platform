@extends('layouts.yara')

@section('content')

{{-- Floating back to assessment results --}}
<a
    href="{{ route('assessment.results', $assessment) }}"
    aria-label="Back to assessment results"
    title="Back to assessment results"
    class="fixed left-5 top-1/2 z-50 flex h-12 w-12 -translate-y-1/2
           items-center justify-center rounded-full border border-slate-200
           bg-white text-slate-500 shadow-lg transition-all duration-200
           hover:-translate-x-0.5 hover:text-slate-950 hover:shadow-xl"
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        class="h-5 w-5"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15 18l-6-6 6-6"
        />
    </svg>
</a>

<div class="mx-auto max-w-5xl">

    {{-- Page header --}}
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 px-6 py-10 text-white md:px-8">

            <div class="pointer-events-none absolute -top-20 -right-20 h-72 w-72 rounded-full bg-yellow-400/10 blur-3xl"></div>

            <div class="relative">

                <p class="text-sm font-bold uppercase tracking-[0.18em] text-yellow-400">
                    YARA Transformation
                </p>

                <h1 class="mt-3 text-3xl font-bold md:text-4xl">
                    Build Your Transformation Roadmap
                </h1>

                <p class="mt-4 max-w-3xl leading-7 text-slate-300">
                    Turn your completed AI Readiness Assessment into a prioritized,
                    expert-reviewed implementation roadmap tailored to your ambition,
                    timeline and investment capacity.
                </p>

            </div>

        </div>


        {{-- Assessment connected --}}
        <div class="border-b border-slate-200 bg-gradient-to-r from-green-50/60 to-slate-50 px-6 py-6 md:px-8">

            <div class="flex items-start gap-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 ring-4 ring-green-50">
                    <svg class="h-5 w-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div>
                    <p class="font-bold text-slate-950">
                        Your assessment is connected
                    </p>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Your existing readiness scores and assessment findings will
                        form the foundation of your Transformation Roadmap.
                        You do not need to retake the assessment.
                    </p>
                </div>

            </div>

        </div>


        {{-- Process --}}
        <div class="px-6 py-8 md:px-8">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                    How it works
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-950">
                    From readiness diagnosis to implementation
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    Tell us where you want to go and the constraints your organization
                    is working within. YARA and a Yellomind consultant will use this
                    context together with your assessment findings to prepare your
                    roadmap.
                </p>
            </div>


            <div class="mt-6 grid gap-4 md:grid-cols-3">

                <div class="group rounded-2xl border border-slate-200 bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-1 hover:border-yellow-300 hover:bg-white hover:shadow-lg hover:shadow-slate-200/60">

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-950 text-sm font-bold text-white shadow-sm">
                        1
                    </div>

                    <h3 class="mt-4 font-bold text-slate-950">
                        Define your ambition
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                            Choose your target maturity, timeline and investment capacity.

                    </p>

                </div>


                <div class="group rounded-2xl border border-slate-200 bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-1 hover:border-yellow-300 hover:bg-white hover:shadow-lg hover:shadow-slate-200/60">

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-950 text-sm font-bold text-white shadow-sm">
                        2
                    </div>

                    <h3 class="mt-4 font-bold text-slate-950">
                        Expert review
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Your assessment and transformation priorities are converted
                        into a roadmap and reviewed by a Yellomind consultant.
                    </p>

                </div>


                <div class="group rounded-2xl border border-slate-200 bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-1 hover:border-yellow-300 hover:bg-white hover:shadow-lg hover:shadow-slate-200/60">

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-yellow-400 text-sm font-bold text-slate-950 shadow-sm">
                        3
                    </div>

                    <h3 class="mt-4 font-bold text-slate-950">
                        Receive your roadmap
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Receive prioritized initiatives, implementation actions,
                        success metrics and expert guidance in a downloadable
                        final deliverable.
                    </p>

                </div>

            </div>

        </div>


        {{-- Transformation brief --}}
        <div class="border-t border-slate-200 px-6 py-8 md:px-8">

            <div class="mb-6">

                <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                    Transformation Brief
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-950">
                    Tell us what you're aiming for
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    These inputs help tailor your roadmap to realistic organizational
                    priorities and constraints.
                </p>

            </div>


            @if($errors->any())

                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">

                    <p class="font-semibold text-red-700">
                        Please review the information below.
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('assessment.roadmap.preferences', $assessment) }}"
            >
                @csrf


                {{-- Target / Budget / Timeline --}}
                <div class="grid gap-5 md:grid-cols-3">

                    {{-- Target maturity --}}
                    <div>

                        <label
                            for="target_level"
                            class="block text-sm font-semibold text-slate-900"
                        >
                            Target maturity level
                        </label>

                        <p class="mt-1 text-xs text-slate-400">
                            Current: Level {{ $currentLevelValue }}
                            @if($currentMaturity)
                                — {{ $currentMaturity->label ?? $currentMaturity->name }}
                            @endif
                        </p>

                        <select
    name="target_level"
    id="target_level"
    required
    class="mt-3 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition-colors hover:border-slate-400 focus:border-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/30"
>
    @php
        $savedTargetLevel = $preference
            ? (int) str_replace('Level ', '', $preference->target_maturity)
            : null;

        $selectedTargetLevel = old('target_level', $savedTargetLevel);
    @endphp

    <option
        value=""
        disabled
        {{ $selectedTargetLevel ? '' : 'selected' }}
    >
        Select target
    </option>

    @foreach($targetLevelOptions as $option)
        <option
            value="{{ $option->level }}"
            {{ (string) $selectedTargetLevel === (string) $option->level ? 'selected' : '' }}
        >
            Level {{ $option->level }}

            @if($option->label ?? $option->name)
                — {{ $option->label ?? $option->name }}
            @endif
        </option>
    @endforeach
</select>

                    </div>


                    {{-- Budget --}}
                    <div>

                        <label
                            for="budget"
                            class="block text-sm font-semibold text-slate-900"
                        >
                            Investment capacity
                        </label>

                        <p class="mt-1 text-xs text-slate-400">
                            Used to keep recommendations realistic.
                        </p>

                        <select
                            name="budget"
                            id="budget"
                            required
                            class="mt-3 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition-colors hover:border-slate-400 focus:border-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/30"
                        >

                            <option value="" disabled
                                {{ old('budget', $preference?->budget_level) ? '' : 'selected' }}>
                                Select range
                            </option>

                            @foreach([
                                'Under $10,000',
                                '$10,000 - $50,000',
                                '$50,000 - $200,000',
                                '$200,000+',
                                'Prefer not to say'
                            ] as $budgetOption)

                                <option
                                    value="{{ $budgetOption }}"
                                    {{ old('budget', $preference?->budget_level) === $budgetOption ? 'selected' : '' }}
                                >
                                    {{ $budgetOption }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Timeline --}}
                    <div>

                        <label
                            for="timeline"
                            class="block text-sm font-semibold text-slate-900"
                        >
                            Timeline horizon
                        </label>

                        <p class="mt-1 text-xs text-slate-400">
                            When you want meaningful progress delivered.
                        </p>

                        <select
                            name="timeline"
                            id="timeline"
                            required
                            class="mt-3 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition-colors hover:border-slate-400 focus:border-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/30"
                        >

                            <option value="" disabled
                                {{ old('timeline', $preference?->timeframe) ? '' : 'selected' }}>
                                Select timeline
                            </option>

                            @foreach([
                                '3 months',
                                '6 months',
                                '12 months',
                                '18-24 months'
                            ] as $timelineOption)

                                <option
                                    value="{{ $timelineOption }}"
                                    {{ old('timeline', $preference?->timeframe) === $timelineOption ? 'selected' : '' }}
                                >
                                    {{ $timelineOption }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- CTA --}}
                <div class="mt-8 flex flex-col gap-4 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">

                    <p class="max-w-xl text-sm leading-6 text-slate-500">
                        You'll review your Transformation engagement before completing
                        payment. Your roadmap is prepared after successful payment.
                    </p>

                    <button
                        type="submit"
                        class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-950 px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-slate-950/10 transition-all duration-200 hover:-translate-y-0.5 hover:bg-yellow-400 hover:text-slate-950 hover:shadow-xl hover:shadow-yellow-400/20"
                    >
                        Continue to Payment

                        <span class="inline-block transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">
                            →
                        </span>
                    </button>

                </div>

            </form>

        </div>

    </section>

</div>

@endsection