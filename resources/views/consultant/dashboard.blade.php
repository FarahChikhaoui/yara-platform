@extends('layouts.yara')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.05a7.002 7.002 0 015.95 5.95H18a1 1 0 110 2h-1.05A7.002 7.002 0 0111 16.95V18a1 1 0 11-2 0v-1.05A7.002 7.002 0 013.05 11H2a1 1 0 110-2h1.05A7.002 7.002 0 019 3.05V2a1 1 0 011-1z"/></svg>
                Consultant Workspace
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                Assessment Review
            </h1>

            <p class="mt-2 text-slate-500 max-w-xl">
                Review completed organizational AI readiness assessments and provide expert validation.
            </p>
        </div>

        <div class="flex-none">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0a7.5 7.5 0 10-10.6 0 7.5 7.5 0 0010.6 0z" />
                </svg>
                <input
                    type="text"
                    id="assessment-search"
                    placeholder="Search organizations…"
                    class="w-full sm:w-72 rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-16 text-sm text-slate-700 placeholder:text-slate-400 shadow-sm transition focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 focus:outline-none"
                >
                <kbd class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 rounded border border-slate-200 bg-slate-50 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400">/</kbd>
            </div>
        </div>
    </div>


    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">
                    Completed Assessments
                </p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-yellow-100 group-hover:text-yellow-700">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-950">
                {{ $assessments->count() }}
            </p>
        </div>

        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">
                    Organizations
                </p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-yellow-100 group-hover:text-yellow-700">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-950">
                {{ $assessments->pluck('company_id')->unique()->count() }}
            </p>
        </div>

        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">
                    Avg. Readiness Score
                </p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-yellow-100 group-hover:text-yellow-700">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l4.5-4.5 3.75 3.75L18 6m0 0h-4.5M18 6v4.5" />
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-950">
                {{ $assessments->count() ? number_format($assessments->avg('company_score'), 1) : '—' }}
                <span class="text-base font-medium text-slate-400">/100</span>
            </p>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-slate-950 p-6 text-white shadow-sm">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-yellow-400/10"></div>
            <div class="relative flex items-center justify-between">
                <p class="text-sm font-medium text-slate-400">
                    Awaiting Review
                </p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-yellow-400">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="relative mt-4 text-3xl font-bold">
                {{ $awaitingReviewCount }}
            </p>
            <p class="relative mt-1 text-xs text-slate-400">
                @php
                    $overdueCount = $assessments
                        ->where('review_status', '!=', 'reviewed')
                        ->filter(fn ($a) => $a->created_at->diffInDays(now()) > 7)
                        ->count();
                @endphp
                @if($overdueCount)
                    <span class="font-semibold text-red-400">{{ $overdueCount }} overdue</span> · needs your validation
                @else
                    Needs your validation
                @endif
            </p>
        </div>

    </div>


    {{-- ASSESSMENTS --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 p-6">

            <div>
                <h2 class="text-xl font-bold text-slate-950">
                    Client Assessments
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Completed assessments available for consultant review.
                </p>
            </div>

            @if($assessments->count())
                <div class="flex flex-wrap items-center gap-3">

                    <button type="button" id="export-csv"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        Export CSV
                    </button>

                    <select id="sort-select" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 focus:outline-none">
                        <option value="date-desc">Newest first</option>
                        <option value="date-asc">Oldest first</option>
                        <option value="score-desc">Highest score</option>
                        <option value="score-asc">Lowest score</option>
                        <option value="name-asc">Organization A–Z</option>
                    </select>

                </div>
            @endif

        </div>

        @if($assessments->count())

            {{-- STATUS FILTER TABS --}}
            @php
                $reviewedCount = $assessments->where('review_status', 'reviewed')->count();
                $pendingCount = $assessments->count() - $reviewedCount;
            @endphp

            <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-6 py-3" id="status-filters">

                <button type="button" data-filter="all" class="filter-tab active-filter rounded-full px-3.5 py-1.5 text-xs font-semibold transition">
                    All <span class="ml-1 opacity-60">{{ $assessments->count() }}</span>
                </button>

                <button type="button" data-filter="pending" class="filter-tab rounded-full px-3.5 py-1.5 text-xs font-semibold transition">
                    Pending <span class="ml-1 opacity-60">{{ $pendingCount }}</span>
                </button>

                <button type="button" data-filter="reviewed" class="filter-tab rounded-full px-3.5 py-1.5 text-xs font-semibold transition">
                    Reviewed <span class="ml-1 opacity-60">{{ $reviewedCount }}</span>
                </button>

                <span class="ml-auto flex items-center gap-2 text-xs font-medium text-slate-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Reviewed
                    <span class="ml-3 h-2 w-2 rounded-full bg-slate-950"></span>
                    Pending
                </span>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left" id="assessments-table">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Organization
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Assessment
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Score
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Maturity
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Risk
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Date
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($assessments as $assessment)

                            @php

                                $maturity = $maturityLevels->first(
                                    function ($level) use ($assessment) {

                                        return
                                            $assessment->company_score >= $level->min_score &&
                                            $assessment->company_score <= $level->max_score;

                                    }
                                );

                                $orgName = $assessment->company?->name ?? 'Unknown organization';
                                $initials = collect(explode(' ', $orgName))
                                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                    ->take(2)
                                    ->implode('');

                                $score = $assessment->company_score;
                                $scoreColor = $score >= 70
                                    ? 'text-emerald-600'
                                    : ($score >= 40 ? 'text-yellow-600' : 'text-red-500');

                                $isReviewed = $assessment->review_status === 'reviewed';
                                $isOverdue = !$isReviewed && $assessment->created_at->diffInDays(now()) > 7;

                                $riskLevel = $assessment->risk_level ?? null;
                                $riskChipStyles = [
                                    'Low' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    'Medium' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                    'High' => 'bg-red-50 text-red-600 ring-red-600/20',
                                ];
                            @endphp


                            <tr
                                class="row-searchable transition hover:bg-slate-50"
                                data-org="{{ strtolower($orgName) }}"
                                data-status="{{ $isReviewed ? 'reviewed' : 'pending' }}"
                                data-score="{{ $score }}"
                                data-date="{{ $assessment->created_at->timestamp }}"
                            >

                                {{-- ORGANIZATION --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-yellow-400">
                                            {{ $initials ?: '—' }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900">
                                                {{ $orgName }}
                                            </p>
                                            <p class="mt-0.5 text-xs text-slate-400">
                                                {{ $assessment->company?->country ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>


                                {{-- ASSESSMENT --}}
                                <td class="px-6 py-5 text-sm text-slate-700">

                                    {{ $assessment->title ?? 'AI Readiness Assessment' }}

                                </td>


                                {{-- SCORE --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-2.5">
                                        <span class="font-bold {{ $scoreColor }}">
                                            {{ number_format($score, 1) }}
                                        </span>
                                        <span class="text-sm text-slate-300">/100</span>

                                        <div class="hidden sm:block h-1.5 w-16 rounded-full bg-slate-100 overflow-hidden">
                                            <div
                                                class="h-full rounded-full {{ $score >= 70 ? 'bg-emerald-500' : ($score >= 40 ? 'bg-yellow-400' : 'bg-red-400') }}"
                                                style="width: {{ min(100, max(0, $score)) }}%"
                                            ></div>
                                        </div>
                                    </div>

                                </td>


                                {{-- MATURITY --}}
                                <td class="px-6 py-5">

                                    @if($maturity)

                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700">
                                            {{ $maturity->label ?? $maturity->name }}
                                        </span>

                                    @else

                                        <span class="text-sm text-slate-400">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- RISK --}}
                                <td class="px-6 py-5">

                                    @if($riskLevel)

                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $riskChipStyles[$riskLevel] ?? 'bg-slate-50 text-slate-500 ring-slate-200' }}">
                                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                            {{ $riskLevel }}
                                        </span>

                                    @else

                                        <span class="text-sm text-slate-300">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- DATE --}}
                                <td class="px-6 py-5 text-sm text-slate-500">

                                    {{ $assessment->created_at->format('d M Y') }}

                                    @if($isOverdue)
                                        <span class="ml-1.5 inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-500">
                                            Overdue
                                        </span>
                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="px-6 py-5 text-right">

                                    @if($isReviewed)

                                        <a href="{{ route('consultant.assessments.review', $assessment) }}"
                                           class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                            Reviewed
                                        </a>

                                    @else

                                        <a href="{{ route('consultant.assessments.review', $assessment) }}"
                                           class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                                            Review
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                            </svg>
                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <p id="no-results" class="hidden px-6 py-10 text-center text-sm text-slate-400">
                    No organizations match your search or filters.
                </p>

            </div>

        @else

            <div class="p-14 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h3 class="mt-4 font-bold text-slate-900">
                    No assessments to review
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Completed client assessments will appear here.
                </p>

            </div>

        @endif

    </div>

</div>

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
        color: #fff;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('assessment-search');
    const rows = Array.from(document.querySelectorAll('.row-searchable'));
    const noResults = document.getElementById('no-results');
    const tableBody = document.querySelector('#assessments-table tbody');
    const filterTabs = document.querySelectorAll('.filter-tab');

    let activeFilter = 'all';

    function applyFilters() {
        const query = (searchInput?.value || '').trim().toLowerCase();
        let visibleCount = 0;

        rows.forEach(function (row) {
            const matchesSearch = !query || row.dataset.org.includes(query);
            const matchesFilter = activeFilter === 'all' || row.dataset.status === activeFilter;
            const visible = matchesSearch && matchesFilter;
            row.classList.toggle('hidden', !visible);
            if (visible) visibleCount++;
        });

        noResults?.classList.toggle('hidden', visibleCount !== 0);
    }

    searchInput?.addEventListener('input', applyFilters);

    // "/" keyboard shortcut to focus search
    document.addEventListener('keydown', function (e) {
        if (e.key === '/' && document.activeElement !== searchInput) {
            e.preventDefault();
            searchInput?.focus();
        }
    });

    // Status filter tabs
    filterTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            filterTabs.forEach(t => t.classList.remove('active-filter'));
            this.classList.add('active-filter');
            activeFilter = this.dataset.filter;
            applyFilters();
        });
    });

    // Sorting
    const sortSelect = document.getElementById('sort-select');
    sortSelect?.addEventListener('change', function () {
        const [key, direction] = this.value.split('-');
        const sorted = rows.slice().sort(function (a, b) {
            let valA, valB;

            if (key === 'score') {
                valA = parseFloat(a.dataset.score);
                valB = parseFloat(b.dataset.score);
            } else if (key === 'date') {
                valA = parseInt(a.dataset.date, 10);
                valB = parseInt(b.dataset.date, 10);
            } else {
                valA = a.dataset.org;
                valB = b.dataset.org;
                return direction === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
            }

            return direction === 'asc' ? valA - valB : valB - valA;
        });

        sorted.forEach(function (row) { tableBody.appendChild(row); });
    });

    // CSV export (visible rows only)
    document.getElementById('export-csv')?.addEventListener('click', function () {
        const visibleRows = rows.filter(row => !row.classList.contains('hidden'));
        const header = ['Organization', 'Assessment', 'Score', 'Maturity', 'Risk', 'Date', 'Status'];

        const lines = visibleRows.map(function (row) {
            const cells = row.querySelectorAll('td');
            const org = cells[0]?.querySelector('p.font-semibold')?.textContent.trim() ?? '';
            const assessmentTitle = cells[1]?.textContent.trim() ?? '';
            const score = row.dataset.score ?? '';
            const maturity = cells[3]?.textContent.trim() ?? '';
            const risk = cells[4]?.textContent.trim() ?? '';
            const date = cells[5]?.childNodes[0]?.textContent.trim() ?? '';
            const status = row.dataset.status ?? '';

            return [org, assessmentTitle, score, maturity, risk, date, status]
                .map(v => '"' + String(v).replace(/"/g, '""') + '"')
                .join(',');
        });

        const csv = [header.join(','), ...lines].join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'consultant-assessments.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    });

});
</script>

@endsection