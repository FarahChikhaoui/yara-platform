@extends('layouts.yara')

@section('content')

<div class="max-w-6xl mx-auto space-y-10 pb-16">

    {{-- BACK + QUICK ACTIONS --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a
            href="{{ route('consultant.dashboard') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div class="flex items-center gap-2">
            <a href="#review"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                </svg>
                Jump to review
            </a>
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 print:hidden">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.32 0a41.955 41.955 0 00-11.32 0M6 10.5V4.875C6 4.392 6.392 4 6.875 4h10.25c.483 0 .875.392.875.875V10.5" />
                </svg>
                Print report
            </button>
        </div>
    </div>


    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                Consultant Review
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                {{ $assessment->company?->name ?? 'Organization' }}
            </h1>

            <p class="mt-2 text-slate-500 max-w-xl">
                Review the organization's completed AI readiness assessment and results.
            </p>
        </div>

        <div class="flex flex-none flex-col items-end gap-2">
            @if($assessment->review_status === 'reviewed')
                <span class="inline-flex flex-none items-center gap-1.5 rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Reviewed
                </span>
            @else
                <span class="inline-flex flex-none items-center gap-1.5 rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Awaiting Review
                </span>
            @endif

            @if(($assessment->risk_level ?? null) && $assessment->review_status === 'reviewed')
                @php
                    $riskBadgeStyles = [
                        'Low' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Medium' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'High' => 'bg-red-50 text-red-600 border-red-200',
                    ];
                @endphp
                <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold {{ $riskBadgeStyles[$assessment->risk_level] ?? 'bg-slate-50 text-slate-600 border-slate-200' }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                    {{ $assessment->risk_level }} Risk
                </span>
            @endif
        </div>
    </div>


    {{-- ORGANIZATION INFORMATION --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                Assessment
            </p>

            <h2 class="mt-2 text-2xl font-bold text-slate-950">
                {{ $assessment->title ?? 'AI Readiness Assessment' }}
            </h2>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">

                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">Organization</p>
                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $assessment->company?->name ?? '—' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m-2 0h12a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">Industry</p>
                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $assessment->company?->industry ?? '—' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">Country</p>
                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $assessment->company?->country ?? '—' }}
                        </p>
                    </div>
                </div>

            </div>

        </div>


        {{-- STATUS --}}
        <div class="relative overflow-hidden rounded-2xl bg-slate-950 p-8 text-white shadow-sm">
            <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-yellow-400/10"></div>

            <p class="relative text-sm text-slate-400">
                Assessment Status
            </p>

            <div class="relative mt-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 px-3 py-1 text-sm font-semibold text-emerald-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Completed
                </span>
            </div>

            <p class="relative mt-6 text-sm text-slate-400">
                Submitted
            </p>

            <p class="relative mt-1 font-semibold">
                {{ $assessment->created_at->format('d M Y') }}
            </p>

            @if($assessment->reviewed_at)
                <p class="relative mt-4 text-sm text-slate-400">
                    Reviewed
                </p>
                <p class="relative mt-1 font-semibold">
                    {{ $assessment->reviewed_at->format('d M Y') }}
                </p>
            @endif
        </div>

    </div>


    {{-- SCORES --}}
    <div>

        <div class="mb-5">
            <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                Assessment Results
            </p>

            <h2 class="mt-1 text-2xl font-bold text-slate-950">
                AI Readiness Scores
            </h2>
        </div>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- COMPANY --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm font-medium text-slate-500">
                    Organizational Readiness
                </p>

                <div class="mt-3">
                    <span class="text-3xl font-bold text-slate-950">
                        {{ number_format((float) $assessment->company_score, 1) }}
                    </span>

                    <span class="text-slate-400">
                        /100
                    </span>
                </div>

                <div class="mt-3 h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full bg-slate-900" style="width: {{ min(100, max(0, (float) $assessment->company_score)) }}%"></div>
                </div>

                <p class="mt-3 text-xs text-slate-400">
                    Internal organizational AI readiness.
                </p>

            </div>


            {{-- COUNTRY --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm font-medium text-slate-500">
                    Country AI Readiness
                </p>

                @if($assessment->country_ai_score !== null)

                    <div class="mt-3">
                        <span class="text-3xl font-bold text-slate-950">
                            {{ number_format((float) $assessment->country_ai_score, 1) }}
                        </span>

                        <span class="text-slate-400">
                            /100
                        </span>
                    </div>

                    <div class="mt-3 h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full bg-slate-400" style="width: {{ min(100, max(0, (float) $assessment->country_ai_score)) }}%"></div>
                    </div>

                    @if($assessment->country_ai_year)
                        <p class="mt-3 text-xs text-slate-400">
                            Benchmark year {{ $assessment->country_ai_year }}
                        </p>
                    @endif

                @else

                    <p class="mt-3 text-xl font-semibold text-slate-400">
                        Not available
                    </p>

                @endif

            </div>


            {{-- COMPOSITE --}}
            <div class="relative overflow-hidden rounded-2xl border border-yellow-200 bg-yellow-50 p-6 shadow-sm">

                <p class="text-sm font-medium text-yellow-700">
                    YARA Composite Score
                </p>

                <div class="mt-3">
                    <span class="text-3xl font-bold text-slate-950">
                        {{ number_format((float) $assessment->combined_score, 1) }}
                    </span>

                    <span class="text-slate-500">
                        /100
                    </span>
                </div>

                <div class="mt-3 h-1.5 w-full rounded-full bg-yellow-200/70 overflow-hidden">
                    <div class="h-full rounded-full bg-yellow-500" style="width: {{ min(100, max(0, (float) $assessment->combined_score)) }}%"></div>
                </div>

                <p class="mt-3 text-xs text-yellow-700/80">
                    80% organization + 20% country context.
                </p>

            </div>

        </div>

    </div>

    {{-- Dimension Breakdown --}}
    <section>

        <div class="mb-6">
            <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                Readiness Analysis
            </p>

            <h2 class="mt-2 text-2xl font-bold text-slate-900">
                Dimension Breakdown
            </h2>

            <p class="mt-2 text-slate-500">
                Review the organization's AI readiness performance across each assessment dimension.
            </p>
        </div>

        @php
            $flaggedDimensions = collect($dimensionScores ?? [])->filter(fn ($score) => $score < 40);
        @endphp

        @if($flaggedDimensions->isNotEmpty())
            <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
                <svg class="mt-0.5 w-5 h-5 flex-none text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-700">
                        {{ $flaggedDimensions->count() }} {{ Str::plural('dimension', $flaggedDimensions->count()) }} may need urgent attention
                    </p>
                    <p class="mt-1 text-sm text-red-600/90">
                        {{ $flaggedDimensions->keys()->implode(', ') }} {{ $flaggedDimensions->count() > 1 ? 'scored' : 'scored' }} below 40/100.
                    </p>
                </div>
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

            <div class="grid grid-cols-12 border-b border-slate-200 bg-slate-50 px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <div class="col-span-5">
                    Dimension
                </div>

                <div class="col-span-4">
                    Score
                </div>

                <div class="col-span-3">
                    Maturity
                </div>
            </div>

            @forelse($dimensionScores as $dimensionName => $score)

                @php
                    $dimensionMaturity = $dimensionMaturityLevels[$dimensionName] ?? null;
                    $barColor = $score >= 70 ? 'bg-emerald-500' : ($score >= 40 ? 'bg-yellow-400' : 'bg-red-400');
                @endphp

                <div class="grid grid-cols-12 items-center border-b border-slate-100 px-6 py-5 last:border-b-0 transition hover:bg-slate-50/60">

                    {{-- Dimension --}}
                    <div class="col-span-5">
                        <p class="font-semibold text-slate-900">
                            {{ $dimensionName }}
                        </p>
                    </div>

                    {{-- Score --}}
                    <div class="col-span-4 pr-6">
                        <div class="flex items-center gap-3">
                            <span class="text-lg font-bold text-slate-900">
                                {{ number_format($score, 1) }}
                            </span>
                            <span class="text-sm text-slate-400">/100</span>
                        </div>
                        <div class="mt-2 h-1.5 w-full max-w-[160px] rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ min(100, max(0, $score)) }}%"></div>
                        </div>
                    </div>

                    {{-- Maturity --}}
                    <div class="col-span-3">

                        @if($dimensionMaturity)

                            <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-semibold text-yellow-700">
                                {{ $dimensionMaturity->label ?? $dimensionMaturity->name }}
                            </span>

                        @else

                            <span class="text-sm text-slate-400">
                                —
                            </span>

                        @endif

                    </div>

                </div>

            @empty

                <div class="px-6 py-8 text-center text-slate-500">
                    No dimension data available.
                </div>

            @endforelse

        </div>

    </section>

    {{-- Assessment Responses --}}
    <section>

        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                    Response Review
                </p>

                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                    Assessment Responses
                </h2>

                <p class="mt-2 text-slate-500">
                    Review the answers submitted by the organization for each assessment question.
                </p>
            </div>

            <div class="flex flex-none items-center gap-3">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input
                        type="text"
                        id="response-search"
                        placeholder="Search questions…"
                        class="w-48 rounded-lg border border-slate-200 py-1.5 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 focus:outline-none sm:w-64"
                    >
                </div>

                <button
                    type="button"
                    id="toggle-responses"
                    class="flex-none text-sm font-semibold text-slate-500 transition hover:text-slate-900"
                >
                    Collapse all
                </button>
            </div>
        </div>

        @php
            $responsesByDimension = $responses
                ->filter(function ($response) {
                    return $response->question
                        && $response->question->dimension;
                })
                ->groupBy(function ($response) {
                    return $response->question->dimension->name;
                });
        @endphp

        <div class="space-y-4" id="responses-accordion">

            @forelse($responsesByDimension as $dimensionName => $dimensionResponses)

                <details class="response-group group overflow-hidden rounded-2xl border border-slate-200 bg-white" open>

                    {{-- Dimension header --}}
                    <summary class="flex cursor-pointer list-none items-center justify-between border-b border-slate-200 bg-slate-50 px-6 py-4 transition group-open:border-b group-[:not([open])]:border-b-0">
                        <h3 class="font-bold text-slate-900">
                            {{ $dimensionName }}
                        </h3>

                        <div class="flex items-center gap-3">
                            <span class="text-xs font-medium text-slate-400">
                                {{ $dimensionResponses->count() }} {{ Str::plural('question', $dimensionResponses->count()) }}
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </summary>

                    {{-- Questions --}}
                    <div class="divide-y divide-slate-100">

                        @foreach($dimensionResponses as $response)

                            <div class="response-row px-6 py-5" data-search-text="{{ Str::lower($response->question->question_text) }}">

                                <p class="font-semibold text-slate-900">
                                    {{ $response->question->question_text }}
                                </p>

                                <div class="mt-3 flex items-start gap-2 rounded-xl bg-slate-50 px-4 py-3">
                                    <svg class="mt-0.5 w-4 h-4 flex-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                    </svg>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Organization's Answer
                                        </p>

                                        <p class="mt-1 font-medium text-slate-700">
                                            {{ $response->answerOption?->label ?? 'No answer' }}
                                        </p>
                                    </div>
                                </div>

                            </div>

                        @endforeach

                    </div>

                </details>

            @empty

                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500">
                    No assessment responses available.
                </div>

            @endforelse

            <p id="no-response-results" class="hidden rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-400">
                No questions match your search.
            </p>

        </div>

    </section>

    {{-- Consultant Validation --}}
    <section id="review" class="scroll-mt-24">

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

            <div class="flex flex-wrap items-start justify-between gap-6">

                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                        Expert Validation
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-900">
                        Consultant Review
                    </h2>

                    <p class="mt-2 text-slate-500 max-w-lg">
                        Provide expert observations and validate the organization's assessment.
                    </p>
                </div>

                @if($assessment->review_status === 'reviewed')

                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Reviewed
                    </span>

                @else

                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-700">
                        Awaiting Review
                    </span>

                @endif
            </div>


            {{-- Success message --}}
            @if(session('success'))

                <div class="mt-6 flex items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                    <svg class="mt-0.5 w-4 h-4 flex-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    {{ session('success') }}
                </div>

            @endif


            @if($assessment->review_status === 'reviewed' && !request()->boolean('edit'))

                {{-- Already reviewed --}}
                <div class="mt-8">

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">
                            Consultant Observations
                        </p>

                        <button type="button" id="copy-notes"
                            data-notes="{{ $assessment->consultant_notes }}"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-500 transition hover:bg-slate-50">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                            </svg>
                            <span id="copy-notes-label">Copy</span>
                        </button>
                    </div>

                    <div class="mt-3 whitespace-pre-line rounded-xl bg-slate-50 p-5 leading-relaxed text-slate-700">
                        {{ $assessment->consultant_notes }}
                    </div>

                    @if($assessment->reviewed_at)

                        <p class="mt-4 text-sm text-slate-400">
                            Reviewed {{ $assessment->reviewed_at->format('d M Y \a\t H:i') }}
                        </p>

                    @endif

                    <div class="mt-6">
                        <a href="{{ route('consultant.assessments.review', $assessment) }}?edit=1"
                           class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                            </svg>
                            Edit Review
                        </a>
                    </div>
                </div>

            @else

                {{-- Review form (advanced) --}}
                @php
                    $selectedRisk = old('risk_level', $assessment->risk_level ?? '');
                    $riskOptions = [
                        [
                            'value' => 'Low',
                            'label' => 'Low Risk',
                            'hint' => 'Strong foundation',
                            'color' => 'emerald',
                        ],
                        [
                            'value' => 'Medium',
                            'label' => 'Medium Risk',
                            'hint' => 'Some gaps to address',
                            'color' => 'yellow',
                        ],
                        [
                            'value' => 'High',
                            'label' => 'High Risk',
                            'hint' => 'Significant concerns',
                            'color' => 'red',
                        ],
                    ];
                @endphp

                <form
                    method="POST"
                    action="{{ route('consultant.assessments.submit-review', $assessment) }}"
                    class="mt-8"
                    id="review-form"
                    novalidate
                >
                    @csrf

                    {{-- Risk level selector --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">
                            Overall Risk Level
                        </label>
                        <p class="mt-1 text-sm text-slate-500">
                            How would you rate this organization's overall AI readiness risk?
                        </p>

                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3" id="risk-level-group">

                            @foreach($riskOptions as $option)
                                <button
                                    type="button"
                                    data-risk="{{ $option['value'] }}"
                                    class="risk-option rounded-xl border-2 px-4 py-3 text-center transition hover:border-{{ $option['color'] }}-300 {{ $selectedRisk === $option['value'] ? 'border-slate-900 bg-slate-50' : 'border-slate-200' }}"
                                >
                                    <span class="block text-sm font-bold text-{{ $option['color'] === 'emerald' ? 'emerald-600' : ($option['color'] === 'yellow' ? 'yellow-600' : 'red-500') }}">
                                        {{ $option['label'] }}
                                    </span>
                                    <span class="mt-0.5 block text-xs text-slate-400">{{ $option['hint'] }}</span>
                                </button>
                            @endforeach

                        </div>

                        <p id="risk-error" class="mt-2 hidden text-sm font-medium text-red-600">
                            Select a risk level before submitting your review.
                        </p>
                    </div>

                    {{-- Guided template --}}
                    <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <label for="consultant_notes" class="block text-sm font-semibold text-slate-900">
                                Consultant Observations
                            </label>
                            <p class="mt-1 text-sm text-slate-500">
                                Add your expert assessment, observations, risks or areas requiring attention.
                            </p>
                        </div>

                        <div class="flex flex-none items-center gap-2">
                            <button type="button" id="insert-template" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5h6m-6 3h3m-3-9h6m3.75-3H5.25A2.25 2.25 0 003 6.75v10.5A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25V6.75A2.25 2.25 0 0018.75 4.5z" />
                                </svg>
                                Use template
                            </button>

                            <button type="button" id="toggle-fullscreen" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M20.25 3.75v4.5m0-4.5h-4.5m4.5 0L15 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15m11.25 5.25v-4.5m0 4.5h-4.5m4.5 0L15 15" />
                                </svg>
                                Expand
                            </button>
                        </div>
                    </div>

                    {{-- Quick-insert observation chips --}}
                    <div class="mt-4 space-y-2.5">

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="mr-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Positive signals</span>
                            @foreach([
                                'Leadership alignment is strong',
                                'Clear use-case pipeline exists',
                                'Data infrastructure is solid',
                            ] as $chip)
                                <button type="button" class="chip-insert rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 transition hover:border-emerald-400 hover:bg-emerald-100">
                                    + {{ $chip }}
                                </button>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="mr-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Risk indicators</span>
                            @foreach([
                                'No formal AI governance in place',
                                'Data infrastructure needs investment',
                                'Skills gap identified across teams',
                                'Change management is a concern',
                            ] as $chip)
                                <button type="button" class="chip-insert rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:border-red-400 hover:bg-red-100">
                                    + {{ $chip }}
                                </button>
                            @endforeach
                        </div>

                    </div>

                    <div class="relative mt-4" id="textarea-wrapper">
                        <textarea
                            id="consultant_notes"
                            name="consultant_notes"
                            rows="9"
                            required
                            maxlength="5000"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm transition focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 focus:outline-none"
                            placeholder="Enter your review observations..."
                        >{{ old('consultant_notes', $assessment->consultant_notes) }}</textarea>

                        {{-- Draft restore banner --}}
                        <div id="draft-banner" class="hidden mt-2 flex items-center justify-between rounded-lg bg-slate-50 px-4 py-2.5 text-xs text-slate-500">
                            <span>A locally saved draft is available.</span>
                            <div class="flex items-center gap-3">
                                <button type="button" id="restore-draft" class="font-semibold text-yellow-700 hover:underline">Restore</button>
                                <button type="button" id="dismiss-draft" class="font-semibold text-slate-400 hover:underline">Dismiss</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                        <span id="draft-status" class="text-xs text-slate-400"></span>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-300">⌘/Ctrl + Enter to submit</span>
                            <span id="char-count" class="text-xs text-slate-400">0 / 5000</span>
                        </div>
                    </div>

                    @error('consultant_notes')

                        <p class="mt-2 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                    {{-- Hidden field carries the chosen risk level --}}
                    <input type="hidden" name="risk_level" id="risk_level_input" value="{{ $selectedRisk }}">

                    <div class="mt-6 flex flex-wrap items-center justify-end gap-3">

                        <span id="submit-hint" class="mr-auto text-xs text-slate-400"></span>

                        @if($assessment->review_status === 'reviewed')
                            <a href="{{ route('consultant.assessments.review', $assessment) }}"
                               class="inline-flex items-center rounded-xl border border-slate-200 px-6 py-3 font-semibold text-slate-600 transition hover:bg-slate-50">
                                Cancel
                            </a>
                        @endif

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.98]"
                        >
                            Mark as Reviewed
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </button>

                    </div>

                </form>

            @endif

        </div>

    </section>

</div>

<style>
    @media print {
        .print\:hidden { display: none !important; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ----------------------------------------------------------------
     * Response search / filter
     * ---------------------------------------------------------------- */
    const searchInput = document.getElementById('response-search');
    const responseRows = document.querySelectorAll('.response-row');
    const responseGroups = document.querySelectorAll('.response-group');
    const noResults = document.getElementById('no-response-results');

    searchInput?.addEventListener('input', function () {
        const term = this.value.trim().toLowerCase();
        let anyVisible = false;

        responseGroups.forEach(function (group) {
            let groupHasMatch = false;

            group.querySelectorAll('.response-row').forEach(function (row) {
                const match = !term || row.dataset.searchText.includes(term);
                row.classList.toggle('hidden', !match);
                if (match) groupHasMatch = true;
            });

            group.classList.toggle('hidden', !groupHasMatch);
            if (groupHasMatch) {
                anyVisible = true;
                if (term) group.open = true;
            }
        });

        noResults?.classList.toggle('hidden', anyVisible || !term);
    });

    /* ----------------------------------------------------------------
     * Expand / collapse all response groups
     * ---------------------------------------------------------------- */
    const toggleBtn = document.getElementById('toggle-responses');

    if (toggleBtn && responseGroups.length) {
        let expanded = true;

        toggleBtn.addEventListener('click', function () {
            expanded = !expanded;
            responseGroups.forEach(function (group) {
                group.open = expanded;
            });
            toggleBtn.textContent = expanded ? 'Collapse all' : 'Expand all';
        });
    }

    /* ----------------------------------------------------------------
     * Copy consultant notes (reviewed state)
     * ---------------------------------------------------------------- */
    const copyBtn = document.getElementById('copy-notes');
    copyBtn?.addEventListener('click', function () {
        const label = document.getElementById('copy-notes-label');
        navigator.clipboard?.writeText(this.dataset.notes || '').then(function () {
            if (!label) return;
            const original = label.textContent;
            label.textContent = 'Copied';
            setTimeout(function () { label.textContent = original; }, 1500);
        });
    });

    /* ----------------------------------------------------------------
     * Review form (risk selector, chips, template, fullscreen, autosave)
     * ---------------------------------------------------------------- */
    const textarea = document.getElementById('consultant_notes');
    if (!textarea) return;

    const charCount = document.getElementById('char-count');
    const draftStatus = document.getElementById('draft-status');
    const submitHint = document.getElementById('submit-hint');
    const draftKey = 'consultant_draft_{{ $assessment->id }}';

    function updateCount() {
        const len = textarea.value.length;
        charCount.textContent = len + ' / 5000';
        submitHint.textContent = len < 20 ? 'Add a bit more detail for a thorough review.' : '';
    }
    updateCount();

    // Risk level selector
    const riskButtons = document.querySelectorAll('.risk-option');
    const riskInput = document.getElementById('risk_level_input');
    const riskError = document.getElementById('risk-error');

    riskButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            riskButtons.forEach(b => b.classList.remove('border-slate-900', 'bg-slate-50'));
            this.classList.add('border-slate-900', 'bg-slate-50');
            riskInput.value = this.dataset.risk;
            riskError?.classList.add('hidden');
        });
    });

    // Quick-insert chips
    document.querySelectorAll('.chip-insert').forEach(function (chip) {
        chip.addEventListener('click', function () {
            const text = this.textContent.replace(/^\+\s*/, '').trim();
            const bullet = (textarea.value.trim().length ? '\n' : '') + '• ' + text;
            textarea.value += bullet;
            textarea.dispatchEvent(new Event('input'));
            textarea.focus();
        });
    });

    // Insert structured template
    document.getElementById('insert-template')?.addEventListener('click', function () {
        const template = "Strengths:\n- \n\nRisks & Gaps:\n- \n\nRecommendations:\n- \n";
        if (!textarea.value.trim() || confirm('This will append a structured template to your notes. Continue?')) {
            textarea.value += (textarea.value.trim().length ? '\n\n' : '') + template;
            textarea.dispatchEvent(new Event('input'));
            textarea.focus();
        }
    });

    // Fullscreen toggle
    const wrapper = document.getElementById('textarea-wrapper');
    const fullscreenBtn = document.getElementById('toggle-fullscreen');

    fullscreenBtn?.addEventListener('click', function () {
        const isExpanded = wrapper.classList.toggle('fixed');
        if (isExpanded) {
            wrapper.classList.add('inset-6', 'z-50', 'bg-white', 'p-6', 'rounded-2xl', 'shadow-2xl', 'border', 'border-slate-200');
            textarea.classList.add('h-full');
            textarea.rows = 20;
            fullscreenBtn.textContent = 'Collapse';
        } else {
            wrapper.classList.remove('inset-6', 'z-50', 'bg-white', 'p-6', 'rounded-2xl', 'shadow-2xl', 'border', 'border-slate-200');
            textarea.classList.remove('h-full');
            textarea.rows = 9;
            fullscreenBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M20.25 3.75v4.5m0-4.5h-4.5m4.5 0L15 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15m11.25 5.25v-4.5m0 4.5h-4.5m4.5 0L15 15" /></svg> Expand';
        }
    });

    // Local draft autosave
    let saveTimeout;
    textarea.addEventListener('input', function () {
        updateCount();
        clearTimeout(saveTimeout);
        draftStatus.textContent = 'Saving draft…';
        saveTimeout = setTimeout(function () {
            try {
                localStorage.setItem(draftKey, textarea.value);
                draftStatus.textContent = 'Draft saved locally';
            } catch (e) {
                draftStatus.textContent = '';
            }
        }, 600);
    });

    // Offer to restore a draft if one exists and differs from server content
    try {
        const savedDraft = localStorage.getItem(draftKey);
        const draftBanner = document.getElementById('draft-banner');

        if (savedDraft && savedDraft.trim() && savedDraft !== textarea.value) {
            draftBanner.classList.remove('hidden');

            document.getElementById('restore-draft')?.addEventListener('click', function () {
                textarea.value = savedDraft;
                textarea.dispatchEvent(new Event('input'));
                draftBanner.classList.add('hidden');
            });

            document.getElementById('dismiss-draft')?.addEventListener('click', function () {
                localStorage.removeItem(draftKey);
                draftBanner.classList.add('hidden');
            });
        }
    } catch (e) {
        // localStorage unavailable, skip silently
    }

    // Validate risk level before allowing submit; keyboard shortcut to submit
    const form = document.getElementById('review-form');

    form?.addEventListener('submit', function (e) {
        if (!riskInput.value) {
            e.preventDefault();
            riskError?.classList.remove('hidden');
            document.getElementById('risk-level-group')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        try { localStorage.removeItem(draftKey); } catch (err) {}
    });

    textarea.addEventListener('keydown', function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
            e.preventDefault();
            form?.requestSubmit();
        }
    });

});
</script>

@endsection