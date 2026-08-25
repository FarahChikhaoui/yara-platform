@extends('layouts.yara')

@section('content')

@php
    $gap = $benchmarkGap !== null ? round($benchmarkGap, 1) : null;
@endphp

<div class="mx-auto max-w-7xl space-y-6">

    {{-- TOOLBAR: back navigation + year selector, grouped --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <a
            href="{{ url('/assessment/results/' . $assessment->id) }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
            </svg>
            Back to assessment results
        </a>

        


        <form method="GET"
              action="{{ url('/assessment/results/' . $assessment->id . '/country') }}"
              class="flex items-center gap-3">

            {{-- Keep selected comparison country when changing year --}}
            @if(request('compare'))
                <input type="hidden" name="compare" value="{{ request('compare') }}">
            @endif

            <label for="year" class="text-sm font-semibold text-slate-500">
                Benchmark year
            </label>

            <select
                id="year"
                name="year"
                onchange="this.form.submit()"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-yellow-400 focus:ring-yellow-400"
            >
                @foreach($availableYears as $year)
                    <option value="{{ $year }}" {{ (string) $selectedYear === (string) $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

        </form>

    </div>


    {{-- HERO --}}
    <section class="overflow-hidden rounded-3xl bg-slate-950 text-white shadow-xl">

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_330px]">

            {{-- Country information --}}
            <div class="p-8 md:p-10">

                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-yellow-400">
                    Country Benchmark
                </p>

                <h1 class="mt-3 text-4xl font-bold tracking-tight md:text-5xl">
                    {{ $country->country }}
                </h1>

                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-400">
                    Explore the national AI readiness environment and understand
                    how your organization's readiness compares with its country context.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">

                    @if($country->year)
                        <span class="rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-300">
                            Benchmark year {{ $country->year }}
                        </span>
                    @endif

                    @if($country->score_type)
                        <span class="rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-300">
                            {{ $country->score_type }}
                        </span>
                    @endif

                </div>

            </div>

            {{-- Country score --}}
            <div class="flex flex-col justify-center border-t border-slate-800 bg-slate-900/60 p-8 lg:border-l lg:border-t-0">

                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    AI Readiness Score
                </p>

                <div class="mt-3 flex items-end gap-2">
                    <span class="text-6xl font-bold tracking-tight text-white">
                        {{ number_format($countryScore, 1) }}
                    </span>
                    <span class="mb-2 text-lg text-slate-500">
                        /100
                    </span>
                </div>

                <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-800">
                    <div class="h-full rounded-full bg-yellow-400" style="width: {{ min($countryScore, 100) }}%"></div>
                </div>

            </div>

        </div>

    </section>


    {{-- COUNTRY KPIs --}}
    <section class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Global rank --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Global Rank
                </p>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 transition group-hover:bg-slate-200">
                    <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.21 13.89L7 23l5-3 5 3-1.21-9.12M15.5 8.5a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0z"/>
                    </svg>
                </div>
            </div>

            <div class="mt-3">
                @if($country->final_rank !== null)
                    <span class="text-4xl font-bold text-slate-950">#{{ $country->final_rank }}</span>
                @elseif($country->official_rank !== null)
                    <span class="text-4xl font-bold text-slate-950">#{{ $country->official_rank }}</span>
                @else
                    <span class="text-xl font-semibold text-slate-400">Not available</span>
                @endif
            </div>

            <p class="mt-2 text-sm text-slate-500">
                Position in the available AI readiness dataset.
            </p>

        </div>


        {{-- Organization score --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Your Organization
                </p>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-yellow-50 transition group-hover:bg-yellow-100">
                    <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m4 0v-5a2 2 0 00-2-2h0a2 2 0 00-2 2v5m-2-12h.01M12 7h.01M16 7h.01M8 11h.01M12 11h.01M16 11h.01"/>
                    </svg>
                </div>
            </div>

            @if($organizationScore !== null)
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-4xl font-bold text-slate-950">{{ number_format($organizationScore, 1) }}</span>
                    <span class="mb-1 text-sm text-slate-400">/100</span>
                </div>
            @else
                <p class="mt-3 text-xl font-semibold text-slate-400">Not available</p>
            @endif

            <p class="mt-2 text-sm text-slate-500">
                Organizational readiness from your YARA assessment.
            </p>

        </div>


        {{-- Gap --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Benchmark Gap
                </p>

                @if($gap !== null)
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $gap >= 0 ? 'bg-emerald-50 group-hover:bg-emerald-100' : 'bg-red-50 group-hover:bg-red-100' }} transition">
                        <svg class="h-4 w-4 {{ $gap >= 0 ? 'text-emerald-600' : 'text-red-500 rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l5 5a1 1 0 01-1.414 1.414L11 6.414V16a1 1 0 11-2 0V6.414L5.707 9.707a1 1 0 01-1.414-1.414l5-5A1 1 0 0110 3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif
            </div>

            @if($gap !== null)
                <p class="mt-3 text-4xl font-bold {{ $gap >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                    {{ $gap >= 0 ? '+' : '' }}{{ number_format($gap, 1) }}
                </p>

                <p class="mt-2 text-sm text-slate-500">
                    Your organization is
                    <span class="font-semibold text-slate-700">
                        {{ abs($gap) }} points {{ $gap >= 0 ? 'above' : 'below' }}
                    </span>
                    the national benchmark.
                </p>
            @else
                <p class="mt-3 text-xl font-semibold text-slate-400">Not available</p>
            @endif

        </div>

    </section>


    {{-- HISTORICAL AI READINESS TREND --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="flex items-start justify-between gap-4">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-amber-600">
                    Historical Intelligence
                </p>

                <h2 class="mt-2 text-xl font-bold text-slate-950">
                    AI Readiness Trend
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Evolution of {{ $country->country }}'s AI readiness score across available years.
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 px-4 py-3 text-right">
                <p class="text-xs font-semibold uppercase text-slate-400">
                    Latest change
                </p>

                @if($scoreMovement !== null)
                    <p class="mt-1 text-lg font-bold {{ $scoreMovement > 0 ? 'text-emerald-600' : ($scoreMovement < 0 ? 'text-red-500' : 'text-slate-600') }}">
                        {{ $scoreMovement > 0 ? '+' : '' }}{{ number_format($scoreMovement, 1) }}
                    </p>
                @else
                    <p class="mt-1 text-sm font-semibold text-slate-400">
                        No previous data
                    </p>
                @endif
            </div>

        </div>

        <div class="mt-6 h-72">
            <canvas id="countryHistoryChart"></canvas>
        </div>

    </div>


    {{-- COMPARISON --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Country Comparison
                </p>

                <h2 class="mt-1 text-2xl font-bold text-slate-950">
                    Compare AI readiness
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Select another country to benchmark against {{ $country->country }}.
                </p>
            </div>

            <form
                method="GET"
                action="{{ route('assessment.country', $assessment) }}"
                class="w-full lg:w-80"
            >
                {{-- Keep currently selected year --}}
                <input type="hidden" name="year" value="{{ $selectedYear }}">

                <label for="comparisonCountry" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Compare with
                </label>

                <select
                    id="comparisonCountry"
                    name="compare"
                    onchange="this.form.submit()"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                >
                    <option value="">Select a country</option>

                    @foreach($countries as $comparisonOption)
                        @if(strtolower(trim($comparisonOption->country)) !== strtolower(trim($country->country)))
                            <option
                                value="{{ $comparisonOption->country }}"
                                {{ request('compare') === $comparisonOption->country ? 'selected' : '' }}
                            >
                                {{ $comparisonOption->country }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </form>

        </div>


        {{-- Comparison cards --}}
        <div class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-[1fr_auto_1fr] md:items-stretch">

            {{-- Current country --}}
            <div class="rounded-2xl border-2 border-yellow-300 bg-yellow-50/50 p-6">

                <span class="inline-flex rounded-full bg-yellow-400 px-3 py-1 text-xs font-bold uppercase tracking-wide text-slate-950">
                    Your Market
                </span>

                <h3 class="mt-4 text-2xl font-bold text-slate-950">
                    {{ $country->country }}
                </h3>

                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Score</p>
                        <p class="mt-1 text-3xl font-bold text-slate-950">
                            {{ $countryScore !== null ? number_format($countryScore, 1) : '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Rank</p>
                        <p class="mt-1 text-3xl font-bold text-slate-950">
                            @if($currentRank !== null) #{{ $currentRank }} @else — @endif
                        </p>
                    </div>
                </div>
            </div>


            {{-- VS --}}
            <div class="flex items-center justify-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-950 text-xs font-bold text-white shadow-sm">
                    VS
                </div>
            </div>


            {{-- Comparison country --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                @if($comparisonCountry)

                    <span class="inline-flex rounded-full bg-slate-200 px-3 py-1 text-xs font-bold uppercase tracking-wide text-slate-600">
                        Comparison Market
                    </span>

                    <h3 class="mt-4 text-2xl font-bold text-slate-950">
                        {{ $comparisonCountry->country }}
                    </h3>

                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Score</p>
                            <p class="mt-1 text-3xl font-bold text-slate-950">
                                {{ $comparisonScore !== null ? number_format($comparisonScore, 1) : '—' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Rank</p>
                            <p class="mt-1 text-3xl font-bold text-slate-950">
                                {{ $comparisonRank !== null ? '#' . $comparisonRank : '—' }}
                            </p>
                        </div>
                    </div>

                    @if($countryComparisonGap !== null)
                        <div class="mt-5 border-t border-slate-200 pt-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Score difference
                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                @if($countryComparisonGap > 0)
                                    {{ $country->country }} is {{ number_format(abs($countryComparisonGap), 1) }} points ahead.
                                @elseif($countryComparisonGap < 0)
                                    {{ $comparisonCountry->country }} is {{ number_format(abs($countryComparisonGap), 1) }} points ahead.
                                @else
                                    Both countries have the same score.
                                @endif
                            </p>
                        </div>
                    @endif

                @elseif(request('compare'))

                    <div class="flex h-full min-h-[160px] items-center justify-center text-center">
                        <div>
                            <p class="font-semibold text-slate-700">No data available</p>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ request('compare') }} has no AI readiness data for {{ $selectedYear }}.
                            </p>
                        </div>
                    </div>

                @else

                    <div class="flex h-full min-h-[160px] items-center justify-center text-center">
                        <div>
                            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm">
                                +
                            </div>
                            <p class="mt-3 text-sm font-semibold text-slate-600">Select another country</p>
                            <p class="mt-1 text-xs text-slate-400">Its score and ranking will appear here.</p>
                        </div>
                    </div>

                @endif

            </div>

        </div>

    </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const history = @json($historyChart);
    const comparisonHistory = @json($comparisonHistoryChart);

    const canvas = document.getElementById('countryHistoryChart');

    if (!canvas || !history.length) {
        return;
    }

    const years = [
        ...new Set([
            ...history.map(item => String(item.year)),
            ...comparisonHistory.map(item => String(item.year))
        ])
    ].sort((a, b) => Number(a) - Number(b));

    function scoreForYear(data, year) {
        const record = data.find(item => String(item.year) === String(year));
        return record ? record.score : null;
    }

    const datasets = [
        {
            label: @json($country->country),
            data: years.map(year => scoreForYear(history, year)),
            borderColor: '#facc15',
            backgroundColor: 'rgba(250, 204, 21, 0.10)',
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#facc15',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            tension: 0.35,
            fill: false,
            spanGaps: false
        }
    ];

    if (comparisonHistory.length) {
        datasets.push({
            label: @json($comparisonCountry?->country),
            data: years.map(year => scoreForYear(comparisonHistory, year)),
            borderColor: '#334155',
            backgroundColor: 'transparent',
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#334155',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            tension: 0.35,
            fill: false,
            spanGaps: false
        });
    }

    new Chart(canvas, {
        type: 'line',
        data: { labels: years, datasets: datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: {
                    display: datasets.length > 1,
                    position: 'top',
                    align: 'end',
                    labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, boxHeight: 8, padding: 20 }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.parsed.y === null) {
                                return context.dataset.label + ': No data';
                            }
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(1);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 20 },
                    grid: { color: '#e2e8f0' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

});
</script>
@endsection