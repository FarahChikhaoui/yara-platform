@extends('layouts.yara')

@section('content')

@php
    $currentScore = (float) $currentAssessment->company_score;
    $previousScore = (float) $previousAssessment->company_score;

    $improvements = $dimensionComparison
        ->filter(fn ($item) => $item['change'] !== null && $item['change'] > 0)
        ->sortByDesc('change');

    $declines = $dimensionComparison
        ->filter(fn ($item) => $item['change'] !== null && $item['change'] < 0)
        ->sortBy('change');

    $stable = $dimensionComparison
        ->filter(fn ($item) => $item['change'] !== null && abs($item['change']) < 0.05);

    $biggestImprovement = $improvements->first();
    $biggestDecline = $declines->first();

    $previousDate = optional($previousAssessment->updated_at)->format('d M Y');
    $currentDate = optional($currentAssessment->updated_at)->format('d M Y');
@endphp


{{-- =========================================================
     FLOATING BACK BUTTON
========================================================== --}}
<a
    href="{{ route('dashboard') }}"
    aria-label="Dashboard"
   title="Back to dashboard"
   class="fixed left-5 top-1/2 z-50 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-lg shadow-slate-900/10 transition-all duration-200 hover:-translate-x-1 hover:scale-105 hover:border-yellow-300 hover:text-yellow-600 hover:shadow-xl print:hidden">

    <svg class="h-5 w-5"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M15 19l-7-7 7-7"/>
    </svg>
</a>



<div id="comparison-content" class="space-y-7">
    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <section
        class="relative overflow-hidden rounded-3xl border border-slate-200/70
               bg-white
               shadow-[0_1px_2px_rgba(15,23,42,0.04),0_24px_48px_-32px_rgba(15,23,42,0.25)]"
    >

        <div
            class="border-b border-slate-200/80
                   bg-gradient-to-r from-slate-50 via-white to-slate-50
                   px-6 py-4 md:px-8"
        >

            <div class="flex flex-wrap items-center gap-3">

                <span
                    class="inline-flex items-center gap-2 rounded-full
                           bg-gradient-to-r from-yellow-100 to-amber-100
                           px-3 py-1 text-xs font-bold uppercase tracking-wide
                           text-amber-700 ring-1 ring-inset ring-amber-300/60"
                >
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                    Progress Intelligence
                </span>

                <span class="text-sm text-slate-400">
                    {{ $completedAssessments->count() }} completed assessments
                </span>

            </div>

        </div>


        <div class="relative px-6 py-8 md:px-8 md:py-9">

            <div
                class="pointer-events-none absolute -right-24 -top-24
                       h-64 w-64 rounded-full bg-yellow-300/10 blur-3xl"
            ></div>

            <div
                class="pointer-events-none absolute -bottom-20 left-1/3
                       h-48 w-48 rounded-full bg-amber-200/10 blur-3xl"
            ></div>

            <div class="relative">

                <p
                    class="flex items-center gap-2 text-sm font-semibold uppercase
                           tracking-[0.18em] text-yellow-600"
                >
                    <span
                        class="h-px w-6
                               bg-gradient-to-r from-yellow-500 to-transparent"
                    ></span>

                    AI Readiness Progress
                </p>

                <h1
                    class="mt-3 text-3xl font-bold tracking-tight
                           text-slate-950 md:text-4xl"
                >
                    Assessment Comparison
                </h1>

                <p class="mt-3 max-w-3xl text-base leading-7 text-slate-500">
                    See how organizational AI readiness has evolved between
                    your two most recent completed assessments.
                </p>

            </div>

        </div>

    </section>

{{-- =========================================================
     ASSESSMENT SELECTOR
========================================================== --}}
@if($completedAssessments->count() > 2)

    <section
        class="relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white
               px-6 py-5
               shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-26px_rgba(15,23,42,0.2)]"
    >

        <form
    id="comparison-form"
    method="GET"
    action="{{ route('assessment.comparison') }}"
    class="flex flex-col gap-5
           lg:flex-row lg:items-end lg:justify-between"
>

            <div>

                <p
                    class="text-xs font-bold uppercase tracking-[0.16em]
                           text-slate-400"
                >
                    Comparison Period
                </p>

                <h2 class="mt-1 text-lg font-bold tracking-tight text-slate-950">
                    Choose assessments to compare
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Compare your latest progress or explore change across
                    an earlier period.
                </p>

            </div>


            <div
                class="flex flex-col gap-3
                       sm:flex-row sm:items-end"
            >

                {{-- Previous --}}
                <div class="min-w-[210px]">

                    <label
                        for="previous"
                        class="mb-1.5 block text-xs font-bold uppercase
                               tracking-wide text-slate-400"
                    >
                        From
                    </label>

                    <select
                        id="previous"
                        name="previous"
                        class="h-11 w-full rounded-xl border border-slate-200
                               bg-slate-50 px-3
                               text-sm font-semibold text-slate-700
                               shadow-sm outline-none transition
                               focus:border-amber-400 focus:bg-white
                               focus:ring-2 focus:ring-amber-100"
                    >

                        @foreach($completedAssessments as $assessment)

                            <option
                                value="{{ $assessment->id }}"
                                @selected($assessment->id === $previousAssessment->id)
                            >
                                {{ optional($assessment->updated_at)->format('d M Y') }}
                                · {{ number_format((float) $assessment->company_score, 1) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Direction --}}
                <div
                    class="hidden h-11 items-center justify-center
                           px-1 text-slate-300 sm:flex"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 12h14m-4-4 4 4-4 4"
                        />
                    </svg>
                </div>


                {{-- Current --}}
                <div class="min-w-[210px]">

                    <label
                        for="current"
                        class="mb-1.5 block text-xs font-bold uppercase
                               tracking-wide text-slate-400"
                    >
                        To
                    </label>

                    <select
                        id="current"
                        name="current"
                        class="h-11 w-full rounded-xl border border-slate-200
                               bg-slate-50 px-3
                               text-sm font-semibold text-slate-700
                               shadow-sm outline-none transition
                               focus:border-amber-400 focus:bg-white
                               focus:ring-2 focus:ring-amber-100"
                    >

                        @foreach($completedAssessments as $assessment)

                            <option
                                value="{{ $assessment->id }}"
                                @selected($assessment->id === $currentAssessment->id)
                            >
                                {{ optional($assessment->updated_at)->format('d M Y') }}
                                · {{ number_format((float) $assessment->company_score, 1) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Compare --}}
               <button
    id="comparison-submit"
    type="submit"
    class="group inline-flex h-11 min-w-[105px] items-center justify-center
           gap-2 rounded-xl bg-gradient-to-r from-slate-950 to-slate-800 px-5
           text-sm font-bold text-white
           shadow-md shadow-slate-900/20 transition-all duration-200
           hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/25
           disabled:cursor-wait disabled:opacity-70 disabled:hover:translate-y-0"
>
    <svg
        id="comparison-spinner"
        class="hidden h-4 w-4 animate-spin"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
    >
        <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
        ></circle>

        <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
        ></path>
    </svg>

    <span id="comparison-submit-text">
        Compare
    </span>
</button>

            </div>

        </form>

    </section>

@endif
{{-- =========================================================
     FRAMEWORK VERSION NOTICE
========================================================== --}}
@if($frameworkVersionsDiffer)

    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-5">

        <div class="flex items-start gap-4">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-sm font-bold text-white">
                !
            </div>

            <div>
                <p class="font-bold text-amber-900">
                    Framework versions differ
                </p>

                <p class="mt-1 text-sm leading-6 text-amber-800">
                    These assessments were completed using different versions of the
                    YARA AI Readiness Framework. Score changes should therefore be
                    interpreted with caution.
                </p>

                <div class="mt-3 flex items-center gap-2 text-xs font-bold text-amber-700">

                    <span class="rounded-lg bg-white/70 px-2.5 py-1 ring-1 ring-amber-200">
                        Framework v{{ $previousAssessment->framework_version }}
                    </span>

                    <span>→</span>

                    <span class="rounded-lg bg-white/70 px-2.5 py-1 ring-1 ring-amber-200">
                        Framework v{{ $currentAssessment->framework_version }}
                    </span>

                </div>

            </div>

        </div>

    </div>

@endif
    {{-- =========================================================
         OVERALL MOVEMENT
    ========================================================== --}}
    <section
        class="relative overflow-hidden rounded-3xl bg-slate-950 text-white
               shadow-[0_24px_60px_-24px_rgba(2,6,23,0.55)]
               ring-1 ring-white/5"
    >

        {{-- Background texture --}}
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.35]"
            style="
                background-image:
                    radial-gradient(
                        circle at 1px 1px,
                        rgba(255,255,255,0.06) 1px,
                        transparent 0
                    );
                background-size: 22px 22px;
            "
        ></div>

        <div
            class="pointer-events-none absolute -left-28 -top-28
                   h-80 w-80 rounded-full bg-yellow-400/10 blur-3xl"
        ></div>

        <div
            class="pointer-events-none absolute -bottom-24 -right-16
                   h-64 w-64 rounded-full bg-amber-500/10 blur-3xl"
        ></div>


        <div class="relative px-7 py-7 md:px-9 md:py-8">

            {{-- Section heading --}}
            <div
                class="flex flex-col gap-2
                       lg:flex-row lg:items-end lg:justify-between"
            >

                <div>

                    <p
                        class="text-xs font-bold uppercase tracking-[0.18em]
                               text-yellow-400"
                    >
                        Overall Movement
                    </p>

                    <h2 class="mt-2 text-2xl font-bold tracking-tight">
                        Organizational AI Readiness
                    </h2>

                </div>

                <p class="text-xs text-slate-500">
                    Organization score only · Country benchmark excluded
                </p>

            </div>


            {{-- Main horizontal comparison --}}
            <div
                class="mt-7 grid grid-cols-1
                       divide-y divide-slate-800
                       md:grid-cols-[1fr_220px_1fr]
                       md:divide-x md:divide-y-0"
            >

                {{-- Previous --}}
                <div class="py-5 md:pr-8">

                    <p
                        class="text-xs font-semibold uppercase tracking-wide
                               text-slate-500"
                    >
                        Previous Assessment
                    </p>

                    <div class="mt-3 flex items-end gap-2">

                        <span class="text-4xl font-bold tracking-tight text-white">
                            {{ number_format($previousScore, 1) }}
                        </span>

                        <span class="mb-1 text-sm text-slate-500">
                            /100
                        </span>

                    </div>

                    <div class="mt-3 h-1.5 w-full max-w-[220px] overflow-hidden rounded-full bg-slate-800">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-slate-400 to-slate-300 transition-all duration-700 ease-out"
                            style="width: {{ min(100, max(0, $previousScore)) }}%;"
                        ></div>
                    </div>


                    <div
                        class="mt-5 flex flex-wrap items-center
                               gap-x-3 gap-y-1 text-sm"
                    >

                        <span class="font-semibold text-slate-300">
                            {{ $previousDate }}
                        </span>

                        <span class="text-slate-700">•</span>

                        <span class="text-slate-500">
                            Assessment #{{ $previousAssessment->id }}
                        </span>

                    </div>

                </div>


                {{-- Change --}}
                <div
                    class="flex flex-col items-center justify-center
                           py-6 text-center md:px-8"
                >

                    <p
                        class="text-xs font-semibold uppercase tracking-wide
                               text-slate-500"
                    >
                        Net Change
                    </p>


                    @if($overallChange > 0)

                        <div class="mt-2 flex items-center gap-2 text-green-400">

                            <span class="text-xl">↑</span>

                            <span class="text-4xl font-bold tracking-tight drop-shadow-[0_0_16px_rgba(74,222,128,0.25)]">
                                +{{ number_format($overallChange, 1) }}
                            </span>

                        </div>

                        <span
                            class="mt-3 inline-flex rounded-full
                                   border border-green-500/20
                                   bg-green-500/10 px-3 py-1
                                   text-xs font-bold text-green-400"
                        >
                            Improvement
                        </span>


                    @elseif($overallChange < 0)

                        <div class="mt-2 flex items-center gap-2 text-red-400">

                            <span class="text-xl">↓</span>

                            <span class="text-4xl font-bold tracking-tight drop-shadow-[0_0_16px_rgba(248,113,113,0.25)]">
                                {{ number_format($overallChange, 1) }}
                            </span>

                        </div>

                        <span
                            class="mt-3 inline-flex rounded-full
                                   border border-red-500/20
                                   bg-red-500/10 px-3 py-1
                                   text-xs font-bold text-red-400"
                        >
                            Decline
                        </span>


                    @else

                        <div class="mt-2 text-4xl font-bold text-slate-300">
                            0.0
                        </div>

                        <span
                            class="mt-3 inline-flex rounded-full
                                   border border-slate-700
                                   bg-slate-800 px-3 py-1
                                   text-xs font-bold text-slate-400"
                        >
                            Stable
                        </span>

                    @endif

                </div>


                {{-- Current --}}
                <div class="relative py-5 md:pl-8">

                    <div class="flex items-center justify-between gap-4">

                        <p
                            class="text-xs font-semibold uppercase tracking-wide
                                   text-yellow-400"
                        >
                            Latest Assessment
                        </p>

                        <span
                            class="rounded-full bg-gradient-to-r from-yellow-400 to-amber-400
                                   px-2.5 py-1
                                   text-[10px] font-bold uppercase tracking-wide
                                   text-slate-950 shadow-sm"
                        >
                            Current
                        </span>

                    </div>


                    <div class="mt-3 flex items-end gap-2">

                        <span class="text-4xl font-bold tracking-tight text-white">
                            {{ number_format($currentScore, 1) }}
                        </span>

                        <span class="mb-1 text-sm text-slate-500">
                            /100
                        </span>

                    </div>

                    <div class="mt-3 h-1.5 w-full max-w-[220px] overflow-hidden rounded-full bg-slate-800">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-yellow-400 to-amber-400 shadow-[0_0_8px_rgba(250,204,21,0.5)] transition-all duration-700 ease-out"
                            style="width: {{ min(100, max(0, $currentScore)) }}%;"
                        ></div>
                    </div>


                    <div
                        class="mt-5 flex flex-wrap items-center
                               gap-x-3 gap-y-1 text-sm"
                    >

                        <span class="font-semibold text-slate-300">
                            {{ $currentDate }}
                        </span>

                        <span class="text-slate-700">•</span>

                        <span class="text-slate-500">
                            Assessment #{{ $currentAssessment->id }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
         PROGRESS SNAPSHOT
    ========================================================== --}}
    <section class="grid grid-cols-1 gap-5 md:grid-cols-3">

        {{-- Improved --}}
        <div
            class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white p-6
                   shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_28px_48px_-28px_rgba(15,23,42,0.3)]"
        >

            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-green-300/0 blur-2xl transition group-hover:bg-green-300/15"></div>

            <div class="relative flex items-start justify-between gap-4">

                <div>

                    <p
                        class="text-xs font-bold uppercase tracking-[0.16em]
                               text-green-700"
                    >
                        Improved Capabilities
                    </p>

                    <div class="mt-3 flex items-end gap-2">

                        <span
                            class="text-4xl font-bold tracking-tight
                                   text-slate-950"
                        >
                            {{ $improvements->count() }}
                        </span>

                        <span class="mb-1 text-sm font-medium text-slate-400">
                            {{ $improvements->count() === 1 ? 'dimension' : 'dimensions' }}
                        </span>

                    </div>

                </div>


                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center
                           rounded-2xl bg-green-50 text-green-600 transition group-hover:bg-green-500 group-hover:text-white"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 16l5-5 4 4 7-8"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 7h5v5"
                        />
                    </svg>
                </div>

            </div>


            <div class="relative mt-5 border-t border-slate-100 pt-4">

                @if($biggestImprovement)

                    <p
                        class="text-xs font-semibold uppercase tracking-wide
                               text-slate-400"
                    >
                        Biggest improvement
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $biggestImprovement['dimension'] }}
                    </p>

                    <p class="mt-1 text-sm font-bold text-green-600">
                        +{{ number_format($biggestImprovement['change'], 1) }} points
                    </p>

                @else

                    <p class="text-sm leading-6 text-slate-500">
                        No capabilities improved between these assessments.
                    </p>

                @endif

            </div>

        </div>



        {{-- Declined --}}
        <div
            class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white p-6
                   shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_28px_48px_-28px_rgba(15,23,42,0.3)]"
        >

            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-red-300/0 blur-2xl transition group-hover:bg-red-300/15"></div>

            <div class="relative flex items-start justify-between gap-4">

                <div>

                    <p
                        class="text-xs font-bold uppercase tracking-[0.16em]
                               text-red-700"
                    >
                        Declined Capabilities
                    </p>

                    <div class="mt-3 flex items-end gap-2">

                        <span
                            class="text-4xl font-bold tracking-tight
                                   text-slate-950"
                        >
                            {{ $declines->count() }}
                        </span>

                        <span class="mb-1 text-sm font-medium text-slate-400">
                            {{ $declines->count() === 1 ? 'dimension' : 'dimensions' }}
                        </span>

                    </div>

                </div>


                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center
                           rounded-2xl bg-red-50 text-red-600 transition group-hover:bg-red-500 group-hover:text-white"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 8l5 5 4-4 7 8"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 17h5v-5"
                        />
                    </svg>
                </div>

            </div>


            <div class="relative mt-5 border-t border-slate-100 pt-4">

                @if($biggestDecline)

                    <p
                        class="text-xs font-semibold uppercase tracking-wide
                               text-slate-400"
                    >
                        Largest decline
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $biggestDecline['dimension'] }}
                    </p>

                    <p class="mt-1 text-sm font-bold text-red-600">
                        {{ number_format($biggestDecline['change'], 1) }} points
                    </p>

                @else

                    <p class="text-sm leading-6 text-slate-500">
                        No capabilities declined between these assessments.
                    </p>

                @endif

            </div>

        </div>



        {{-- Stable --}}
        <div
            class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white p-6
                   shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_28px_48px_-28px_rgba(15,23,42,0.3)]"
        >

            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-slate-300/0 blur-2xl transition group-hover:bg-slate-300/20"></div>

            <div class="relative flex items-start justify-between gap-4">

                <div>

                    <p
                        class="text-xs font-bold uppercase tracking-[0.16em]
                               text-slate-500"
                    >
                        Stable Capabilities
                    </p>

                    <div class="mt-3 flex items-end gap-2">

                        <span
                            class="text-4xl font-bold tracking-tight
                                   text-slate-950"
                        >
                            {{ $stable->count() }}
                        </span>

                        <span class="mb-1 text-sm font-medium text-slate-400">
                            {{ $stable->count() === 1 ? 'dimension' : 'dimensions' }}
                        </span>

                    </div>

                </div>


                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center
                           rounded-2xl bg-slate-100 text-slate-500 transition group-hover:bg-slate-950 group-hover:text-white"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 12h12"
                        />
                    </svg>
                </div>

            </div>


            <div class="relative mt-5 border-t border-slate-100 pt-4">

                <p class="text-sm leading-6 text-slate-500">
                    Capabilities with no measurable score movement
                    between assessments.
                </p>

            </div>

        </div>

    </section>



   {{-- =========================================================
     CAPABILITY MOVEMENT
========================================================== --}}
<section
    class="overflow-hidden rounded-3xl border border-slate-200/70
           bg-white
           shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]"
>

    {{-- Header --}}
    <div
        class="border-b border-slate-200/80
               bg-slate-50/60 px-7 py-6 md:px-8"
    >
        <div
            class="flex flex-col gap-3
                   sm:flex-row sm:items-end sm:justify-between"
        >

            <div>
                <p
                    class="text-xs font-bold uppercase tracking-[0.18em]
                           text-yellow-600"
                >
                    Capability Movement
                </p>

                <h2
                    class="mt-2 text-2xl font-bold tracking-tight
                           text-slate-950"
                >
                    Where readiness changed
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Focus on capabilities that moved between the two assessments.
                </p>
            </div>

            <div
                class="inline-flex w-fit items-center gap-2 rounded-full
                       bg-slate-100 px-4 py-2
                       text-sm font-semibold text-slate-600
                       ring-1 ring-inset ring-slate-200"
            >
                <span class="h-2 w-2 rounded-full bg-yellow-400"></span>

                {{ $dimensionComparison->count() }} capabilities
            </div>

        </div>
    </div>


    {{-- Main movement groups --}}
    <div class="grid grid-cols-1 lg:grid-cols-2">

        {{-- =====================================================
             IMPROVING
        ====================================================== --}}
        <div
            class="border-b border-slate-200
                   p-7 lg:border-b-0 lg:border-r lg:p-8"
        >

            <div class="flex items-center justify-between gap-4">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 items-center justify-center
                               rounded-xl bg-green-50 text-green-600"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 15l4-4 3 3 7-7"
                            />
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-950">
                            Improving
                        </h3>

                        <p class="text-xs text-slate-400">
                            Positive capability movement
                        </p>
                    </div>

                </div>

                <span
                    class="rounded-full bg-green-50 px-2.5 py-1
                           text-xs font-bold text-green-700"
                >
                    {{ $improvements->count() }}
                </span>

            </div>


            <div class="mt-6 space-y-3">

                @forelse($improvements as $item)

                    <div
                        class="group rounded-2xl border border-slate-100
                               bg-slate-50/60 px-4 py-4 transition-all duration-200
                               hover:-translate-y-0.5 hover:border-green-200 hover:bg-green-50/40 hover:shadow-sm"
                    >

                        <div
                            class="flex flex-col gap-3
                                   sm:flex-row sm:items-center sm:justify-between"
                        >

                            <div class="min-w-0">

                                <p class="font-semibold text-slate-900">
                                    {{ $item['dimension'] }}
                                </p>

                                <div
                                    class="mt-1.5 flex items-center gap-2
                                           text-sm"
                                >

                                    <span class="font-semibold text-slate-400">
                                        {{ number_format($item['previous_score'], 1) }}
                                    </span>

                                    <span class="text-slate-300">
                                        →
                                    </span>

                                    <span class="font-bold text-slate-900">
                                        {{ number_format($item['current_score'], 1) }}
                                    </span>

                                </div>

                            </div>


                            <span
                                class="inline-flex w-fit items-center
                                       rounded-full bg-green-100
                                       px-3 py-1.5
                                       text-sm font-bold text-green-700"
                            >
                                ↑&nbsp;+{{ number_format($item['change'], 1) }}
                            </span>

                        </div>

                        {{-- Visual movement bar: filled to current score, marker at previous score --}}
                        <div class="relative mt-3 h-1.5 rounded-full bg-slate-200">

                            <div
                                class="h-full rounded-full bg-gradient-to-r from-green-500 to-green-400 transition-all duration-700 ease-out"
                                style="width: {{ min(100, max(0, $item['current_score'])) }}%;"
                            ></div>

                            <span
                                class="absolute top-1/2 h-3 w-0.5 -translate-y-1/2 rounded-full bg-slate-500/70"
                                style="left: {{ min(100, max(0, $item['previous_score'])) }}%;"
                                title="Previous score"
                            ></span>

                        </div>

                    </div>

                @empty

                    <div
                        class="rounded-2xl border border-dashed border-slate-200
                               px-5 py-7 text-center"
                    >
                        <p class="text-sm text-slate-400">
                            No capabilities improved during this period.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>



        {{-- =====================================================
             NEEDS ATTENTION
        ====================================================== --}}
        <div class="p-7 lg:p-8">

            <div class="flex items-center justify-between gap-4">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 items-center justify-center
                               rounded-xl bg-red-50 text-red-600"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 9l4 4 3-3 7 7"
                            />
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-950">
                            Needs Attention
                        </h3>

                        <p class="text-xs text-slate-400">
                            Capabilities that declined
                        </p>
                    </div>

                </div>

                <span
                    class="rounded-full bg-red-50 px-2.5 py-1
                           text-xs font-bold text-red-700"
                >
                    {{ $declines->count() }}
                </span>

            </div>


            <div class="mt-6 space-y-3">

                @forelse($declines as $item)

                    <div
                        class="group rounded-2xl border border-slate-100
                               bg-slate-50/60 px-4 py-4 transition-all duration-200
                               hover:-translate-y-0.5 hover:border-red-200 hover:bg-red-50/30 hover:shadow-sm"
                    >

                        <div
                            class="flex flex-col gap-3
                                   sm:flex-row sm:items-center sm:justify-between"
                        >

                            <div class="min-w-0">

                                <p class="font-semibold text-slate-900">
                                    {{ $item['dimension'] }}
                                </p>

                                <div
                                    class="mt-1.5 flex items-center gap-2
                                           text-sm"
                                >

                                    <span class="font-semibold text-slate-400">
                                        {{ number_format($item['previous_score'], 1) }}
                                    </span>

                                    <span class="text-slate-300">
                                        →
                                    </span>

                                    <span class="font-bold text-slate-900">
                                        {{ number_format($item['current_score'], 1) }}
                                    </span>

                                </div>

                            </div>


                            <span
                                class="inline-flex w-fit items-center
                                       rounded-full bg-red-100
                                       px-3 py-1.5
                                       text-sm font-bold text-red-700"
                            >
                                ↓&nbsp;{{ number_format($item['change'], 1) }}
                            </span>

                        </div>

                        {{-- Visual movement bar: filled to current score, marker at previous score --}}
                        <div class="relative mt-3 h-1.5 rounded-full bg-slate-200">

                            <div
                                class="h-full rounded-full bg-gradient-to-r from-red-500 to-red-400 transition-all duration-700 ease-out"
                                style="width: {{ min(100, max(0, $item['current_score'])) }}%;"
                            ></div>

                            <span
                                class="absolute top-1/2 h-3 w-0.5 -translate-y-1/2 rounded-full bg-slate-500/70"
                                style="left: {{ min(100, max(0, $item['previous_score'])) }}%;"
                                title="Previous score"
                            ></span>

                        </div>

                    </div>

                @empty

                    <div
                        class="rounded-2xl border border-dashed border-slate-200
                               px-5 py-7 text-center"
                    >
                        <p class="text-sm text-slate-400">
                            No capabilities declined during this period.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>

    </div>



    {{-- =========================================================
         STABLE — COMPACT FOOTER
    ========================================================== --}}
    @if($stable->count() > 0)

        <div
            class="border-t border-slate-200
                   bg-slate-50/60 px-7 py-5 md:px-8"
        >

            <div
                class="flex flex-col gap-3
                       lg:flex-row lg:items-center"
            >

                <div class="flex shrink-0 items-center gap-2">

                    <span
                        class="flex h-7 w-7 items-center justify-center
                               rounded-lg bg-slate-200/70
                               text-sm font-bold text-slate-500"
                    >
                        —
                    </span>

                    <span
                        class="text-xs font-bold uppercase tracking-wide
                               text-slate-500"
                    >
                        Stable
                    </span>

                </div>


                <div class="flex flex-wrap gap-2">

                    @foreach($stable as $item)

                        <span
                            class="inline-flex items-center gap-2
                                   rounded-full border border-slate-200
                                   bg-white px-3 py-1.5
                                   text-xs font-semibold text-slate-600
                                   shadow-sm transition hover:border-slate-300"
                        >
                            {{ $item['dimension'] }}

                            <span class="text-slate-300">
                                {{ number_format($item['current_score'], 1) }}
                            </span>
                        </span>

                    @endforeach

                </div>

            </div>

        </div>

    @endif

</section>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const content = document.getElementById('comparison-content');

    if (!content) {
        return;
    }


    /**
     * Attach AJAX behavior to the comparison form.
     *
     * We call this again after replacing the comparison content
     * because the form itself is part of the replaced HTML.
     */
    function bindComparisonForm() {

        const form = document.getElementById('comparison-form');

        if (!form) {
            return;
        }

        form.addEventListener('submit', async (event) => {

            event.preventDefault();

            const previousSelect =
                form.querySelector('[name="previous"]');

            const currentSelect =
                form.querySelector('[name="current"]');

            const button =
                document.getElementById('comparison-submit');

            const spinner =
                document.getElementById('comparison-spinner');

            const buttonText =
                document.getElementById('comparison-submit-text');


            if (!previousSelect || !currentSelect) {
                return;
            }


            /**
             * Don't allow the exact same assessment
             * to be compared against itself.
             */
            if (previousSelect.value === currentSelect.value) {

                previousSelect.focus();

                return;
            }


            const params = new URLSearchParams({
                previous: previousSelect.value,
                current: currentSelect.value,
            });


            const url =
                `${form.action}?${params.toString()}`;


            /**
             * Loading state
             */
            if (button) {
                button.disabled = true;
            }

            if (spinner) {
                spinner.classList.remove('hidden');
            }

            if (buttonText) {
                buttonText.textContent = 'Comparing';
            }


            try {

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                });


                if (!response.ok) {
                    throw new Error(
                        `Comparison request failed: ${response.status}`
                    );
                }


                const html = await response.text();

                const parser = new DOMParser();

                const documentResponse =
                    parser.parseFromString(html, 'text/html');

                const newContent =
                    documentResponse.getElementById(
                        'comparison-content'
                    );


                if (!newContent) {
                    throw new Error(
                        'Comparison content was not found.'
                    );
                }


                /**
                 * Replace only the comparison page content.
                 *
                 * Sidebar/layout/floating back button remain untouched.
                 */
                content.innerHTML = newContent.innerHTML;


                /**
                 * Keep the selected comparison in the browser URL
                 * without reloading the page.
                 */
                window.history.pushState(
                    {
                        comparisonUrl: url
                    },
                    '',
                    url
                );


                /**
                 * The form was replaced with new HTML,
                 * therefore its event listener must be attached again.
                 */
                bindComparisonForm();


                /**
                 * Bring the user gently back toward the comparison
                 * summary without jumping to the very top of the app.
                 */
                content.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });

            } catch (error) {

                console.error(error);

                /**
                 * Safe fallback:
                 * if AJAX ever fails, the normal Laravel page
                 * still works.
                 */
                window.location.href = url;

            }

        });

    }


    bindComparisonForm();



    /**
     * Browser Back / Forward support.
     *
     * Example:
     * A vs B
     * A vs D
     * B vs D
     *
     * Back returns to the previous comparison without
     * performing a full browser reload.
     */
    window.addEventListener('popstate', async () => {

        try {

            const response = await fetch(window.location.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            });


            if (!response.ok) {
                throw new Error('Unable to restore comparison.');
            }


            const html = await response.text();

            const parser = new DOMParser();

            const documentResponse =
                parser.parseFromString(html, 'text/html');

            const newContent =
                documentResponse.getElementById(
                    'comparison-content'
                );


            if (!newContent) {
                throw new Error(
                    'Comparison content was not found.'
                );
            }


            content.innerHTML = newContent.innerHTML;

            bindComparisonForm();

        } catch (error) {

            console.error(error);

            window.location.reload();

        }

    });

});
</script>
@endsection