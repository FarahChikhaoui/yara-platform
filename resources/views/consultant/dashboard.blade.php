@extends('layouts.yara')

@section('content')

<div class="space-y-8">

    {{-- ============================================================
         HEADER
    ============================================================ --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1.05a7.002 7.002 0 015.95 5.95H18a1 1 0 110 2h-1.05A7.002 7.002 0 0111 16.95V18a1 1 0 11-2 0v-1.05A7.002 7.002 0 013.05 11H2a1 1 0 110-2h1.05A7.002 7.002 0 019 3.05V2a1 1 0 011-1z"/>
                </svg>

                Consultant Workspace
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                Transformation Requests
            </h1>

            <p class="mt-2 max-w-2xl text-slate-500">
                Review paid transformation engagements, analyze client priorities
                and build expert AI transformation roadmaps.
            </p>
        </div>


        {{-- SEARCH --}}
        <div class="flex-none">
            <div class="relative">

                <svg
                    class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0a7.5 7.5 0 10-10.6 0 7.5 7.5 0 0010.6 0z"
                    />
                </svg>

                <input
                    type="text"
                    id="assessment-search"
                    placeholder="Search organizations…"
                    class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-16 text-sm text-slate-700 placeholder:text-slate-400 shadow-sm transition focus:border-yellow-400 focus:outline-none focus:ring-1 focus:ring-yellow-400 sm:w-72"
                >

                <kbd class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 rounded border border-slate-200 bg-slate-50 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400">
                    /
                </kbd>

            </div>
        </div>

    </div>


    {{-- ============================================================
         SUMMARY CARDS
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- TOTAL --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-slate-500">
                    Total Requests
                </p>

                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>

            </div>

            <p class="mt-4 text-3xl font-bold text-slate-950">
                {{ $assessments->count() }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Paid transformation engagements
            </p>

        </div>


        {{-- NEW --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-slate-500">
                    New Requests
                </p>

                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6v6l4 2"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </span>

            </div>

            <p class="mt-4 text-3xl font-bold text-slate-950">
                {{ $submittedCount }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Waiting to be started
            </p>

        </div>


        {{-- IN REVIEW --}}
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-slate-500">
                    In Review
                </p>

                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.25 4.5l7.5 7.5-7.5 7.5M18.75 12H3"/>
                    </svg>
                </span>

            </div>

            <p class="mt-4 text-3xl font-bold text-slate-950">
                {{ $inReviewCount }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Currently being developed
            </p>

        </div>


        {{-- READY --}}
        <div class="relative overflow-hidden rounded-2xl bg-slate-950 p-6 text-white shadow-sm">

            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-yellow-400/10"></div>

            <div class="relative flex items-center justify-between">

                <p class="text-sm font-medium text-slate-400">
                    Roadmaps Ready
                </p>

                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-yellow-400">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                </span>

            </div>

            <p class="relative mt-4 text-3xl font-bold">
                {{ $roadmapReadyCount }}
            </p>

            <p class="relative mt-1 text-xs text-slate-400">
                Completed transformation roadmaps
            </p>

        </div>

    </div>


    {{-- ============================================================
         TRANSFORMATION REQUESTS
    ============================================================ --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- TABLE HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 p-6">

            <div>

                <h2 class="text-xl font-bold text-slate-950">
                    Client Transformation Requests
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Paid engagements submitted for expert transformation planning.
                </p>

            </div>


            @if($assessments->count())

                <select
                    id="sort-select"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 shadow-sm focus:border-yellow-400 focus:outline-none focus:ring-1 focus:ring-yellow-400"
                >
                    <option value="date-desc">Newest first</option>
                    <option value="date-asc">Oldest first</option>
                    <option value="score-desc">Highest readiness</option>
                    <option value="score-asc">Lowest readiness</option>
                    <option value="name-asc">Organization A–Z</option>
                </select>

            @endif

        </div>


        @if($assessments->count())

            {{-- ====================================================
                 STATUS FILTERS
            ==================================================== --}}
            <div
                id="status-filters"
                class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-6 py-3"
            >

                <button
                    type="button"
                    data-filter="all"
                    class="filter-tab active-filter rounded-full px-3.5 py-1.5 text-xs font-semibold transition"
                >
                    All
                    <span class="ml-1 opacity-60">
                        {{ $assessments->count() }}
                    </span>
                </button>


                <button
                    type="button"
                    data-filter="submitted"
                    class="filter-tab rounded-full px-3.5 py-1.5 text-xs font-semibold transition"
                >
                    New Requests
                    <span class="ml-1 opacity-60">
                        {{ $submittedCount }}
                    </span>
                </button>


                <button
                    type="button"
                    data-filter="in_review"
                    class="filter-tab rounded-full px-3.5 py-1.5 text-xs font-semibold transition"
                >
                    In Review
                    <span class="ml-1 opacity-60">
                        {{ $inReviewCount }}
                    </span>
                </button>


                <button
                    type="button"
                    data-filter="roadmap_ready"
                    class="filter-tab rounded-full px-3.5 py-1.5 text-xs font-semibold transition"
                >
                    Ready
                    <span class="ml-1 opacity-60">
                        {{ $roadmapReadyCount }}
                    </span>
                </button>

            </div>


            {{-- ====================================================
                 TABLE
            ==================================================== --}}
            <div class="overflow-x-auto">

                <table class="w-full text-left" id="assessments-table">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Organization
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Readiness
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Target
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Timeline
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Investment
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($assessments as $assessment)

                            @php

                                $orgName =
                                    $assessment->company?->name
                                    ?? 'Unknown organization';

                                $initials = collect(explode(' ', $orgName))
                                    ->filter()
                                    ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                                    ->take(2)
                                    ->implode('');

                                $score = $assessment->company_score ?? 0;

                                $scoreColor =
                                    $score >= 70
                                        ? 'text-emerald-600'
                                        : ($score >= 40
                                            ? 'text-yellow-600'
                                            : 'text-red-500');

                                $preference = $assessment->roadmapPreference;

                                $status = $assessment->transformation_status;

                                $statusStyles = [
                                    'submitted' =>
                                        'bg-yellow-50 text-yellow-700 ring-yellow-600/20',

                                    'in_review' =>
                                        'bg-blue-50 text-blue-700 ring-blue-600/20',

                                    'roadmap_ready' =>
                                        'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                ];

                                $statusLabels = [
                                    'submitted' => 'New Request',
                                    'in_review' => 'In Review',
                                    'roadmap_ready' => 'Roadmap Ready',
                                ];

                            @endphp


                            <tr
                                class="row-searchable transition hover:bg-slate-50"
                                data-org="{{ strtolower($orgName) }}"
                                data-status="{{ $status }}"
                                data-score="{{ $score }}"
                                data-date="{{ optional($assessment->paid_at ?? $assessment->created_at)->timestamp }}"
                            >

                                {{-- ORGANIZATION --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-slate-950 text-xs font-bold text-yellow-400">
                                            {{ $initials ?: '—' }}
                                        </div>

                                        <div>

                                            <p class="font-semibold text-slate-900">
                                                {{ $orgName }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-slate-400">
                                                Request #{{ $assessment->id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- READINESS --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-2">

                                        <span class="font-bold {{ $scoreColor }}">
                                            {{ number_format($score, 1) }}
                                        </span>

                                        <span class="text-xs text-slate-400">
                                            /100
                                        </span>

                                    </div>

                                </td>


                                {{-- TARGET --}}
                                <td class="px-6 py-5">

                                    @if($preference?->target_maturity)

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $preference->target_maturity }}
                                        </span>

                                    @else

                                        <span class="text-sm text-slate-300">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- TIMELINE --}}
                                <td class="px-6 py-5 text-sm font-medium text-slate-700">

                                    {{ $preference?->timeframe ?? '—' }}

                                </td>


                                {{-- INVESTMENT --}}
                                <td class="px-6 py-5 text-sm text-slate-600">

                                    {{ $preference?->budget_level ?? '—' }}

                                </td>


                                {{-- STATUS --}}
                                <td class="px-6 py-5">

                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $statusStyles[$status] ?? 'bg-slate-50 text-slate-600 ring-slate-200' }}">

                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>

                                        {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}

                                    </span>

                                </td>


                                {{-- ACTION --}}
                                <td class="px-6 py-5 text-right">

                                    @if($status === 'submitted')

                                        <a
                                            href="{{ route('consultant.assessments.review', $assessment) }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                                        >
                                            View Request

                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                            </svg>
                                        </a>


                                    @elseif($status === 'in_review')

                                        <a
                                            href="{{ route('consultant.assessments.review', $assessment) }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                                        >
                                            Continue Review

                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                            </svg>
                                        </a>


                                    @elseif($status === 'roadmap_ready')

                                        <a
                                            href="{{ route('consultant.assessments.review', $assessment) }}"
                                            class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>

                                            View Roadmap
                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>


                <p
                    id="no-results"
                    class="hidden px-6 py-10 text-center text-sm text-slate-400"
                >
                    No transformation requests match your search or filters.
                </p>

            </div>


        @else

            {{-- ====================================================
                 EMPTY STATE
            ==================================================== --}}
            <div class="p-14 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                </div>

                <h3 class="mt-4 font-bold text-slate-900">
                    No transformation requests
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Paid client transformation engagements will appear here once submitted.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- ================================================================
     STYLES
================================================================ --}}
<style>

    .filter-tab {
        background-color: #f8fafc;
        color: #64748b;
    }

    .filter-tab:hover {
        background-color: #f1f5f9;
    }

    .filter-tab.active-filter {
        background-color: #0f172a;
        color: #ffffff;
    }

</style>


{{-- ================================================================
     SEARCH / FILTER / SORT
================================================================ --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('assessment-search');

    const rows =
        Array.from(document.querySelectorAll('.row-searchable'));

    const noResults =
        document.getElementById('no-results');

    const tableBody =
        document.querySelector('#assessments-table tbody');

    const filterTabs =
        document.querySelectorAll('.filter-tab');

    let activeFilter = 'all';


    function applyFilters() {

        const query =
            (searchInput?.value || '')
                .trim()
                .toLowerCase();

        let visibleCount = 0;

        rows.forEach(function (row) {

            const matchesSearch =
                !query ||
                row.dataset.org.includes(query);

            const matchesFilter =
                activeFilter === 'all' ||
                row.dataset.status === activeFilter;

            const visible =
                matchesSearch && matchesFilter;

            row.classList.toggle(
                'hidden',
                !visible
            );

            if (visible) {
                visibleCount++;
            }

        });

        noResults?.classList.toggle(
            'hidden',
            visibleCount !== 0
        );

    }


    searchInput?.addEventListener(
        'input',
        applyFilters
    );


    // "/" focuses search
    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === '/' &&
                document.activeElement !== searchInput
            ) {
                event.preventDefault();
                searchInput?.focus();
            }

        }
    );


    // Status filters
    filterTabs.forEach(function (tab) {

        tab.addEventListener(
            'click',
            function () {

                filterTabs.forEach(
                    item =>
                        item.classList.remove(
                            'active-filter'
                        )
                );

                this.classList.add(
                    'active-filter'
                );

                activeFilter =
                    this.dataset.filter;

                applyFilters();

            }
        );

    });


    // Sorting
    const sortSelect =
        document.getElementById('sort-select');

    sortSelect?.addEventListener(
        'change',
        function () {

            const [key, direction] =
                this.value.split('-');

            const sorted =
                rows.slice().sort(
                    function (a, b) {

                        let valA;
                        let valB;

                        if (key === 'score') {

                            valA =
                                parseFloat(a.dataset.score);

                            valB =
                                parseFloat(b.dataset.score);

                        }

                        else if (key === 'date') {

                            valA =
                                parseInt(
                                    a.dataset.date,
                                    10
                                );

                            valB =
                                parseInt(
                                    b.dataset.date,
                                    10
                                );

                        }

                        else {

                            valA =
                                a.dataset.org;

                            valB =
                                b.dataset.org;

                            return direction === 'asc'
                                ? valA.localeCompare(valB)
                                : valB.localeCompare(valA);

                        }

                        return direction === 'asc'
                            ? valA - valB
                            : valB - valA;

                    }
                );

            sorted.forEach(
                row =>
                    tableBody.appendChild(row)
            );

            applyFilters();

        }
    );

});

</script>

@endsection