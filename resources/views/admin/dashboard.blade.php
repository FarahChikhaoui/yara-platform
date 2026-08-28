@extends('layouts.admin')

@section('content')

<div class="space-y-8">
<div class="pointer-events-none fixed -top-24 left-1/2 -z-10 h-72 w-[36rem] -translate-x-1/2 rounded-full bg-yellow-300/10 blur-3xl"></div>
    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                </span>
                <p class="text-sm font-semibold tracking-widest text-amber-600 uppercase">
                    YARA Intelligence Center
                </p>
            </div>

            <h1 class="mt-2 text-4xl font-bold text-slate-950">
                Overview
            </h1>

            <p class="mt-2 text-lg text-slate-500">
                Monitor AI readiness activity, organizational maturity and platform insights.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-500 sm:block">
                As of <span class="font-semibold text-slate-800">{{ now()->format('M d, Y') }}</span>
            </div>

            <a href="{{ route('admin.assessments.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                View all assessments
            </a>
        </div>

    </div>


    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">

        {{-- ORGANIZATIONS --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">
                        Organizations
                    </p>

                    <p class="mt-3 text-4xl font-bold text-slate-950">
                        {{ $totalOrganizations }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-yellow-50 transition group-hover:bg-yellow-100">
                    <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m4 0v-5a2 2 0 00-2-2h0a2 2 0 00-2 2v5m-2-12h.01M12 7h.01M16 7h.01M8 11h.01M12 11h.01M16 11h.01"/>
                    </svg>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <p class="text-sm text-slate-500">
                    Registered organizations
                </p>

                @isset($organizationsTrend)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $organizationsTrend >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        <svg class="h-3 w-3 {{ $organizationsTrend < 0 ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l5 5a1 1 0 01-1.414 1.414L11 6.414V16a1 1 0 11-2 0V6.414L5.707 9.707a1 1 0 01-1.414-1.414l5-5A1 1 0 0110 3z" clip-rule="evenodd"/>
                        </svg>
                        {{ abs($organizationsTrend) }}%
                    </span>
                @endisset
            </div>

        </div>


        {{-- ASSESSMENTS --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">
                        Assessments
                    </p>

                    <p class="mt-3 text-4xl font-bold text-slate-950">
                        {{ $totalAssessments }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 transition group-hover:bg-blue-100">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>

            <div class="mt-4 flex gap-3 text-sm">
                <span class="inline-flex items-center gap-1.5 font-medium text-emerald-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    {{ $completedAssessments }} completed
                </span>

                <span class="text-slate-300">•</span>

                <span class="inline-flex items-center gap-1.5 font-medium text-amber-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    {{ $inProgressAssessments }} in progress
                </span>
            </div>

        </div>


        {{-- COMPLETION RATE --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">
                        Completion Rate
                    </p>

                    <p class="mt-3 text-4xl font-bold text-slate-950">
                        {{ $completionRate }}%
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 transition group-hover:bg-emerald-100">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <div class="mt-5 h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ min($completionRate, 100) }}%"></div>
            </div>

        </div>


        {{-- CLIENTS --}}
        <div class="group rounded-2xl bg-slate-950 p-6 text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-400 uppercase">
                        Client Users
                    </p>

                    <p class="mt-3 text-4xl font-bold">
                        {{ $totalClients }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-yellow-400 transition group-hover:bg-yellow-300">
                    <svg class="h-5 w-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>

            </div>

            <p class="mt-4 text-sm text-slate-400">
                Active platform accounts
            </p>

        </div>

    </div>


    {{-- AI READINESS INTELLIGENCE --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- MATURITY DISTRIBUTION --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">

            <div class="mb-7 flex items-start justify-between gap-4">

                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">
                        AI Readiness Intelligence
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-slate-950">
                        Organizational Maturity
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Distribution of completed assessments across maturity levels.
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Completed
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-950">
                        {{ $completedAssessments }}
                    </p>
                </div>

            </div>

            <div class="space-y-5">

                @forelse($maturityDistribution as $level)

                    @php
                        $percentage = $completedAssessments > 0
                            ? round(($level['count'] / $completedAssessments) * 100)
                            : 0;
                    @endphp

                    <div>

                        <div class="mb-2 flex items-center justify-between gap-4">

                            <div class="flex items-center gap-3">
                                <span class="font-semibold text-slate-800">
                                    {{ $level['name'] }}
                                </span>

                                <span class="text-xs text-slate-400">
                                    {{ $level['min_score'] }}–{{ $level['max_score'] }}
                                </span>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-slate-900">
                                    {{ $level['count'] }}
                                </span>

                                <span class="w-10 text-right text-xs text-slate-400">
                                    {{ $percentage }}%
                                </span>
                            </div>

                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-yellow-400 transition-all" style="width: {{ $percentage }}%"></div>
                        </div>

                    </div>

                @empty

                    <div class="py-10 text-center text-sm text-slate-500">
                        No maturity data available yet.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- AVERAGE READINESS — signature gauge --}}
        @php
            $gaugeRadius = 70;
            $gaugeCircumference = 2 * pi() * $gaugeRadius;
            $gaugeOffset = $gaugeCircumference - (min($averageCompanyScore, 100) / 100) * $gaugeCircumference;
        @endphp

        <div class="relative overflow-hidden rounded-2xl bg-slate-950 p-6 text-white shadow-sm">

            <div class="relative z-10">

                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Portfolio Readiness
                </p>

                <h2 class="mt-1 text-xl font-bold">
                    Average AI Readiness
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-400">
                    Average organizational readiness across completed assessments.
                </p>

                <div class="mt-8 flex items-center justify-center">

                    <div class="relative h-44 w-44">

                        <svg class="h-full w-full -rotate-90" viewBox="0 0 160 160">
                            <circle cx="80" cy="80" r="{{ $gaugeRadius }}"
                                    fill="none" stroke="#1e293b" stroke-width="12"/>

                            <circle cx="80" cy="80" r="{{ $gaugeRadius }}"
                                    fill="none" stroke="#facc15" stroke-width="12"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ $gaugeCircumference }}"
                                    stroke-dashoffset="{{ $gaugeOffset }}"
                                    class="transition-all duration-700 ease-out"/>
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-bold tracking-tight">
                                {{ number_format($averageCompanyScore, 1) }}
                            </span>
                            <span class="text-xs font-medium text-slate-500">
                                out of 100
                            </span>
                        </div>

                    </div>

                </div>

                <div class="mt-6 flex justify-center gap-2 text-xs text-slate-500">
                    <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                    Portfolio average, all completed assessments
                </div>

            </div>

            <div class="pointer-events-none absolute -bottom-20 -right-20 h-48 w-48 rounded-full bg-yellow-400/10 blur-3xl"></div>

        </div>

    </div>


    {{-- CAPABILITY INTELLIGENCE --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- CAPABILITY PROFILE --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Capability Intelligence
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-slate-950">
                        Capability Profile
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Average readiness across YARA's assessment dimensions.
                    </p>
                </div>

                <div class="hidden text-right sm:block">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Scale
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-700">
                        0 — 100
                    </p>
                </div>

            </div>

            <div class="mt-5 flex h-[340px] items-center justify-center">
                <canvas id="dimensionRadarChart"></canvas>
            </div>

            @php
                $dimensionsWithData = collect($dimensionPerformance)->whereNotNull('score')->count();
                $totalDimensions = collect($dimensionPerformance)->count();
            @endphp

            <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-4">

                <p class="text-xs text-slate-500">
                    {{ $dimensionsWithData }} of {{ $totalDimensions }} dimensions currently have assessment data.
                </p>

                <a href="/admin/dimensions" class="text-xs font-semibold text-amber-600 hover:text-amber-700">
                    View framework →
                </a>

            </div>

        </div>


        {{-- PRIORITY GAPS --}}
        <div class="rounded-2xl bg-slate-950 p-6 text-white shadow-sm">

            @php
                $priorityGaps = collect($dimensionPerformance)
                    ->whereNotNull('score')
                    ->sortBy('score')
                    ->take(3)
                    ->values();
            @endphp

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Attention Required
            </p>

            <h2 class="mt-1 text-xl font-bold">
                Priority Gaps
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-400">
                Lowest-performing capabilities across completed assessments.
            </p>

            <div class="mt-7 space-y-4">

                @forelse($priorityGaps as $index => $dimension)

                    <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-4">

                        <div class="flex items-start gap-3">

                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-yellow-400 font-bold text-slate-950">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <div class="min-w-0 flex-1">

                                <div class="flex items-start justify-between gap-3">

                                    <div>
                                        <p class="text-xs font-semibold text-yellow-400">
                                            {{ $dimension['code'] }}
                                        </p>

                                        <p class="mt-1 text-sm font-semibold leading-5 text-white">
                                            {{ $dimension['name'] }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <span class="text-lg font-bold">
                                            {{ number_format($dimension['score'], 1) }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            /100
                                        </span>
                                    </div>

                                </div>

                                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-800">
                                    <div class="h-full rounded-full bg-yellow-400" style="width: {{ min($dimension['score'], 100) }}%"></div>
                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="rounded-xl border border-slate-800 p-5 text-sm text-slate-400">
                        Not enough assessment data to identify capability gaps.
                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- ACTIVITY & LEADERBOARD --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- RECENT ASSESSMENTS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Live Activity
                    </p>
                    <h2 class="mt-1 text-xl font-bold text-slate-950">
                        Recent Assessments
                    </h2>
                </div>

                <a href="{{ route('admin.assessments.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700">
                    View all →
                </a>
            </div>

            @isset($recentAssessments)
                @forelse($recentAssessments as $recent)

                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 py-3.5 last:border-0 last:pb-0">

                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-600">
                                {{ strtoupper(substr($recent['organization'] ?? '—', 0, 2)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900">
                                    {{ $recent['organization'] ?? 'Unknown organization' }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ $recent['updated_at'] ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-shrink-0 items-center gap-4">
                            @if(($recent['status'] ?? null) === 'completed')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Completed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    In progress
                                </span>
                            @endif

                            <span class="w-12 text-right text-sm font-bold text-slate-900">
                                {{ isset($recent['score']) ? number_format($recent['score'], 1) : '—' }}
                            </span>
                        </div>

                    </div>

                @empty

                    <div class="py-10 text-center text-sm text-slate-500">
                        No assessment activity yet.
                    </div>

                @endforelse
            @else

                <div class="rounded-xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-400">
                    Pass a <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">$recentAssessments</code> collection from the controller to populate this feed.
                </div>

            @endisset

        </div>


        {{-- TOP ORGANIZATIONS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                Leaderboard
            </p>
            <h2 class="mt-1 text-xl font-bold text-slate-950">
                Top Organizations
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Highest readiness scores this period.
            </p>

            @isset($topOrganizations)
                <div class="mt-6 space-y-3">
                    @forelse($topOrganizations as $index => $org)

                        <div class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-3">

                            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg
                                {{ $index === 0 ? 'bg-yellow-400 text-slate-950' : 'bg-white border border-slate-200 text-slate-500' }}
                                text-xs font-bold">
                                {{ $index + 1 }}
                            </div>

                            <p class="min-w-0 flex-1 truncate text-sm font-semibold text-slate-800">
                                {{ $org['name'] ?? 'Unknown' }}
                            </p>

                            <span class="flex-shrink-0 text-sm font-bold text-slate-950">
                                {{ isset($org['score']) ? number_format($org['score'], 1) : '—' }}
                            </span>

                        </div>

                    @empty

                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-6 text-center text-sm text-slate-500">
                            No scored organizations yet.
                        </div>

                    @endforelse
                </div>
            @else

                <div class="mt-6 rounded-xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-400">
                    Pass a <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">$topOrganizations</code> collection from the controller to populate this leaderboard.
                </div>

            @endisset

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('dimensionRadarChart');

    if (!canvas) {
        return;
    }

    const dimensionCodes = @json(collect($dimensionPerformance)->pluck('code')->values());
    const dimensionNames = @json(collect($dimensionPerformance)->pluck('name')->values());
    const scores = @json(collect($dimensionPerformance)->pluck('score')->values());

    new Chart(canvas, {
        type: 'radar',

        data: {
            labels: dimensionCodes,
            datasets: [{
                label: 'Average Readiness',
                data: scores,
                borderColor: '#facc15',
                backgroundColor: 'rgba(250, 204, 21, 0.15)',
                pointBackgroundColor: '#facc15',
                pointBorderColor: '#ffffff',
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            const index = context[0].dataIndex;
                            return dimensionCodes[index] + ' · ' + dimensionNames[index];
                        },
                        label: function(context) {
                            if (context.raw === null) {
                                return 'No assessment data';
                            }
                            return 'Average readiness: ' + Number(context.raw).toFixed(1) + ' / 100';
                        }
                    }
                }
            },

            scales: {
                r: {
                    min: 0,
                    max: 100,
                    beginAtZero: true,
                    ticks: { display: false, stepSize: 20 },
                    grid: { color: '#e2e8f0' },
                    angleLines: { color: '#e2e8f0' },
                    pointLabels: {
                        color: '#475569',
                        font: { size: 12, weight: '600' }
                    }
                }
            }
        }
    });

});
</script>
@endsection