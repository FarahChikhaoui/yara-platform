@extends('layouts.yara')
@section('content')
<div id="page-top"></div>

{{-- Slim scroll-progress indicator, matches the yellow/amber accent --}}
<div id="scroll-progress" class="fixed left-0 top-0 z-[60] h-1 w-0 bg-gradient-to-r from-yellow-400 to-amber-500 transition-[width] duration-150 ease-out print:hidden"></div>

@php
    $organizationScore = (float) ($assessment->company_score ?? $averageScore);
    $combinedScore = (float) ($assessment->combined_score ?? $averageScore);

    $countryScore = $assessment->country_ai_score !== null
        ? (float) $assessment->country_ai_score
        : null;

    $scoreValues = $dimensionScores->values();

    $maximumDimensionScore = $scoreValues->count()
        ? (float) $scoreValues->max()
        : 0;

    $minimumDimensionScore = $scoreValues->count()
        ? (float) $scoreValues->min()
        : 0;

    $maturitySpread = $maximumDimensionScore - $minimumDimensionScore;

    if ($maturitySpread <= 15) {
        $balanceLabel = 'Balanced';
        $balanceDescription = 'Maturity is relatively consistent across assessed dimensions.';
        $balanceBadgeClass = 'bg-green-100 text-green-700';
    } elseif ($maturitySpread <= 30) {
        $balanceLabel = 'Moderately uneven';
        $balanceDescription = 'Some capability gaps exist between assessed dimensions.';
        $balanceBadgeClass = 'bg-yellow-100 text-yellow-700';
    } else {
        $balanceLabel = 'Highly uneven';
        $balanceDescription = 'Significant maturity gaps exist across assessed capabilities.';
        $balanceBadgeClass = 'bg-red-100 text-red-700';
    }

    $definedCapabilityCount = $dimensionMaturityLevels
        ->filter(function ($level) {
            return $level && $level->level >= 3;
        })
        ->count();

    $assessedDimensionTotal = $dimensionScores->count();

    if (($lowestDimensionScore ?? 0) < 20) {
        $riskLevel = 'Critical';
        $riskBadgeClass = 'bg-red-100 text-red-700';
    } elseif (($lowestDimensionScore ?? 0) < 40) {
        $riskLevel = 'High';
        $riskBadgeClass = 'bg-orange-100 text-orange-700';
    } elseif (($lowestDimensionScore ?? 0) < 60) {
        $riskLevel = 'Moderate';
        $riskBadgeClass = 'bg-yellow-100 text-yellow-700';
    } else {
        $riskLevel = 'Controlled';
        $riskBadgeClass = 'bg-green-100 text-green-700';
    }

    $scoreAngle = max(0, min(100, $combinedScore)) * 3.6;

    $rankedDimensionScores = $dimensionScores->sortDesc();

    $coverageIncomplete = isset($assessedDimensionCount, $totalDimensionCount)
        && $assessedDimensionCount < $totalDimensionCount;

    // ------------------------------------------------------------------
    // AI-generated, questionnaire-driven roadmap
    // ------------------------------------------------------------------
    // Expected shape of $assessment->ai_roadmap (json):
    // {
    //   "summary": "1-2 sentence overview of the roadmap strategy",
    //   "actions": [
    //     {
    //       "priority_rank": 1,
    //       "title": "...",
    //       "dimension": "Data Infrastructure",
    //       "description": "...",
    //       "business_rationale": "...",
    //       "business_impact": "Critical|High|Medium|Low",
    //       "effort": "Low|Medium|High",
    //       "timeline": "3-6 months",
    //       "investment": "$10,000 - $30,000",
    //       "standard_reference": "ISO/IEC 42001"
    //     }
    //   ]
    // }
    //
    // Expected shape of $assessment->roadmap_inputs (json):
    // {
    //   "target_level": 4,
    //   "target_level_label": "Level 4 — Optimized",
    //   "budget": "$10,000 - $50,000",
    //   "timeline": "6-12 months",
    //   "focus_areas": ["Data Governance", "Change Management"]
    // }

    $roadmapData = null;

    if (!empty($assessment->ai_roadmap)) {
        $decodedRoadmap = json_decode($assessment->ai_roadmap, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedRoadmap)) {
            $roadmapData = $decodedRoadmap;
        }
    }

    $roadmapActions = collect($roadmapData['actions'] ?? []);
    $roadmapActionCount = $roadmapActions->count();

    $roadmapInputs = null;

    if (!empty($assessment->roadmap_inputs)) {
        $decodedInputs = json_decode($assessment->roadmap_inputs, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedInputs)) {
            $roadmapInputs = $decodedInputs;
        }
    }

    $roadmapGeneratedAt = $assessment->roadmap_generated_at ?? null;

    $roadmapIsStale = $roadmapGeneratedAt
        && $assessment->updated_at
        && $assessment->updated_at->greaterThan($roadmapGeneratedAt);

    // Maturity levels above the organization's current level — these are
    // the only valid "target level" choices in the planning form, since
    // you can't set a target below (or at) where you already are.
    $currentLevelValue = $maturityLevel->level ?? 0;

    $targetLevelOptions = collect($allMaturityLevels ?? [])
        ->filter(fn ($level) => $level->level > $currentLevelValue)
        ->values();

    if ($targetLevelOptions->isEmpty()) {
        // Fallback if the controller hasn't passed the full level list —
        // assumes a standard 5-level maturity scale.
        $targetLevelOptions = collect(range($currentLevelValue + 1, 5))
            ->map(fn ($lvl) => (object) ['level' => $lvl, 'label' => null, 'name' => null]);
    }

    // Candidate capabilities for the optional "focus areas" question —
    // weakest first, since those are most likely to need prioritizing.
    $focusAreaOptions = $dimensionScores->sortBy(fn ($score) => $score)->keys()->values();

    // Form should re-open automatically after a failed submission (either
    // validation errors, or a roadmap_error flash from the AI call) so the
    // user's corrected inputs are visible instead of the form collapsing
    // back behind "Edit inputs & regenerate".
    $roadmapFormHasIssue = $errors->any() || session('roadmap_error');

    @endphp

<div class="space-y-8">

      {{-- Executive report heading --}}
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 md:px-8">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex flex-wrap items-center gap-3">

                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-yellow-700">
                        YARA Executive Report
                    </span>

                    <span class="text-sm text-slate-400">
                        Assessment ID #{{ $assessment->id }}
                    </span>

                </div>

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex w-fit items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-100"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        class="h-4 w-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v7H6v-7Z"
                        />
                    </svg>

                    Print Report
                </button>

            </div>

        </div>

        <div class="px-6 py-8 md:px-8 md:py-10">

            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">

               <div class="max-w-4xl">

    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-yellow-600">
        Executive AI Readiness Report
    </p>

    <h1 class="mt-3 text-3xl font-bold leading-tight text-slate-950 md:text-4xl">
        {{ $assessment->title }}
    </h1>

    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-500">
    Executive assessment of organizational AI maturity, capability performance
    and priority transformation actions.
</p>

</div>

                <div class="grid min-w-full gap-3 sm:grid-cols-3 lg:min-w-[440px]">

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Organization
                        </p>

                        <p class="mt-2 truncate font-bold text-slate-950">
                            {{ $assessment->company->name ?? 'Not specified' }}
                        </p>

                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Country
                        </p>

                        <p class="mt-2 truncate font-bold text-slate-950">
                            {{ $assessment->company->country ?? 'Not specified' }}
                        </p>

                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Completed
                        </p>

                        <p class="mt-2 font-bold text-slate-950">
                            {{ optional($assessment->updated_at)->format('d M Y') ?? 'Not available' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

    @if($coverageIncomplete)
        <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500 text-sm font-bold text-white">
                !
            </div>
            <p class="text-sm leading-6 text-amber-800">
                <span class="font-semibold">Partial coverage:</span>
                only {{ $assessedDimensionCount }} of {{ $totalDimensionCount }} dimensions were assessed.
                Scores below reflect answered capabilities only.
            </p>
        </div>
    @endif

   {{-- Main executive summary panel --}}
<section class="overflow-hidden rounded-3xl bg-slate-950 text-white shadow-xl">

    <div class="grid grid-cols-1 xl:grid-cols-[330px_1fr]">

        {{-- Combined score --}}
        <div class="flex flex-col items-center justify-center border-b border-slate-800 p-8 xl:border-b-0 xl:border-r">

            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                Combined YARA Score
            </p>

            <div
                class="relative mt-6 flex h-56 w-56 items-center justify-center rounded-full"
                style="
                    background:
                    conic-gradient(
                        #facc15 0deg {{ $scoreAngle }}deg,
                        #1e293b {{ $scoreAngle }}deg 360deg
                    );
                "
            >
                <div class="flex h-44 w-44 flex-col items-center justify-center rounded-full bg-slate-950">

                    <span class="text-6xl font-bold tracking-tight">
                        {{ number_format($combinedScore, 1) }}
                    </span>

                    <span class="mt-2 text-sm text-slate-400">
                        out of 100
                    </span>

                </div>
            </div>

            @if($maturityLevel)
                <div class="mt-6 text-center">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Current maturity level
                    </p>

                    <span class="mt-3 inline-flex rounded-full bg-yellow-400 px-4 py-2 text-sm font-bold text-slate-950">
                        Level {{ $maturityLevel->level }}
                        — {{ $maturityLevel->label ?? $maturityLevel->name }}
                    </span>

                </div>
            @endif

        </div>

        {{-- Executive overview --}}
        <div class="p-8">

       <div class="mb-8">

    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

        <div>

            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-yellow-400">
                Executive Dashboard
            </p>

            <h2 class="mt-2 text-3xl font-bold">
                Assessment Overview
            </h2>

        </div>

        <div class="rounded-2xl border border-slate-700 bg-slate-900 px-6 py-4">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Overall Status
            </p>

            <p class="mt-2 text-xl font-bold text-yellow-400">
                {{ $balanceLabel }}
            </p>

        </div>

    </div>

    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-5 py-4">

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Capabilities Assessed
            </p>

            <p class="mt-2 text-2xl font-bold text-white">
                {{ $assessedDimensionTotal }}
            </p>

        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-5 py-4">

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Current Maturity
            </p>

            <p class="mt-2 text-2xl font-bold text-white">
                @if($maturityLevel)
                    Level {{ $maturityLevel->level }}
                @else
                    —
                @endif
            </p>

        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-5 py-4">

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Roadmap Actions
            </p>

            <p class="mt-2 text-2xl font-bold text-white">
                {{ $roadmapActionCount ?: '—' }}
            </p>

        </div>

    </div>

</div>

            {{-- Main KPI cards --}}
            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <div class="group rounded-2xl border border-slate-700 bg-slate-900 p-6 transition hover:border-yellow-400/40">

                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Organization score
                        </p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-400/10 transition group-hover:bg-yellow-400/20">
                            <svg class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m4 0v-5a2 2 0 00-2-2h0a2 2 0 00-2 2v5m-2-12h.01M12 7h.01M16 7h.01M8 11h.01M12 11h.01M16 11h.01"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-3 text-4xl font-bold">
                        {{ number_format($organizationScore, 1) }}
                    </p>

                    <p class="mt-2 text-sm text-slate-400">
                        Internal organizational AI maturity
                    </p>

                </div>

                <div class="group flex flex-col rounded-2xl border border-slate-700 bg-slate-900 p-6 transition hover:border-yellow-400/40">

    <div class="flex items-start justify-between">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
            Country benchmark
        </p>
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-400/10 transition group-hover:bg-blue-400/20">
            <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>

    @if($countryScore !== null)

        <p class="mt-3 text-4xl font-bold">
            {{ number_format($countryScore, 1) }}
        </p>

        <p class="mt-2 text-sm text-slate-400">
            {{ $assessment->company->country ?? 'Country' }}
            · {{ $assessment->country_ai_year }}
        </p>

        <a
            href="{{ route('assessment.country', $assessment) }}"
            class="mt-5 inline-flex w-fit items-center gap-2 text-sm font-semibold text-yellow-400 transition hover:text-yellow-300"
        >
            Explore country intelligence

            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                class="h-4 w-4"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 12h14m-6-6 6 6-6 6"
                />
            </svg>
        </a>

    @else

        <p class="mt-4 text-sm font-semibold text-slate-400">
            Unavailable
        </p>

    @endif

</div>

                <div class="group rounded-2xl border border-slate-700 bg-slate-900 p-6 transition hover:border-yellow-400/40">

                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Benchmark position
                        </p>
                        @if($countryGap !== null)
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $countryGap >= 0 ? 'bg-green-400/10 group-hover:bg-green-400/20' : 'bg-red-400/10 group-hover:bg-red-400/20' }} transition">
                                <svg class="h-4 w-4 {{ $countryGap >= 0 ? 'text-green-400' : 'text-red-400 rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l5 5a1 1 0 01-1.414 1.414L11 6.414V16a1 1 0 11-2 0V6.414L5.707 9.707a1 1 0 01-1.414-1.414l5-5A1 1 0 0110 3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    @if($countryGap !== null)

                        <p class="mt-3 text-4xl font-bold {{ $countryGap >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $countryGap >= 0 ? '+' : '' }}
                            {{ number_format($countryGap, 1) }}
                        </p>

                        <p class="mt-2 text-sm text-slate-400">
                            {{ $countryGap >= 0 ? 'Above' : 'Below' }}
                            national benchmark
                        </p>

                    @else

                        <p class="mt-4 text-sm font-semibold text-slate-400">
                            Unavailable
                        </p>

                    @endif

                </div>

                <div class="group rounded-2xl border border-slate-700 bg-slate-900 p-6 transition hover:border-yellow-400/40">

                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Defined capabilities
                        </p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-400/10 transition group-hover:bg-slate-400/20">
                            <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-3 text-4xl font-bold">
                        {{ $definedCapabilityCount }}

                        <span class="text-lg text-slate-500">
                            / {{ $assessedDimensionTotal }}
                        </span>
                    </p>

                    <p class="mt-2 text-sm text-slate-400">
                        At maturity Level 3 or above
                    </p>

                </div>

            </div>

            {{-- Strategic insights --}}
            <div class="mt-8 border-t border-slate-800 pt-8">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                    <div class="rounded-2xl bg-slate-900 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Strongest strategic capability
                        </p>

                        <p class="mt-3 text-lg font-bold">
                            {{ $highestDimensionName ?? 'Not available' }}
                        </p>

                        @if($highestDimensionScore !== null)
                            <p class="mt-2 text-sm font-bold text-green-400">
                                {{ number_format($highestDimensionScore, 1) }}/100
                            </p>
                        @endif

                    </div>

                    <div class="rounded-2xl bg-slate-900 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Largest transformation risk
                        </p>

                        <p class="mt-3 text-lg font-bold">
                            {{ $lowestDimensionName ?? 'Not available' }}
                        </p>

                        <div class="mt-3 flex items-center gap-2">

                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $riskBadgeClass }}">
                                {{ $riskLevel }}
                            </span>

                            @if($lowestDimensionScore !== null)
                                <span class="text-sm text-slate-400">
                                    {{ number_format($lowestDimensionScore, 1) }}/100
                                </span>
                            @endif

                        </div>

                    </div>

                    <div class="rounded-2xl bg-slate-900 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Maturity consistency
                        </p>

                        <p class="mt-3 text-2xl font-bold">
                            {{ number_format($maturitySpread, 1) }}

                            <span class="text-sm font-medium text-slate-500">
                                point spread
                            </span>
                        </p>

                        <p class="mt-2 text-sm leading-6 text-slate-400">
                            {{ $balanceDescription }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
       


    {{-- Analytics charts --}}
    @if($dimensionScores->count() >= 3)

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">

            {{-- Radar chart --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                <div>
                    <h2 class="text-xl font-bold text-slate-950">
                        AI Capability Landscape
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Overall shape and balance across assessed capabilities.
                    </p>
                </div>

                <div class="mt-6" style="height: 360px;">
                    <canvas id="dimensionRadarChart"></canvas>
                </div>

            </div>

            {{-- Ranked horizontal bar chart --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                <div>
                    <h2 class="text-xl font-bold text-slate-950">
                        Capability Ranking
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Assessed dimensions ranked from strongest to weakest.
                    </p>
                </div>

                <div class="mt-6" style="height: 320px;">
                    <canvas id="dimensionBarChart"></canvas>
                    <div class="mt-5 flex flex-wrap items-center justify-center gap-4 text-xs font-medium text-slate-500">

    <div class="flex items-center gap-2">
        <span class="h-3 w-3 rounded-full bg-red-500"></span>
        Critical
    </div>

    <div class="flex items-center gap-2">
        <span class="h-3 w-3 rounded-full bg-orange-500"></span>
        Needs attention
    </div>

    <div class="flex items-center gap-2">
        <span class="h-3 w-3 rounded-full bg-yellow-400"></span>
        Developing
    </div>

    <div class="flex items-center gap-2">
        <span class="h-3 w-3 rounded-full bg-blue-500"></span>
        Established
    </div>

    <div class="flex items-center gap-2">
        <span class="h-3 w-3 rounded-full bg-green-500"></span>
        Advanced
    </div>

</div>
                </div>

            </div>

        </section>

    @endif

   {{-- Strategic strengths and improvement priorities --}}
<section class="grid grid-cols-1 gap-6 xl:grid-cols-2">

    {{-- Strategic strengths --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-7 py-6">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-green-700">
                        Strategic Strengths
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-950">
                        Best-Performing Capabilities
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        The capabilities currently providing the strongest foundation for AI transformation.
                    </p>
                </div>

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        class="h-6 w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 19V9m6 10V5m6 14v-7m4 7H2"
                        />
                    </svg>
                </div>

            </div>

        </div>

        <div class="space-y-5 p-7">

            @forelse($topDimensions as $dimension => $score)

                @php
                    $strengthRank = $loop->iteration;
                @endphp

                <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5 transition hover:border-green-200 hover:bg-green-50/30">

                    <div class="flex items-start gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-950 text-sm font-bold text-white">
                            {{ $strengthRank }}
                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                <h3 class="font-bold text-slate-950">
                                    {{ $dimension }}
                                </h3>

                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-bold text-green-700">
                                        {{ number_format($score, 1) }}
                                    </span>

                                    <span class="text-sm font-semibold text-slate-400">
                                        /100
                                    </span>
                                </div>

                            </div>

                            <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-200">

                                <div
                                    class="h-full rounded-full bg-green-500"
                                    style="width: {{ min(100, max(0, $score)) }}%;"
                                ></div>

                            </div>

                            <div class="mt-3 flex items-center justify-between gap-3 text-xs">

                                <span class="font-semibold text-green-700">
                                    Capability strength
                                </span>

                                <span class="text-slate-400">
                                    Rank #{{ $strengthRank }}
                                </span>

                            </div>

                        </div>

                    </div>

                </article>

            @empty

                <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">
                    No assessed capabilities are available.
                </div>

            @endforelse

        </div>

    </div>

    {{-- Improvement priorities --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-7 py-6">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-red-700">
                        Improvement Priorities
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-950">
                        Capabilities Requiring Action
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        The weakest capabilities that should receive the earliest executive attention.
                    </p>
                </div>

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-100 text-red-700">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        class="h-6 w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.3 3.8 2.5 17.3A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.7L13.7 3.8a2 2 0 0 0-3.4 0Z"
                        />
                    </svg>
                </div>

            </div>

        </div>

        <div class="space-y-5 p-7">

            @forelse($priorityDimensions as $dimension => $score)

                @php
                    $priorityRank = $loop->iteration;

                    if ($score < 20) {
                        $priorityLabel = 'Critical';
                        $priorityClass = 'bg-red-100 text-red-700';
                    } elseif ($score < 40) {
                        $priorityLabel = 'High priority';
                        $priorityClass = 'bg-orange-100 text-orange-700';
                    } else {
                        $priorityLabel = 'Needs attention';
                        $priorityClass = 'bg-yellow-100 text-yellow-700';
                    }
                @endphp

                <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                    <div class="flex items-start gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white">
                            {{ $priorityRank }}
                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                                <div>
                                    <h3 class="font-bold text-slate-950">
                                        {{ $dimension }}
                                    </h3>

                                    <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $priorityClass }}">
                                        {{ $priorityLabel }}
                                    </span>
                                </div>

                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-bold text-red-700">
                                        {{ number_format($score, 1) }}
                                    </span>

                                    <span class="text-sm font-semibold text-slate-400">
                                        /100
                                    </span>
                                </div>

                            </div>

                            <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-200">

                                <div
                                    class="h-full rounded-full bg-red-500"
                                    style="width: {{ min(100, max(0, $score)) }}%;"
                                ></div>

                            </div>

                            <div class="mt-3 flex items-center justify-between gap-3 text-xs">

                                <span class="font-semibold text-red-700">
                                    Executive intervention required
                                </span>

                                <span class="text-slate-400">
                                    Priority #{{ $priorityRank }}
                                </span>

                            </div>

                        </div>

                    </div>

                </article>

            @empty

                <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">
                    No transformation priorities are available.
                </div>

            @endforelse

        </div>

    </div>

</section>

   {{-- Detailed capability breakdown --}}
<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    {{-- Section heading --}}
    <div class="border-b border-slate-200 px-7 py-6 md:px-8">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                    Capability Analysis
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-950">
                    Detailed Assessment Results
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Maturity score, classification and relative performance for each assessed capability.
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600">
                <span class="h-2 w-2 rounded-full bg-yellow-400"></span>

                {{ $rankedDimensionScores->count() }}
                {{ $rankedDimensionScores->count() === 1 ? 'capability' : 'capabilities' }}
            </div>

        </div>

    </div>

    {{-- Capability table --}}
    <div class="divide-y divide-slate-200">

        @foreach($rankedDimensionScores as $dimension => $score)

            @php
                $level = $dimensionMaturityLevels[$dimension] ?? null;

                if ($score < 20) {
                    $statusLabel = 'Critical';
                    $statusClass = 'bg-red-100 text-red-700';
                    $progressClass = 'bg-red-500';
                } elseif ($score < 40) {
                    $statusLabel = 'Needs attention';
                    $statusClass = 'bg-orange-100 text-orange-700';
                    $progressClass = 'bg-orange-500';
                } elseif ($score < 60) {
                    $statusLabel = 'Developing';
                    $statusClass = 'bg-yellow-100 text-yellow-700';
                    $progressClass = 'bg-yellow-400';
                } elseif ($score < 80) {
                    $statusLabel = 'Established';
                    $statusClass = 'bg-blue-100 text-blue-700';
                    $progressClass = 'bg-blue-500';
                } else {
                    $statusLabel = 'Advanced';
                    $statusClass = 'bg-green-100 text-green-700';
                    $progressClass = 'bg-green-500';
                }
            @endphp

            <article class="px-7 py-6 transition hover:bg-slate-50 md:px-8">

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-[minmax(0,1fr)_180px_130px] lg:items-center">

                    {{-- Capability information --}}
                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-3">

                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-950 text-xs font-bold text-white">
                                {{ $loop->iteration }}
                            </span>

                            <h3 class="font-bold text-slate-950">
                                {{ $dimension }}
                            </h3>

                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">

                            @if($level)
                                <span class="text-slate-500">
                                    Maturity:
                                    <strong class="font-semibold text-slate-700">
                                        Level {{ $level->level }}
                                        — {{ $level->label ?? $level->name }}
                                    </strong>
                                </span>
                            @else
                                <span class="text-slate-400">
                                    Maturity level unavailable
                                </span>
                            @endif

                            <span class="text-slate-500">
                                Rank:
                                <strong class="font-semibold text-slate-700">
                                    #{{ $loop->iteration }}
                                </strong>
                            </span>

                        </div>

                    </div>

                    {{-- Progress --}}
                    <div>

                        <div class="mb-2 flex items-center justify-between text-xs font-semibold">

                            <span class="uppercase tracking-wide text-slate-400">
                                Performance
                            </span>

                            <span class="text-slate-600">
                                {{ number_format($score, 1) }}%
                            </span>

                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full {{ $progressClass }}"
                                style="width: {{ min(100, max(0, $score)) }}%;"
                            ></div>

                        </div>

                    </div>

                    {{-- Score --}}
                    <div class="flex items-baseline lg:justify-end">

                        <span class="text-3xl font-bold text-slate-950">
                            {{ number_format($score, 1) }}
                        </span>

                        <span class="ml-1 text-sm font-semibold text-slate-400">
                            /100
                        </span>

                    </div>

                </div>

            </article>

        @endforeach

    </div>

</section>

{{-- AI Executive Summary --}}
@php
    $aiAnalysis = null;

    if (!empty($assessment->ai_executive_summary)) {
        $decoded = json_decode($assessment->ai_executive_summary, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $aiAnalysis = $decoded;
        }
    }

    // Related roadmap actions for the priority risk dimension, so the AI card
    // links directly to what's already in the roadmap below instead of
    // floating as an isolated statement.
    $relatedActionCount = 0;

    if ($aiAnalysis && !empty($aiAnalysis['priority_risk']['dimension']) && $roadmapActionCount) {
        $relatedActionCount = $roadmapActions
            ->filter(function ($action) use ($aiAnalysis) {
                return !empty($action['dimension'])
                    && strcasecmp(
                        trim($action['dimension']),
                        trim($aiAnalysis['priority_risk']['dimension'])
                    ) === 0;
            })
            ->count();
    }

    // Staleness: if the assessment was recalculated after the AI summary
    // was generated, the interpretation may no longer match the numbers above it.
    $summaryGeneratedAt = $assessment->ai_summary_generated_at ?? null;

    $summaryIsStale = $summaryGeneratedAt
        && $assessment->updated_at
        && $assessment->updated_at->greaterThan($summaryGeneratedAt);
@endphp

<section class="mt-10 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

    <div class="flex flex-wrap items-start justify-between gap-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
AI-POWERED READINESS INSIGHTS            </p>

            <h2 class="mt-2 text-2xl font-bold text-slate-900">
                AI Readiness Analysis
            </h2>

            <p class="mt-2 text-slate-500">
Strategic insights based on your organization's assessment results.            </p>
        </div>

        <div class="flex flex-col items-end gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-4 py-2 text-xs font-semibold text-yellow-700">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-yellow-500 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-yellow-600"></span>
                </span>
                AI Generated
            </span>

            <div class="flex items-center gap-3">
                @if($summaryGeneratedAt)
                    <span class="text-xs text-slate-400">
                        Generated {{ $summaryGeneratedAt->diffForHumans() }}
                    </span>
                @endif

                
            </div>
        </div>
    </div>

   @if($aiAnalysis)

    <div id="ai-summary-results">
        @include('assessments.partials.ai-summary-results')
    </div>

@else

        <div class="mt-8 flex flex-col items-start gap-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-yellow-100 text-yellow-700">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                    </svg>
                </span>
                <p class="text-sm leading-6 text-slate-600">
                    Generate an AI strategic interpretation of your assessment results.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('assessment.generate-ai-summary', $assessment) }}"
                  class="flex-none"
                  data-ai-form>
                @csrf

                <button type="submit"
                        data-ai-submit
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70">
                    <span data-ai-label>Generate Strategic Analysis</span>
                </button>
            </form>
        </div>

    @endif

    @if(session('error'))
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

</section>
@if($assessment->engagement_type === 'transformation')
    {{-- Personalized, AI-generated transformation roadmap --}}
@if($assessment->transformation_status !== 'roadmap_ready')

<section id="roadmap" class="scroll-mt-24 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-5 border-b border-slate-200 bg-slate-950 px-6 py-7 text-white md:flex-row md:items-center md:justify-between md:px-8">

                <div>
                   <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                        @if($roadmapActionCount)
                            AI-Generated Action Plan
                        @else
                            Personalized Roadmap
                        @endif
                    </p>

                    <h2 class="mt-2 text-2xl font-bold">
                        @if($roadmapActionCount)
                            Priority Transformation Roadmap
                        @else
                            Build Your Roadmap
                        @endif
                    </h2>
                </div>

                <span class="inline-flex w-fit rounded-full bg-yellow-400 px-4 py-2 text-sm font-bold text-slate-950">
                    @if($roadmapActionCount)
                        {{ $roadmapActionCount }} {{ $roadmapActionCount === 1 ? 'action' : 'actions' }}
                    @else
                        Not generated yet
                    @endif
                </span>

            </div>

            {{-- Slightly tighter bottom padding than before — the card was leaving a
                 large empty gap right above the footer once the roadmap results were
                 collapsed/short. --}}
            <div class="space-y-6 p-6 md:px-8 md:pt-8 md:pb-6">

                @if(session('roadmap_error'))
                    <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                            !
                        </div>
                        <p class="text-sm leading-6 text-red-700">
                            {{ session('roadmap_error') }}
                        </p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
                        <p class="text-sm font-semibold text-red-700">
                            Please fix the following before generating your roadmap:
                        </p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm leading-6 text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($roadmapIsStale)
                    <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-white">
                            !
                        </div>
                        <p class="text-sm leading-6 text-amber-800">
                            <span class="font-semibold">This assessment was updated</span> after this roadmap was generated. Consider regenerating it so the actions reflect your current scores.
                        </p>
                    </div>
                @endif

                @if($roadmapData && $roadmapActionCount)

                    {{-- Inputs recap + edit --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
                                🎯 Target: {{ $roadmapInputs['target_level_label'] ?? '—' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
                                💰 Budget: {{ $roadmapInputs['budget'] ?? '—' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
                                ⏱ Timeline: {{ $roadmapInputs['timeline'] ?? '—' }}
                            </span>
                            @if(!empty($roadmapInputs['focus_areas']))
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
                                    🔍 Focus: {{ implode(', ', $roadmapInputs['focus_areas']) }}
                                </span>
                            @endif
                        </div>

                        <button type="button" id="toggle-roadmap-form"
                            class="flex-none text-xs font-bold text-yellow-700 transition hover:underline">
                            Edit inputs &amp; regenerate
                        </button>
                    </div>

                    @if(!empty($roadmapData['summary']))
                        <p class="rounded-2xl border border-slate-200 bg-white p-5 text-sm leading-6 text-slate-600">
                            {{ $roadmapData['summary'] }}
                        </p>
                    @endif

                @endif

                {{-- Planning questionnaire — visible by default until a roadmap
                     exists, then tucked behind "Edit inputs & regenerate". It also
                     re-opens automatically if the previous submission had a
                     validation error or the AI call failed, so corrections are
                     visible instead of hidden behind the toggle. --}}
                <div id="roadmap-planning-form" class="{{ ($roadmapActionCount && !$roadmapFormHasIssue) ? 'hidden' : '' }}">

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                @if($assessment->transformation_status === 'planning')
                                <h3 class="text-lg font-bold text-slate-900">
                                    Tell us what you're aiming for
                                </h3>
                                @elseif($assessment->transformation_status === 'submitted')

    <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-8 text-center">

        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-yellow-400 font-bold text-slate-950">
            ✓
        </div>

        <p class="mt-5 text-xs font-bold uppercase tracking-wider text-yellow-700">
            Transformation Roadmap
        </p>

        <h3 class="mt-2 text-2xl font-bold text-slate-950">
            Submitted for expert review
        </h3>

        <p class="mx-auto mt-3 max-w-2xl leading-7 text-slate-600">
            Your assessment findings and transformation priorities have been
            submitted to Yellomind. A consultant will review your readiness
            results, target maturity, timeline, investment capacity and
            strategic priorities before preparing your transformation roadmap.
        </p>

        <div class="mx-auto mt-6 max-w-xl rounded-xl border border-yellow-200 bg-white p-4">
            <p class="text-sm font-semibold text-slate-900">
                Your request is waiting for consultant review.
            </p>

            <p class="mt-1 text-sm text-slate-500">
                You can return to this page at any time to check its status.
            </p>
        </div>

    </div>

@endif
                                <p class="mt-1 text-sm text-slate-500">
                                    A few quick questions so the AI can tailor the roadmap to your ambition, budget and timeline — built on top of your assessment results. Note that very ambitious targets on a tight budget or short timeline may be flagged as unrealistic — extend one of those or pick a closer target level.
                                </p>
                            </div>

                            <span class="inline-flex flex-none items-center gap-1.5 rounded-full bg-slate-900 px-3 py-1.5 text-xs font-bold text-yellow-400">
                                Current: Level {{ $currentLevelValue }}{{ $maturityLevel ? ' — ' . ($maturityLevel->label ?? $maturityLevel->name) : '' }}
                            </span>
                        </div>

                        <form method="POST"
      action="{{ route('assessment.roadmap.preferences', $assessment) }}"
      class="mt-6">
    @csrf

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                                {{-- Target level --}}
                                <div>
                                    <label for="target_level" class="block text-sm font-semibold text-slate-900">
                                        Target maturity level
                                    </label>
                                    <select name="target_level" id="target_level" required
                                        class="mt-2 w-full rounded-xl border {{ $errors->has('target_level') ? 'border-red-400' : 'border-slate-300' }} bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 focus:outline-none">
                                        <option value="" disabled {{ empty(old('target_level', $roadmapInputs['target_level'] ?? null)) ? 'selected' : '' }}>Select a level</option>
                                        @foreach($targetLevelOptions as $option)
                                            <option value="{{ $option->level }}" {{ old('target_level', $roadmapInputs['target_level'] ?? null) == $option->level ? 'selected' : '' }}>
                                                Level {{ $option->level }}{{ ($option->label ?? $option->name) ? ' — ' . ($option->label ?? $option->name) : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_level')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Budget --}}
                                <div>
                                    <label for="budget" class="block text-sm font-semibold text-slate-900">
                                        Budget range
                                    </label>
                                    @php
                                        $budgetOptions = [
                                            'Under $10,000',
                                            '$10,000 - $50,000',
                                            '$50,000 - $200,000',
                                            '$200,000+',
                                            'Prefer not to say',
                                        ];
                                    @endphp
                                    <select name="budget" id="budget" required
                                        class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 focus:outline-none">
                                        <option value="" disabled {{ empty(old('budget', $roadmapInputs['budget'] ?? null)) ? 'selected' : '' }}>Select a range</option>
                                        @foreach($budgetOptions as $budgetOption)
                                            <option value="{{ $budgetOption }}" {{ old('budget', $roadmapInputs['budget'] ?? null) === $budgetOption ? 'selected' : '' }}>
                                                {{ $budgetOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Timeline --}}
                                <div>
                                    <label for="timeline" class="block text-sm font-semibold text-slate-900">
                                        Timeline horizon
                                    </label>
                                    @php
                                        $timelineOptions = ['3 months', '6 months', '12 months', '18-24 months'];
                                    @endphp
                                    <select name="timeline" id="timeline" required
                                        class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 focus:outline-none">
                                        <option value="" disabled {{ empty(old('timeline', $roadmapInputs['timeline'] ?? null)) ? 'selected' : '' }}>Select a timeline</option>
                                        @foreach($timelineOptions as $timelineOption)
                                            <option value="{{ $timelineOption }}" {{ old('timeline', $roadmapInputs['timeline'] ?? null) === $timelineOption ? 'selected' : '' }}>
                                                {{ $timelineOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            {{-- Optional focus areas --}}
                            <div class="mt-5">
                                <label class="block text-sm font-semibold text-slate-900">
                                    Focus areas <span class="font-normal text-slate-400">(optional, pick up to 3)</span>
                                </label>
                                <div class="mt-2 flex flex-wrap gap-2" id="focus-area-group">
                                    @foreach($focusAreaOptions as $focusOption)
                                        @php
                                            $isChecked = in_array($focusOption, old('focus_areas', $roadmapInputs['focus_areas'] ?? []), true);
                                        @endphp
                                        <label class="focus-chip inline-flex cursor-pointer items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-medium transition {{ $isChecked ? 'border-yellow-400 bg-yellow-50 text-yellow-700' : 'border-slate-200 bg-white text-slate-600 hover:border-yellow-300' }}">
                                            <input type="checkbox" name="focus_areas[]" value="{{ $focusOption }}" class="hidden focus-checkbox" {{ $isChecked ? 'checked' : '' }}>
                                            {{ $focusOption }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-6 flex flex-wrap items-center justify-end gap-3">

                                @if($roadmapActionCount)
                                    <button type="button" id="cancel-roadmap-form"
                                        class="inline-flex items-center rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-white">
                                        Cancel
                                    </button>
                                @endif

                               <button type="submit"
        class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
    <span>Continue to Payment</span>

    <svg
        class="h-4 w-4"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M5 12h14m-6-6 6 6-6 6"
        />
    </svg>
</button>

                            </div>

                                               </form>

                    </div>

                </div>

                <div id="roadmap-results">
                    @include('assessments.partials.roadmap-results')
                </div>

            </div>

    </section>
    @endif
@else

    {{-- =====================================================
         SELF-ASSESSMENT → TRANSFORMATION UPGRADE
    ====================================================== --}}

   <section
    id="roadmap"
    class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-950 text-white shadow-sm"
>
    <div class="px-6 py-8 md:px-8 md:py-10">

        <div class="flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">

            {{-- =====================================================
                 NO TRANSFORMATION REQUEST YET
            ====================================================== --}}
            @if(empty($assessment->transformation_status))

                <div class="max-w-3xl">

                    <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                        From Diagnosis to Action
                    </p>

                    <h2 class="mt-3 text-2xl font-bold md:text-3xl">
                        Ready to move from diagnosis to action?
                    </h2>

                    <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                        Turn your assessment findings into a structured transformation
                        roadmap aligned with your priorities, target maturity,
                        timeline and investment capacity.
                    </p>

                    <p class="mt-3 text-sm leading-6 text-slate-400">
                        Your completed assessment will be reused — you will not need
                        to take the assessment again.
                    </p>

                </div>

                <div class="shrink-0">

                    <form
                        method="POST"
                        action="{{ route('transformation.from-assessment', $assessment) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 font-bold text-slate-950 transition hover:bg-yellow-300"
                        >
                            Build Your Transformation Roadmap
                            <span class="ml-2">→</span>
                        </button>

                    </form>

                </div>


            {{-- =====================================================
                 TRANSFORMATION PLANNING STARTED
            ====================================================== --}}
            @elseif($assessment->transformation_status === 'planning')

                <div class="max-w-3xl">

                    <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                        Transformation Roadmap
                    </p>

                    <h2 class="mt-3 text-2xl font-bold md:text-3xl">
                        Continue building your transformation brief
                    </h2>

                    <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                        Your assessment results are ready. Complete your transformation
                        priorities, target maturity, timeline and investment capacity
                        before submitting your request for expert review.
                    </p>

                    <p class="mt-3 text-sm leading-6 text-slate-400">
                        Your progress has been saved.
                    </p>

                </div>

                <div class="shrink-0">

                    <a
                        href="{{ route('assessment.results', $assessment) }}#roadmap-builder"
                        class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 font-bold text-slate-950 transition hover:bg-yellow-300"
                    >
                        Continue Transformation Plan
                        <span class="ml-2">→</span>
                    </a>

                </div>


            {{-- =====================================================
                 SUBMITTED — WAITING FOR CONSULTANT
            ====================================================== --}}
            @elseif($assessment->transformation_status === 'submitted')

                <div class="max-w-3xl">

                    <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                        Transformation Roadmap
                    </p>

                    <h2 class="mt-3 text-2xl font-bold md:text-3xl">
                        Your transformation request is under review
                    </h2>

                    <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                        Your transformation brief has been successfully submitted
                        to Yellomind and is awaiting expert review.
                    </p>

                    <p class="mt-3 text-sm leading-6 text-slate-400">
                        No further action is required from you right now.
                    </p>

                </div>

                <div class="shrink-0">

                    <a
                        href="{{ route('assessment.transformation.submitted', $assessment) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 font-bold text-slate-950 transition hover:bg-yellow-300"
                    >
                        View Request Status
                        <span class="ml-2">→</span>
                    </a>

                </div>


            {{-- =====================================================
                 CONSULTANT IS REVIEWING
            ====================================================== --}}
            @elseif($assessment->transformation_status === 'in_review')

                <div class="max-w-3xl">

                    <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                        Transformation Roadmap
                    </p>

                    <h2 class="mt-3 text-2xl font-bold md:text-3xl">
                        Expert review in progress
                    </h2>

                    <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                        A Yellomind consultant is currently reviewing your AI readiness
                        findings and transformation priorities.
                    </p>

                    <p class="mt-3 text-sm leading-6 text-slate-400">
                        Your roadmap will become available once the review is complete.
                    </p>

                </div>

                <div class="shrink-0">

                    <a
                        href="{{ route('assessment.transformation.submitted', $assessment) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 font-bold text-slate-950 transition hover:bg-yellow-300"
                    >
                        View Request Status
                        <span class="ml-2">→</span>
                    </a>

                </div>


            {{-- =====================================================
                 ROADMAP READY
            ====================================================== --}}
            @elseif($assessment->transformation_status === 'roadmap_ready')

                <div class="max-w-3xl">

                    <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                        Transformation Roadmap
                    </p>

                    <h2 class="mt-3 text-2xl font-bold md:text-3xl">
                        Your Transformation Roadmap is ready
                    </h2>

                    <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                        Expert review is complete and your transformation roadmap
                        is now available.
                    </p>

                    <p class="mt-3 text-sm leading-6 text-slate-400">
                        Review your prioritized initiatives, implementation timeline
                        and recommended next steps.
                    </p>

                </div>

                <div class="shrink-0">

                    <a
                        href="{{ route('assessment.results', $assessment) }}#final-roadmap"
                        class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 font-bold text-slate-950 transition hover:bg-yellow-300"
                    >
                        View Transformation Roadmap
                        <span class="ml-2">→</span>
                    </a>

                </div>


            {{-- =====================================================
                 FALLBACK — UNKNOWN STATUS
            ====================================================== --}}
            @else

                <div class="max-w-3xl">

                    <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                        Transformation Roadmap
                    </p>

                    <h2 class="mt-3 text-2xl font-bold md:text-3xl">
                        Track your transformation request
                    </h2>

                    <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                        Your transformation request is currently being processed.
                    </p>

                </div>

                <div class="shrink-0">

                    <a
                        href="{{ route('assessment.transformation.submitted', $assessment) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 font-bold text-slate-950 transition hover:bg-yellow-300"
                    >
                        View Request Status
                        <span class="ml-2">→</span>
                    </a>

                </div>

            @endif

                </div>

    </div>
</section>

@endif


{{-- =====================================================
     FINAL EXPERT-REVIEWED TRANSFORMATION ROADMAP
====================================================== --}}
@if(
    $assessment->transformation_status === 'roadmap_ready'
    && $assessment->transformationRoadmap
    && $assessment->transformationRoadmap->status === 'ready'
    && $assessment->transformationRoadmap->finalized_at
)

    <section
        id="final-roadmap"
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
    >

   @php
    $finalRoadmap = $assessment->transformationRoadmap;
    $finalInitiatives = $finalRoadmap->initiatives;
@endphp

{{-- Header --}}
<div class="border-b border-slate-800 bg-slate-950 px-6 py-8 text-white md:px-8">

    <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-yellow-400">
                Expert-Reviewed Transformation Plan
            </p>

            <h2 class="mt-2 text-3xl font-bold">
                Your Transformation Roadmap
            </h2>

            <p class="mt-3 max-w-2xl leading-7 text-slate-300">
                Your personalized roadmap has been reviewed and finalized by a
                Yellomind consultant based on your AI readiness assessment and
                transformation priorities.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            <span class="inline-flex items-center rounded-full bg-green-400/10 px-4 py-2 text-sm font-bold text-green-300 ring-1 ring-inset ring-green-400/20">
                ✓ Expert Reviewed
            </span>

            <span class="inline-flex items-center rounded-full bg-yellow-400 px-4 py-2 text-sm font-bold text-slate-950">
                {{ $finalInitiatives->count() }}
                {{ $finalInitiatives->count() === 1 ? 'Initiative' : 'Initiatives' }}
            </span>

        </div>

    </div>

</div>


{{-- Roadmap overview --}}
<div class="border-b border-slate-200 bg-slate-50 px-6 py-6 md:px-8">

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Status
            </p>

            <p class="mt-2 font-bold text-green-700">
                Roadmap Ready
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Initiatives
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-950">
                {{ $finalInitiatives->count() }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Finalized
            </p>

            <p class="mt-2 font-bold text-slate-950">
                {{ optional($finalRoadmap->finalized_at)->format('d M Y') }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Delivery
            </p>

            <p class="mt-2 font-bold text-slate-950">
                Expert Validated
            </p>
        </div>

    </div>

</div>


{{-- Initiatives --}}
<div class="space-y-5 p-6 md:p-8">

    <div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
            Implementation Priorities
        </p>

        <h3 class="mt-2 text-2xl font-bold text-slate-950">
            Prioritized Initiatives
        </h3>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Recommended initiatives ordered according to your transformation priorities.
        </p>
    </div>

    @forelse($finalInitiatives as $initiative)

        <article class="rounded-2xl border border-slate-200 bg-white p-6">

            <div class="flex items-start gap-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-950 text-sm font-bold text-white">
                    {{ $loop->iteration }}
                </div>

                <div class="min-w-0 flex-1">

                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">

                        <div>
                            @if($initiative->dimension)
                                <p class="text-xs font-bold uppercase tracking-wide text-yellow-600">
                                    {{ $initiative->dimension }}
                                </p>
                            @endif

                            <h4 class="mt-1 text-xl font-bold text-slate-950">
                                {{ $initiative->title }}
                            </h4>
                        </div>

                        <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-bold
                            {{ $initiative->priority === 'high'
                                ? 'bg-red-100 text-red-700'
                                : ($initiative->priority === 'low'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($initiative->priority ?? 'medium') }} Priority
                        </span>

                    </div>

                    <p class="mt-4 leading-7 text-slate-600">
                        {{ $initiative->description }}
                    </p>

                </div>
                                </div>

{{-- Initiative details --}}
<div class="mt-6 grid gap-5 border-t border-slate-100 pt-6 lg:grid-cols-2">

    {{-- Business rationale --}}
    @if($initiative->business_rationale)
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Why This Matters
            </p>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                {{ $initiative->business_rationale }}
            </p>
        </div>
    @endif


    {{-- Expected outcome --}}
    @if($initiative->expected_outcome)
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Expected Outcome
            </p>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                {{ $initiative->expected_outcome }}
            </p>
        </div>
    @endif


    {{-- Recommended actions --}}
    @if(!empty($initiative->recommended_actions))
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Recommended Actions
            </p>

            <ul class="mt-3 space-y-2">
                @foreach($initiative->recommended_actions as $action)
                    <li class="flex gap-2 text-sm leading-6 text-slate-600">
                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-yellow-500"></span>
                        <span>{{ $action }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Success metrics --}}
    @if(!empty($initiative->success_metrics))
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Success Metrics
            </p>

            <ul class="mt-3 space-y-2">
                @foreach($initiative->success_metrics as $metric)
                    <li class="flex gap-2 text-sm leading-6 text-slate-600">
                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-green-500"></span>
                        <span>{{ $metric }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>


{{-- Delivery information --}}
@if(
    $initiative->phase ||
    $initiative->investment ||
    $initiative->effort ||
    $initiative->impact
)
    <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">

        @if($initiative->phase)
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase text-slate-400">
                    Phase
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $initiative->phase }}
                </p>
            </div>
        @endif

        @if($initiative->investment)
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase text-slate-400">
                    Investment
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $initiative->investment }}
                </p>
            </div>
        @endif

        @if($initiative->effort)
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase text-slate-400">
                    Effort
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ ucfirst($initiative->effort) }}
                </p>
            </div>
        @endif

        @if($initiative->impact)
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase text-slate-400">
                    Impact
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ ucfirst($initiative->impact) }}
                </p>
            </div>
        @endif

    </div>
@endif


{{-- Dependencies --}}
@if(!empty($initiative->dependencies))
    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
            Dependencies
        </p>

        <ul class="mt-3 space-y-2">
            @foreach($initiative->dependencies as $dependency)
                <li class="flex gap-2 text-sm leading-6 text-slate-600">
                    <span>•</span>
                    <span>{{ $dependency }}</span>
                </li>
            @endforeach
        </ul>

    </div>
@endif


{{-- Standard reference --}}
@if($initiative->standard_reference)
    <div class="mt-5">
        <span class="inline-flex rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700">
            {{ $initiative->standard_reference }}
        </span>
    </div>
@endif


{{-- Consultant-specific guidance --}}
@if($initiative->consultant_guidance)
    <div class="mt-6 rounded-xl border border-yellow-200 bg-yellow-50 p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-yellow-700">
            Expert Guidance
        </p>

        <p class="mt-2 text-sm leading-6 text-slate-700">
            {{ $initiative->consultant_guidance }}
        </p>

    </div>
@endif
            </div>

        </article>

    @empty

        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <p class="text-sm text-slate-500">
                No finalized initiatives are available.
            </p>
        </div>

    @endforelse

</div>
{{-- Overall Expert Guidance --}}
@if($finalRoadmap->consultant_notes || $finalRoadmap->risks_dependencies)

    <div class="border-t border-slate-200 bg-slate-50 px-6 py-8 md:px-8">

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                Expert Review
            </p>

            <h3 class="mt-2 text-2xl font-bold text-slate-950">
Expert Guidance            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Final implementation guidance and considerations provided during expert review.
            </p>
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-2">

            @if($finalRoadmap->consultant_notes)
                <div class="rounded-2xl border border-slate-200 bg-white p-6">

                    <p class="text-sm font-bold text-slate-950">
                        Implementation Guidance
                    </p>

                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $finalRoadmap->consultant_notes }}</p>

                </div>
            @endif


            @if($finalRoadmap->risks_dependencies)
                <div class="rounded-2xl border border-slate-200 bg-white p-6">

                    <p class="text-sm font-bold text-slate-950">
                        Risks & Dependencies
                    </p>

                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $finalRoadmap->risks_dependencies }}</p>

                </div>
            @endif

               </div>

    </div>

@endif


{{-- Final roadmap download --}}
<div class="border-t border-slate-200 bg-white px-6 py-8 md:px-8">

    <div class="flex flex-col gap-5 rounded-2xl border border-slate-200 bg-slate-50 p-6
                md:flex-row md:items-center md:justify-between">

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                Final Deliverable
            </p>

            <h3 class="mt-2 text-xl font-bold text-slate-950">
                Your Expert-Reviewed Transformation Roadmap
            </h3>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Download your finalized roadmap including prioritized initiatives,
                implementation actions, success metrics and expert guidance.
            </p>
        </div>

        <a
            href="{{ route('assessment.transformation.roadmap.pdf', $assessment) }}"
            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl
                   bg-slate-950 px-5 py-3 text-sm font-bold text-white transition
                   hover:bg-yellow-400 hover:text-slate-950"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                class="h-5 w-5"
                aria-hidden="true"
            >
                <path d="M12 3v12"></path>
                <path d="m7 10 5 5 5-5"></path>
                <path d="M5 21h14"></path>
            </svg>

            Download Roadmap PDF
        </a>

    </div>

</div>


</section>

@endif


</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---------------------------------------------------------
    // SCROLL PROGRESS BAR + BACK-TO-TOP VISIBILITY
    // ---------------------------------------------------------
    const scrollProgress = document.getElementById('scroll-progress');
    const backToTop = document.getElementById('back-to-top');

    function updateScrollUI() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;

        if (scrollProgress) {
            scrollProgress.style.width = progress + '%';
        }

        if (backToTop) {
            const visible = scrollTop > 400;
            backToTop.classList.toggle('opacity-0', !visible);
            backToTop.classList.toggle('opacity-100', visible);
            backToTop.classList.toggle('pointer-events-none', !visible);
        }
    }

    window.addEventListener('scroll', updateScrollUI, { passive: true });
    updateScrollUI();

    // ---------------------------------------------------------
    // AI SUMMARY + ROADMAP — submit without reloading the page
    // ---------------------------------------------------------
    document.querySelectorAll('[data-ai-form]').forEach(function (form) {

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const button = form.querySelector('[data-ai-submit]');
            const label = form.querySelector('[data-ai-label]');
            const originalButtonHtml = button ? button.innerHTML : '';

            if (button) {
                button.disabled = true;
            }

            if (label) {
                label.textContent = 'Analyzing…';
            }

            try {

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'Something went wrong. Please try again.'
                    );
                }

                // ---------------------------------------------
                // AI SUMMARY
                // ---------------------------------------------
                if (form.action.includes('ai-summary')) {

                    const container =
                        document.getElementById('ai-summary-results');

                    if (container && data.html) {
                        container.innerHTML = data.html;
                    }
                }

                // ---------------------------------------------
                // ROADMAP
                // ---------------------------------------------
                if (form.action.includes('roadmap')) {

                    const container =
                        document.getElementById('roadmap-results');

                    if (container && data.html) {
                        container.innerHTML = data.html;
                    }

                    // Hide planning form again after generation
                    const planningForm =
                        document.getElementById('roadmap-planning-form');

                    if (planningForm) {
                        planningForm.classList.add('hidden');
                    }
                }

            } catch (error) {

                console.error(error);

                alert(
                    error.message ||
                    'The AI request could not be completed. Please try again.'
                );

            } finally {

                if (button) {
                    button.disabled = false;
                    button.innerHTML = originalButtonHtml;
                }
            }
        });
    });


    // ---------------------------------------------------------
    // ROADMAP PLANNING FORM
    // ---------------------------------------------------------
    const planningForm =
        document.getElementById('roadmap-planning-form');

    document
        .getElementById('toggle-roadmap-form')
        ?.addEventListener('click', function () {

            planningForm?.classList.toggle('hidden');

            if (!planningForm?.classList.contains('hidden')) {
                planningForm.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });


    document
        .getElementById('cancel-roadmap-form')
        ?.addEventListener('click', function () {

            planningForm?.classList.add('hidden');
        });


    // ---------------------------------------------------------
    // MAXIMUM 3 FOCUS AREAS
    // ---------------------------------------------------------
    const MAX_FOCUS_AREAS = 3;

    const focusCheckboxes =
        document.querySelectorAll('.focus-checkbox');


    function refreshFocusChipStyles() {

        focusCheckboxes.forEach(function (checkbox) {

            const chip =
                checkbox.closest('.focus-chip');

            if (!chip) return;

            chip.classList.toggle(
                'border-yellow-400',
                checkbox.checked
            );

            chip.classList.toggle(
                'bg-yellow-50',
                checkbox.checked
            );

            chip.classList.toggle(
                'text-yellow-700',
                checkbox.checked
            );

            chip.classList.toggle(
                'border-slate-200',
                !checkbox.checked
            );

            chip.classList.toggle(
                'bg-white',
                !checkbox.checked
            );

            chip.classList.toggle(
                'text-slate-600',
                !checkbox.checked
            );
        });
    }


    focusCheckboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            const checkedCount =
                Array.from(focusCheckboxes)
                    .filter(c => c.checked)
                    .length;

            if (checkedCount > MAX_FOCUS_AREAS) {
                this.checked = false;
            }

            refreshFocusChipStyles();
        });
    });

});
</script>

@if($dimensionScores->count() >= 3)

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const radarLabels = @json($dimensionScores->keys()->values());

        const radarValues = @json(
            $dimensionScores
                ->values()
                ->map(fn ($score) => round($score, 2))
                ->values()
        );

        const rankedLabels = @json($rankedDimensionScores->keys()->values());

        const rankedValues = @json(
            $rankedDimensionScores
                ->values()
                ->map(fn ($score) => round($score, 2))
                ->values()
        );

        const radarCanvas = document.getElementById('dimensionRadarChart');

        if (radarCanvas) {
            new Chart(radarCanvas, {
                type: 'radar',

                data: {
                    labels: radarLabels,

                   datasets: [{
    label: 'Organization maturity',
    data: radarValues,

    backgroundColor: 'rgba(234,179,8,0.18)',
    borderColor: '#eab308',

    fill: true,

    borderWidth: 3,

    pointBackgroundColor: '#eab308',
    pointBorderColor: '#ffffff',
    pointBorderWidth: 2,

    pointRadius: 5,
    pointHoverRadius: 7
}]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            beginAtZero: true,
                            suggestedMin: 0,
suggestedMax: 100,
 grid: {
    color: '#e2e8f0'
},

angleLines: {
    color: '#e2e8f0'
},
                            ticks: {
                               display: false
                            },

                          pointLabels: {
    color: '#334155',

    centerPointLabels: false,

    font: {
        size: 13,
        weight: '600'
    },

    padding: 2
}
                        }
                    },

                    plugins: {
                        legend: {
    display: false
                        },

                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label
                                        + ': '
                                        + Number(context.raw).toFixed(1)
                                        + ' / 100';
                                }
                            }
                        }
                    }
                }
            });
        }

      const barCanvas = document.getElementById('dimensionBarChart');

if (barCanvas) {
    new Chart(barCanvas, {
        type: 'bar',

        data: {
            labels: rankedLabels,

            datasets: [{
                label: 'Capability score',
                data: rankedValues,

                backgroundColor: function(context) {
                    const value = context.raw;

                    if (value < 20) {
                        return '#ef4444';
                    }

                    if (value < 40) {
                        return '#f97316';
                    }

                    if (value < 60) {
                        return '#facc15';
                    }

                    if (value < 80) {
                        return '#3b82f6';
                    }

                    return '#22c55e';
                },

                borderWidth: 0,
                borderRadius: 10,
                borderSkipped: false,
                barThickness: 20,
                maxBarThickness: 24
            }]
        },

        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,

            layout: {
                padding: {
                    top: 4,
                    right: 10,
                    bottom: 4,
                    left: 4
                }
            },

            scales: {
                x: {
                    min: 0,
                    max: 100,
                    beginAtZero: true,

                    border: {
                        display: false
                    },

                    grid: {
                        color: '#e2e8f0',
                        drawTicks: false
                    },

                    ticks: {
                        stepSize: 20,
                        color: '#64748b',
                        padding: 10,

                        font: {
                            size: 11,
                            weight: '600'
                        },

                        callback: function(value) {
                            return value;
                        }
                    }
                },

                y: {
                    border: {
                        display: false
                    },

                    grid: {
                        display: false
                    },

                    ticks: {
                        autoSkip: false,
                        color: '#334155',
                        padding: 10,

                        font: {
                            size: 11,
                            weight: '600'
                        }
                    }
                }
            },

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    displayColors: false,
                    backgroundColor: '#020617',
                    titleColor: '#ffffff',
                    bodyColor: '#cbd5e1',
                    padding: 12,
                    cornerRadius: 10,

                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },

                        label: function(context) {
                            return 'Score: ' + Number(context.raw).toFixed(1) + ' / 100';
                        }
                    }
                }
            },

            animation: {
                duration: 700,
                easing: 'easeOutQuart'
            }
        }
    });
}
    </script>

@endif
</div>
{{-- ========================================================= --}}
{{-- FLOATING NAVIGATION --}}
{{-- Rebuilt as solid circular buttons (white bg, border, shadow) so they --}}
{{-- stay readable no matter what's behind them — including the dark --}}
{{-- footer, where the old bare grey icons were nearly invisible. --}}
{{-- ========================================================= --}}

{{-- Dashboard --}}
<a href="{{ route('dashboard') }}"
   aria-label="Dashboard"
   title="Back to dashboard"
   class="fixed left-5 top-1/2 z-50 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-lg shadow-slate-900/10 transition-all duration-200 hover:-translate-x-1 hover:border-yellow-300 hover:text-yellow-600 print:hidden">

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


{{-- Back to top — only appears once you've scrolled, so it isn't dead
     weight sitting near the top of the page --}}
<a href="#page-top"
   aria-label="Back to top"
   title="Back to top"
   id="back-to-top"
   class="fixed bottom-7 right-7 z-50 flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 opacity-0 shadow-lg shadow-slate-900/10 transition-all duration-200 pointer-events-none hover:-translate-y-1 hover:border-yellow-300 hover:text-yellow-600 print:hidden">

    <svg class="h-5 w-5"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M5 15l7-7 7 7"/>
    </svg>
</a>

<footer class="relative left-1/2 mt-10 w-screen -translate-x-1/2 border-t-4 border-yellow-400 bg-slate-950 text-slate-300 print:hidden">    <div class="relative mx-auto max-w-7xl overflow-hidden px-6 py-10 md:px-8">

        {{-- Subtle decorative glow, same accent color, kept very low-opacity
             so it reads as texture rather than a competing element --}}
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-24 left-1/4 h-72 w-72 rounded-full bg-yellow-400/10 blur-3xl"></div>
            <div class="absolute -top-10 right-1/4 h-56 w-56 rounded-full bg-yellow-400/5 blur-3xl"></div>
        </div>

        <div class="grid gap-10 md:grid-cols-4">

            {{-- BRAND --}}
            <div>

                <div class="flex items-center gap-3">

                    <img
                        src="{{ asset('assets/yellomind_logo.png') }}"
                        class="h-10 w-10 object-contain"
                        alt="Yellomind Logo"
                    >

                    <div>
                        <h3 class="text-base font-bold text-white">
                            YARA Platform
                        </h3>

                        <p class="mt-1 text-sm text-slate-400">
                            AI Readiness Assessment
                        </p>
                    </div>

                </div>

                <p class="mt-5 max-w-xs text-sm leading-6 text-slate-400">
                    YARA helps organizations evaluate AI maturity,
                    governance, readiness and transformation capabilities.
                </p>

            </div>


            {{-- PLATFORM --}}
<div>

    <h3 class="text-base font-semibold text-white">
        Platform
    </h3>

    <div class="mt-4 flex flex-col gap-3 text-sm text-slate-400">

        <a href="{{ route('dashboard') }}"
           class="w-fit transition hover:text-yellow-400">
            Dashboard
        </a>

        <a href="{{ route('assessment.start') }}"
           class="w-fit transition hover:text-yellow-400">
            AI Readiness Assessment
        </a>

        <a href="{{ route('transformation.start') }}"
           class="w-fit transition hover:text-yellow-400">
            Transformation Roadmap
        </a>

    </div>

</div>
            {{-- RESOURCES --}}
<div>

    <h3 class="text-base font-semibold text-white">
        Resources
    </h3>

    <div class="mt-4 flex flex-col gap-3 text-sm text-slate-400">

        <a href="{{ url('/') }}#process"
           class="w-fit transition hover:text-yellow-400">
            How it works
        </a>

        <a href="{{ url('/') }}#engagements"
           class="w-fit transition hover:text-yellow-400">
            Delivery formats
        </a>

        <a href="{{ url('/') }}#faq"
           class="w-fit transition hover:text-yellow-400">
            FAQ
        </a>

    </div>

</div>


            {{-- CONTACT --}}
            <div>

                <h3 class="text-base font-semibold text-white">
                    Contact
                </h3>

                <div class="mt-4 flex flex-col gap-3 text-sm text-slate-400">

                    <a
                        href="https://yellomind.com"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-fit transition hover:text-yellow-400"
                    >
                        Yellomind Consulting
                    </a>

                    <a
                        href="mailto:info@yellomind.com"
                        class="w-fit transition hover:text-yellow-400"
                    >
                        info@yellomind.com
                    </a>

                    <a
                        href="mailto:info@yellomind.com?subject=YARA%20Assessment%20Enquiry"
                        class="w-fit transition hover:text-yellow-400"
                    >
                        Speak to Yellomind
                    </a>

                </div>

            </div>

        </div>


        {{-- LOWER FOOTER --}}
        <div class="mt-10 flex flex-col justify-between gap-4 border-t border-slate-800 pt-6
                    text-sm text-slate-500 md:flex-row md:items-center">

            <p>
                © {{ date('Y') }} YARA Platform. All rights reserved.
            </p>

            <div class="flex items-center gap-7">

                <a href="#"
                   class="transition hover:text-yellow-400">
                    Privacy Policy
                </a>

                <a href="#"
                   class="transition hover:text-yellow-400">
                    Terms & Conditions
                </a>

               

            </div>

        </div>

    </div>

</footer>
@endsection