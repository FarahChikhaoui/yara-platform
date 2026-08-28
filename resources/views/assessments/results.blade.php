@extends('layouts.yara')
@section('content')
<div id="page-top"></div>

{{-- Slim scroll-progress indicator, matches the yellow/amber accent --}}
<div id="scroll-progress" class="fixed left-0 top-0 z-[60] h-1 w-0 bg-gradient-to-r from-yellow-400 via-amber-400 to-orange-500 shadow-[0_0_10px_rgba(250,204,21,0.55)] transition-[width] duration-150 ease-out print:hidden"></div>

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
    <section class="relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04),0_24px_48px_-32px_rgba(15,23,42,0.25)]">

        <div class="relative border-b border-slate-200/80 bg-gradient-to-r from-slate-50 via-white to-slate-50 px-6 py-4 md:px-8">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex flex-wrap items-center gap-3">

                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-yellow-100 to-amber-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-amber-700 ring-1 ring-inset ring-amber-300/60">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        YARA Executive Report
                    </span>

                    <span class="text-sm text-slate-400">
                        Assessment ID #{{ $assessment->id }}
                    </span>

                </div>

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex w-fit items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-100 hover:shadow-md active:translate-y-0"
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

        <div class="relative px-6 py-8 md:px-8 md:py-10">

            <div class="pointer-events-none absolute -right-24 -top-24 h-64 w-64 rounded-full bg-yellow-300/10 blur-3xl"></div>

            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">

               <div class="relative max-w-4xl">

    <p class="flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.18em] text-yellow-600">
        <span class="h-px w-6 bg-gradient-to-r from-yellow-500 to-transparent"></span>
        Executive AI Readiness Report
    </p>

    <h1 class="mt-3 text-3xl font-bold leading-tight tracking-tight text-slate-950 md:text-4xl">
        {{ $assessment->title }}
    </h1>

    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-500">
    Executive assessment of organizational AI maturity, capability performance
    and priority transformation actions.
</p>

</div>

                <div class="grid min-w-full gap-3 sm:grid-cols-3 lg:min-w-[440px]">

                    <div class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-yellow-300/70 hover:bg-white hover:shadow-md">

                        <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M6 21V7a1 1 0 011-1h4a1 1 0 011 1v14M14 21V4a1 1 0 011-1h4a1 1 0 011 1v17M9 9h.01M9 13h.01M9 17h.01"/></svg>
                            Organization
                        </p>

                        <p class="mt-2 truncate font-bold text-slate-950">
                            {{ $assessment->company->name ?? 'Not specified' }}
                        </p>

                    </div>

                    <div class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-yellow-300/70 hover:bg-white hover:shadow-md">

                        <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Country
                        </p>

                        <p class="mt-2 truncate font-bold text-slate-950">
                            {{ $assessment->company->country ?? 'Not specified' }}
                        </p>

                    </div>

                    <div class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-yellow-300/70 hover:bg-white hover:shadow-md">

                        <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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
        <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 px-5 py-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500 text-sm font-bold text-white shadow-sm shadow-amber-500/30">
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
<section class="relative overflow-hidden rounded-3xl bg-slate-950 text-white shadow-[0_24px_60px_-24px_rgba(2,6,23,0.55)] ring-1 ring-white/5">

    <div class="pointer-events-none absolute inset-0 opacity-[0.35]" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.06) 1px, transparent 0); background-size: 22px 22px;"></div>
    <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-yellow-400/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -right-20 h-80 w-80 rounded-full bg-amber-500/10 blur-3xl"></div>

    <div class="relative grid grid-cols-1 xl:grid-cols-[330px_1fr]">

        {{-- Combined score --}}
        <div class="flex flex-col items-center justify-center border-b border-slate-800/80 p-8 xl:border-b-0 xl:border-r xl:border-slate-800/80">

            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">
                Combined YARA Score
            </p>

            <div class="relative mt-6">

                <div class="absolute inset-0 -z-10 rounded-full bg-yellow-400/20 blur-2xl"></div>

                <div
                    class="relative flex h-56 w-56 items-center justify-center rounded-full shadow-[0_0_0_1px_rgba(255,255,255,0.06)]"
                    style="
                        background:
                        conic-gradient(
                            #facc15 0deg {{ $scoreAngle }}deg,
                            #1e293b {{ $scoreAngle }}deg 360deg
                        );
                    "
                >
                    <div class="flex h-44 w-44 flex-col items-center justify-center rounded-full bg-slate-950 shadow-[inset_0_2px_12px_rgba(0,0,0,0.4)]">

                        <span class="text-6xl font-bold tracking-tight text-white drop-shadow-[0_0_18px_rgba(250,204,21,0.25)]">
                            {{ number_format($combinedScore, 1) }}
                        </span>

                        <span class="mt-2 text-sm text-slate-400">
                            out of 100
                        </span>

                    </div>
                </div>
            </div>

            @if($maturityLevel)
                <div class="mt-6 text-center">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Current maturity level
                    </p>

                    <span class="mt-3 inline-flex rounded-full bg-gradient-to-r from-yellow-400 to-amber-400 px-4 py-2 text-sm font-bold text-slate-950 shadow-md shadow-yellow-400/20">
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

            <h2 class="mt-2 text-3xl font-bold tracking-tight">
                Assessment Overview
            </h2>

        </div>

        <div class="rounded-2xl border border-slate-700/80 bg-slate-900/80 px-6 py-4 shadow-inner shadow-black/20 backdrop-blur">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Overall Status
            </p>

            <p class="mt-2 text-xl font-bold text-yellow-400">
                {{ $balanceLabel }}
            </p>

        </div>

    </div>

<div class="mt-6">
    <div class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-900/70 px-5 py-4 backdrop-blur">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                Current Maturity
            </p>

            <p class="mt-1 text-sm text-slate-400">
                Overall organizational maturity level
            </p>
        </div>

        <div class="text-right">
            @if($maturityLevel)
                <p class="text-xl font-bold text-white">
                    Level {{ $maturityLevel->level }}
                </p>

                <p class="mt-1 text-sm font-semibold text-yellow-400">
                    {{ $maturityLevel->label ?? $maturityLevel->name }}
                </p>
            @else
                <p class="text-xl font-bold text-white">—</p>
            @endif
        </div>

    </div>
</div>

            {{-- Main KPI cards --}}
            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <div class="group relative overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900 p-6 transition-all duration-200 hover:-translate-y-1 hover:border-yellow-400/40 hover:shadow-xl hover:shadow-black/30">

                    <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-yellow-400/0 blur-2xl transition group-hover:bg-yellow-400/10"></div>

                    <div class="relative flex items-start justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Organization score
                        </p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-400/10 transition group-hover:bg-yellow-400/20">
                            <svg class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m4 0v-5a2 2 0 00-2-2h0a2 2 0 00-2 2v5m-2-12h.01M12 7h.01M16 7h.01M8 11h.01M12 11h.01M16 11h.01"/>
                            </svg>
                        </div>
                    </div>

                    <p class="relative mt-3 text-4xl font-bold tracking-tight">
                        {{ number_format($organizationScore, 1) }}
                    </p>

                    <p class="relative mt-2 text-sm text-slate-400">
                        Internal organizational AI maturity
                    </p>

                </div>

                <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900 p-6 transition-all duration-200 hover:-translate-y-1 hover:border-yellow-400/40 hover:shadow-xl hover:shadow-black/30">

    <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-blue-400/0 blur-2xl transition group-hover:bg-blue-400/10"></div>

    <div class="relative flex items-start justify-between">
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

        <p class="relative mt-3 text-4xl font-bold tracking-tight">
            {{ number_format($countryScore, 1) }}
        </p>

        <p class="relative mt-2 text-sm text-slate-400">
            {{ $assessment->company->country ?? 'Country' }}
            · {{ $assessment->country_ai_year }}
        </p>

        <a
            href="{{ route('assessment.country', $assessment) }}"
            class="relative mt-5 inline-flex w-fit items-center gap-2 text-sm font-semibold text-yellow-400 transition hover:gap-3 hover:text-yellow-300"
        >
            Explore country intelligence

            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                class="h-4 w-4 transition-transform"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 12h14m-6-6 6 6-6 6"
                />
            </svg>
        </a>

    @else

        <p class="relative mt-4 text-sm font-semibold text-slate-400">
            Unavailable
        </p>

    @endif

</div>

                <div class="group relative overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900 p-6 transition-all duration-200 hover:-translate-y-1 hover:border-yellow-400/40 hover:shadow-xl hover:shadow-black/30">

                    <div class="relative flex items-start justify-between">
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

                        <p class="relative mt-3 text-4xl font-bold tracking-tight {{ $countryGap >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $countryGap >= 0 ? '+' : '' }}
                            {{ number_format($countryGap, 1) }}
                        </p>

                        <p class="relative mt-2 text-sm text-slate-400">
                            {{ $countryGap >= 0 ? 'Above' : 'Below' }}
                            national benchmark
                        </p>

                    @else

                        <p class="relative mt-4 text-sm font-semibold text-slate-400">
                            Unavailable
                        </p>

                    @endif

                </div>

                <div class="group relative overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900 p-6 transition-all duration-200 hover:-translate-y-1 hover:border-yellow-400/40 hover:shadow-xl hover:shadow-black/30">

                    <div class="relative flex items-start justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Defined capabilities
                        </p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-400/10 transition group-hover:bg-slate-400/20">
                            <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <p class="relative mt-3 text-4xl font-bold tracking-tight">
                        {{ $definedCapabilityCount }}

                        <span class="text-lg text-slate-500">
                            / {{ $assessedDimensionTotal }}
                        </span>
                    </p>

                    <p class="relative mt-2 text-sm text-slate-400">
                        At maturity Level 3 or above
                    </p>

                </div>

            </div>

            {{-- Strategic insights --}}
            <div class="mt-8 border-t border-slate-800/80 pt-8">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                    <div class="rounded-2xl border border-slate-800/60 bg-slate-900 p-5 transition hover:border-green-400/30">

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

                    <div class="rounded-2xl border border-slate-800/60 bg-slate-900 p-5 transition hover:border-red-400/30">

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

                    <div class="rounded-2xl border border-slate-800/60 bg-slate-900 p-5 transition hover:border-yellow-400/30">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Maturity consistency
                        </p>

                        <p class="mt-3 text-2xl font-bold tracking-tight">
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
            <div class="rounded-3xl border border-slate-200/70 bg-white p-8 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)] transition hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_28px_48px_-28px_rgba(15,23,42,0.3)]">

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3 7 7 1-5.5 5 1.5 7-6-3.5L6 22l1.5-7L2 10l7-1 3-7z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-950">
                            AI Capability Landscape
                        </h2>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Overall shape and balance across assessed capabilities.
                        </p>
                    </div>
                </div>

                <div class="mt-6" style="height: 360px;">
                    <canvas id="dimensionRadarChart"></canvas>
                </div>

            </div>

            {{-- Ranked horizontal bar chart --}}
            <div class="rounded-3xl border border-slate-200/70 bg-white p-8 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)] transition hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_28px_48px_-28px_rgba(15,23,42,0.3)]">

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9m6 10V5m6 14v-7m4 7H2"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-950">
                            Capability Ranking
                        </h2>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Assessed dimensions ranked from strongest to weakest.
                        </p>
                    </div>
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
    <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]">

        <div class="border-b border-slate-200/80 bg-gradient-to-b from-green-50/40 to-transparent px-7 py-6">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-green-700">
                        Strategic Strengths
                    </p>

                    <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">
                        Best-Performing Capabilities
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        The capabilities currently providing the strongest foundation for AI transformation.
                    </p>
                </div>

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-green-100 to-green-50 text-green-700 shadow-sm">
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

                <article class="group rounded-2xl border border-slate-200 bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-green-200 hover:bg-green-50/40 hover:shadow-md">

                    <div class="flex items-start gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-950 text-sm font-bold text-white shadow-sm transition group-hover:bg-green-600">
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
                                    class="h-full rounded-full bg-gradient-to-r from-green-500 to-green-400 shadow-[0_0_8px_rgba(34,197,94,0.4)] transition-all duration-700 ease-out"
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
    <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]">

        <div class="border-b border-slate-200/80 bg-gradient-to-b from-red-50/40 to-transparent px-7 py-6">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-red-700">
                        Improvement Priorities
                    </p>

                    <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">
                        Capabilities Requiring Action
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        The weakest capabilities that should receive the earliest executive attention.
                    </p>
                </div>

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-red-100 to-red-50 text-red-700 shadow-sm">
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

                <article class="group rounded-2xl border border-slate-200 bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-red-200 hover:bg-red-50/30 hover:shadow-md">

                    <div class="flex items-start gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white shadow-sm">
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
                                    class="h-full rounded-full bg-gradient-to-r from-red-500 to-red-400 shadow-[0_0_8px_rgba(239,68,68,0.4)] transition-all duration-700 ease-out"
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
<section class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]">

    {{-- Section heading --}}
    <div class="border-b border-slate-200/80 bg-slate-50/60 px-7 py-6 md:px-8">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                    Capability Analysis
                </p>

                <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">
                    Detailed Assessment Results
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Maturity score, classification and relative performance for each assessed capability.
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
                <span class="h-2 w-2 rounded-full bg-yellow-400"></span>

                {{ $rankedDimensionScores->count() }}
                {{ $rankedDimensionScores->count() === 1 ? 'capability' : 'capabilities' }}
            </div>

        </div>

    </div>

    {{-- Capability table --}}
    <div class="divide-y divide-slate-100">

        @foreach($rankedDimensionScores as $dimension => $score)

            @php
                $level = $dimensionMaturityLevels[$dimension] ?? null;

                if ($score < 20) {
                    $statusLabel = 'Critical';
                    $statusClass = 'bg-red-100 text-red-700';
                    $progressClass = 'bg-red-500';
                    $edgeClass = 'group-hover:border-l-red-400';
                } elseif ($score < 40) {
                    $statusLabel = 'Needs attention';
                    $statusClass = 'bg-orange-100 text-orange-700';
                    $progressClass = 'bg-orange-500';
                    $edgeClass = 'group-hover:border-l-orange-400';
                } elseif ($score < 60) {
                    $statusLabel = 'Developing';
                    $statusClass = 'bg-yellow-100 text-yellow-700';
                    $progressClass = 'bg-yellow-400';
                    $edgeClass = 'group-hover:border-l-yellow-400';
                } elseif ($score < 80) {
                    $statusLabel = 'Established';
                    $statusClass = 'bg-blue-100 text-blue-700';
                    $progressClass = 'bg-blue-500';
                    $edgeClass = 'group-hover:border-l-blue-400';
                } else {
                    $statusLabel = 'Advanced';
                    $statusClass = 'bg-green-100 text-green-700';
                    $progressClass = 'bg-green-500';
                    $edgeClass = 'group-hover:border-l-green-400';
                }
            @endphp

            <article class="group border-l-4 border-l-transparent px-7 py-6 transition-all duration-200 hover:bg-slate-50 {{ $edgeClass }} md:px-8">

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
                                class="h-full rounded-full {{ $progressClass }} transition-all duration-700 ease-out"
                                style="width: {{ min(100, max(0, $score)) }}%;"
                            ></div>

                        </div>

                    </div>

                    {{-- Score --}}
                    <div class="flex items-baseline lg:justify-end">

                        <span class="text-3xl font-bold tracking-tight text-slate-950">
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




    {{-- =====================================================
         SELF-ASSESSMENT → TRANSFORMATION UPGRADE
    ====================================================== --}}

   <section
    id="roadmap"
    class="overflow-hidden rounded-3xl border border-slate-200/70 bg-slate-950 text-white shadow-[0_24px_60px_-24px_rgba(2,6,23,0.55)] ring-1 ring-white/5"
>
    <div class="px-6 py-8 md:px-8 md:py-10">

        <div class="flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">

            {{-- =====================================================
                 NO TRANSFORMATION REQUEST YET
            ====================================================== --}}
@if(
    empty($assessment->transformation_status)
    && !$assessment->roadmapPreference
)
                <div class="relative w-full overflow-hidden rounded-2xl border border-yellow-400/20 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 p-8 shadow-2xl shadow-yellow-400/5 md:p-10">

    <div class="pointer-events-none absolute -top-24 -right-24 h-64 w-64 rounded-full bg-yellow-400/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 -left-16 h-56 w-56 rounded-full bg-amber-500/5 blur-3xl"></div>

    <div class="relative flex flex-col gap-8 md:flex-row md:items-center md:justify-between">

        <div class="max-w-3xl">

            <p class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-yellow-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                From Diagnosis to Action
            </p>

            <h2 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">
                Ready to move from diagnosis to action?
            </h2>

            <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                Turn your assessment findings into a structured transformation
                roadmap aligned with your priorities, target maturity,
                timeline and investment capacity.
            </p>

            <div class="mt-5 flex items-start gap-2 text-sm leading-6 text-slate-400">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-yellow-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Your completed assessment will be reused — you will not need to take the assessment again.</span>
            </div>

        </div>

        <div class="shrink-0">

            <form
                method="POST"
                action="{{ route('transformation.from-assessment', $assessment) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-yellow-400 to-amber-400 px-7 py-3.5 font-bold text-slate-950 shadow-lg shadow-yellow-400/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-yellow-400/30 md:w-auto"
                >
                    Build Your Transformation Roadmap
                    <span class="transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">→</span>
                </button>

            </form>

        </div>

    </div>

</div>


            {{-- =====================================================
                 TRANSFORMATION PLANNING STARTED
            ====================================================== --}}
@elseif(
    $assessment->payment_status !== 'paid'
    && $assessment->roadmapPreference
)
                <div class="max-w-3xl">

                    <p class="text-sm font-semibold uppercase tracking-wider text-yellow-400">
                        Transformation Roadmap
                    </p>

                    <h2 class="mt-3 text-2xl font-bold tracking-tight md:text-3xl">
                        Continue building your transformation brief
                    </h2>

                    <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                        Your assessment results are ready. Complete your transformation
                        priorities, target maturity, timeline and investment capacity
                        before submitting your request for expert review.
                    </p>

                    

                </div>

                <div class="shrink-0">

                    <a
href="{{ route('transformation.brief', $assessment) }}"                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-yellow-400 to-amber-400 px-6 py-3 font-bold text-slate-950 shadow-md shadow-yellow-400/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-yellow-400/30"
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

                    <h2 class="mt-3 text-2xl font-bold tracking-tight md:text-3xl">
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
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-yellow-400 to-amber-400 px-6 py-3 font-bold text-slate-950 shadow-md shadow-yellow-400/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-yellow-400/30"
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

                    <h2 class="mt-3 text-2xl font-bold tracking-tight md:text-3xl">
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
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-yellow-400 to-amber-400 px-6 py-3 font-bold text-slate-950 shadow-md shadow-yellow-400/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-yellow-400/30"
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

                    <h2 class="mt-3 text-2xl font-bold tracking-tight md:text-3xl">
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
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-yellow-400 to-amber-400 px-6 py-3 font-bold text-slate-950 shadow-md shadow-yellow-400/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-yellow-400/30"
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

                    <h2 class="mt-3 text-2xl font-bold tracking-tight md:text-3xl">
                        Track your transformation request
                    </h2>

                    <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                        Your transformation request is currently being processed.
                    </p>

                </div>

                <div class="shrink-0">

                    <a
                        href="{{ route('assessment.transformation.submitted', $assessment) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-yellow-400 to-amber-400 px-6 py-3 font-bold text-slate-950 shadow-md shadow-yellow-400/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-yellow-400/30"
                    >
                        View Request Status
                        <span class="ml-2">→</span>
                    </a>

                </div>

            @endif

                </div>

    </div>
</section>

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
        class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04),0_24px_48px_-32px_rgba(15,23,42,0.25)]"
    >

   @php
    $finalRoadmap = $assessment->transformationRoadmap;
    $finalInitiatives = $finalRoadmap->initiatives;
@endphp

{{-- Header --}}
<div class="relative overflow-hidden border-b border-slate-800 bg-slate-950 px-6 py-8 text-white md:px-8">

    <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-yellow-400/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-yellow-400">
                Expert-Reviewed Transformation Plan
            </p>

            <h2 class="mt-2 text-3xl font-bold tracking-tight">
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

            <span class="inline-flex items-center rounded-full bg-gradient-to-r from-yellow-400 to-amber-400 px-4 py-2 text-sm font-bold text-slate-950 shadow-sm">
                {{ $finalInitiatives->count() }}
                {{ $finalInitiatives->count() === 1 ? 'Initiative' : 'Initiatives' }}
            </span>

        </div>

    </div>

</div>


{{-- Roadmap overview --}}
<div class="border-b border-slate-200/80 bg-slate-50/60 px-6 py-6 md:px-8">

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Status
            </p>

            <p class="mt-2 font-bold text-green-700">
                Roadmap Ready
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Initiatives
            </p>

            <p class="mt-2 text-2xl font-bold tracking-tight text-slate-950">
                {{ $finalInitiatives->count() }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Finalized
            </p>

            <p class="mt-2 font-bold text-slate-950">
                {{ optional($finalRoadmap->finalized_at)->format('d M Y') }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
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

        <h3 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">
            Prioritized Initiatives
        </h3>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Recommended initiatives ordered according to your transformation priorities.
        </p>
    </div>

    @forelse($finalInitiatives as $initiative)

        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">

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

                            <h4 class="mt-1 text-xl font-bold tracking-tight text-slate-950">
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
            <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-100">
                <p class="text-xs font-bold uppercase text-slate-400">
                    Phase
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $initiative->phase }}
                </p>
            </div>
        @endif

        @if($initiative->investment)
            <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-100">
                <p class="text-xs font-bold uppercase text-slate-400">
                    Investment
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $initiative->investment }}
                </p>
            </div>
        @endif

        @if($initiative->effort)
            <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-100">
                <p class="text-xs font-bold uppercase text-slate-400">
                    Effort
                </p>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ ucfirst($initiative->effort) }}
                </p>
            </div>
        @endif

        @if($initiative->impact)
            <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-100">
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
        <span class="inline-flex rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-100">
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

    <div class="border-t border-slate-200/80 bg-slate-50/60 px-6 py-8 md:px-8">

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                Expert Review
            </p>

            <h3 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">
Expert Guidance            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Final implementation guidance and considerations provided during expert review.
            </p>
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-2">

            @if($finalRoadmap->consultant_notes)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <p class="text-sm font-bold text-slate-950">
                        Implementation Guidance
                    </p>

                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $finalRoadmap->consultant_notes }}</p>

                </div>
            @endif


            @if($finalRoadmap->risks_dependencies)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

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
<div class="border-t border-slate-200/80 bg-white px-6 py-8 md:px-8">

    <div class="flex flex-col gap-5 rounded-2xl border border-slate-200 bg-slate-50 p-6
                md:flex-row md:items-center md:justify-between">

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                Final Deliverable
            </p>

            <h3 class="mt-2 text-xl font-bold tracking-tight text-slate-950">
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
                   bg-slate-950 px-5 py-3 text-sm font-bold text-white shadow-sm transition-all duration-200
                   hover:-translate-y-0.5 hover:bg-yellow-400 hover:text-slate-950 hover:shadow-md"
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


{{-- Back to top — only appears once you've scrolled, so it isn't dead
     weight sitting near the top of the page --}}
<a href="#page-top"
   aria-label="Back to top"
   title="Back to top"
   id="back-to-top"
   class="fixed bottom-7 right-7 z-50 flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 opacity-0 shadow-lg shadow-slate-900/10 transition-all duration-200 pointer-events-none hover:-translate-y-1 hover:scale-105 hover:border-yellow-300 hover:text-yellow-600 hover:shadow-xl print:hidden">

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

<footer class="relative left-1/2 mt-10 w-screen -translate-x-1/2 border-t-4 border-yellow-400 bg-slate-950 text-slate-300 shadow-[0_-20px_40px_-30px_rgba(0,0,0,0.6)] print:hidden">    <div class="relative mx-auto max-w-7xl overflow-hidden px-6 py-10 md:px-8">

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
           class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400">
            Dashboard
        </a>

        <a href="{{ route('assessment.start') }}"
           class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400">
            AI Readiness Assessment
        </a>

        <a href="{{ route('transformation.start') }}"
           class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400">
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
           class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400">
            How it works
        </a>

        <a href="{{ url('/') }}#engagements"
           class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400">
            Delivery formats
        </a>

        <a href="{{ url('/') }}#faq"
           class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400">
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
                        class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400"
                    >
                        Yellomind Consulting
                    </a>

                    <a
                        href="mailto:info@yellomind.com"
                        class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400"
                    >
                        info@yellomind.com
                    </a>

                    <a
                        href="mailto:info@yellomind.com?subject=YARA%20Assessment%20Enquiry"
                        class="w-fit transition hover:translate-x-0.5 hover:text-yellow-400"
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