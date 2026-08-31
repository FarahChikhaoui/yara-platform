@extends('layouts.yara')

@section('content')

<div class="max-w-6xl mx-auto space-y-10 pb-16">
{{-- Roadmap finalized toast --}}
<div
    id="roadmap-finalized-toast"
    class="fixed right-6 top-6 z-[200] hidden w-full max-w-sm translate-y-[-10px] opacity-0 transition-all duration-300"
>
    <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-white px-5 py-4 shadow-xl shadow-slate-900/10">

        <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            <svg
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2.5"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4.5 12.75l6 6 9-13.5"
                />
            </svg>
        </div>

        <div class="min-w-0 flex-1">
            <p class="text-sm font-bold text-slate-950">
                Roadmap finalized
            </p>

            <p class="mt-1 text-sm leading-5 text-slate-500">
                The roadmap was released to the client and the client was notified by email.
            </p>
        </div>

        <button
            type="button"
            id="close-roadmap-finalized-toast"
            class="flex-none text-slate-400 transition hover:text-slate-700"
            aria-label="Close notification"
        >
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
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>

    </div>
</div>
    {{-- Dashboard --}}
<a href="{{ route('consultant.dashboard') }}"
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



        <div class="flex items-center gap-2">
            
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
                    Expand all
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

<details class="response-group group overflow-hidden rounded-2xl border border-slate-200 bg-white">
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

   <!--  {{-- Consultant Validation --}}
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

    </section> -->

    {{-- ================================================================
     AI ASSESSMENT BRIEF
     Reuses the AI readiness analysis already generated for the client.
     No additional AI request is made here.
     ================================================================ --}}

@php
    $consultantAiAnalysis = null;

    if (!empty($assessment->ai_executive_summary)) {

        $decodedConsultantAnalysis = json_decode(
            $assessment->ai_executive_summary,
            true
        );

        if (
            json_last_error() === JSON_ERROR_NONE
            && is_array($decodedConsultantAnalysis)
        ) {
            $consultantAiAnalysis = $decodedConsultantAnalysis;
        }
    }

    $consultantSummaryGeneratedAt =
        $assessment->ai_summary_generated_at ?? null;
@endphp


<section
    id="consultant-ai-summary-container"
    class="rounded-2xl border border-slate-200 bg-white shadow-sm"
>
@if(!$consultantAiAnalysis)

    {{-- Empty state: consultant can generate the brief --}}
    <div class="p-8">

        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            <div class="max-w-2xl">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-100 text-yellow-700">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.813 15.904L9 18l-.813-2.096a4.5 4.5 0 00-2.591-2.591L3.5 12.5l2.096-.813a4.5 4.5 0 002.591-2.591L9 7l.813 2.096a4.5 4.5 0 002.591 2.591l2.096.813-2.096.813a4.5 4.5 0 00-2.591 2.591z"
                            />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                            AI Assessment Brief
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-slate-950">
                            Generate readiness analysis
                        </h2>
                    </div>

                </div>

                <p class="mt-4 text-sm leading-6 text-slate-500">
                    Generate an AI-assisted interpretation of the organization's
                    assessment evidence to support your consultant review.
                    This analysis is diagnostic and does not generate the
                    Transformation Roadmap.
                </p>

            </div>

           <form
    id="consultant-ai-summary-form"
    method="POST"
    action="{{ route('consultant.ai-summary.generate', $assessment) }}"
    class="flex-none"
>
    @csrf

    <button
        type="submit"
        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-slate-950"
    >
        <svg
            class="h-4 w-4 text-yellow-600"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9.813 15.904L9 18l-.813-2.096a4.5 4.5 0 00-2.591-2.591L3.5 12.5l2.096-.813a4.5 4.5 0 002.591-2.591L9 7l.813 2.096a4.5 4.5 0 002.591 2.591l2.096.813-2.096.813a4.5 4.5 0 00-2.591 2.591z"
            />
        </svg>

        Generate Brief
    </button>
</form>

        </div>

    </div>

@else

    {{-- Header --}}
    <div class="flex flex-col gap-5 p-7 sm:flex-row sm:items-start sm:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                    AI Assessment Brief
                </p>

                <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-2.5 py-1 text-[11px] font-semibold text-yellow-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
                    AI Generated
                </span>

            </div>

            <h2 class="mt-2 text-xl font-bold text-slate-950">
                Readiness signals for consultant review
            </h2>

            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                AI-generated interpretation of the assessment evidence.
                Use this brief as supporting context when reviewing the
                transformation roadmap.
            </p>

        </div>


        <div class="flex flex-none items-center gap-3">

    @if($consultantSummaryGeneratedAt)
        <span class="text-xs text-slate-400">
            Generated {{ $consultantSummaryGeneratedAt->diffForHumans() }}
        </span>
    @endif

    <form
    id="consultant-ai-summary-regenerate-form"
    method="POST"
    action="{{ route('consultant.ai-summary.generate', $assessment) }}"
>
        @csrf

        <button
            type="submit"
            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-slate-900"
        >
            <svg
                class="h-3.5 w-3.5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.023 9.348h4.992V4.356M2.985 19.644v-4.992h4.992M4.93 9.348a7.5 7.5 0 0112.321-2.786l3.764 2.786M3 14.652l3.764 2.786a7.5 7.5 0 0012.321-2.786"
                />
            </svg>

            Regenerate
        </button>
    </form>

</div>

    </div>


    {{-- Headline --}}
    @if(!empty($consultantAiAnalysis['headline']))

        <div class="border-t border-slate-100 px-7 py-5">

            <p class="text-base font-semibold leading-7 text-slate-800">
                “{{ $consultantAiAnalysis['headline'] }}”
            </p>

        </div>

    @endif


    {{-- Compact signals --}}
    <div class="grid border-t border-slate-100 md:grid-cols-3">

        {{-- Priority risk --}}
        <div class="p-6 md:border-r md:border-slate-100">

            <div class="flex items-center gap-2">

                <span class="h-2 w-2 rounded-full bg-red-500"></span>

                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                    Priority Risk
                </p>

            </div>

            <p class="mt-3 font-bold text-slate-950">
                {{ $consultantAiAnalysis['priority_risk']['title'] ?? '—' }}
            </p>

            @if(!empty($consultantAiAnalysis['priority_risk']['dimension']))

                <p class="mt-1 text-xs font-semibold text-red-600">
                    {{ $consultantAiAnalysis['priority_risk']['dimension'] }}
                </p>

            @endif

        </div>


        {{-- Strategic strength --}}
        <div class="border-t border-slate-100 p-6 md:border-r md:border-t-0">

            <div class="flex items-center gap-2">

                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                    Strategic Strength
                </p>

            </div>

            <p class="mt-3 font-bold text-slate-950">
                {{ $consultantAiAnalysis['strategic_strength']['title'] ?? '—' }}
            </p>

            @if(!empty($consultantAiAnalysis['strategic_strength']['dimension']))

                <p class="mt-1 text-xs font-semibold text-emerald-600">
                    {{ $consultantAiAnalysis['strategic_strength']['dimension'] }}
                </p>

            @endif

        </div>


        {{-- Readiness pattern --}}
        <div class="border-t border-slate-100 p-6 md:border-t-0">

            <div class="flex items-center gap-2">

                <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                    Readiness Pattern
                </p>

            </div>

            <p class="mt-3 font-bold text-slate-950">
                {{ $consultantAiAnalysis['readiness_pattern']['title'] ?? '—' }}
            </p>

        </div>

    </div>


    {{-- Expand --}}
    <div class="border-t border-slate-100 px-7 py-4">

        <button
            type="button"
            id="consultant-ai-brief-toggle"
            class="group inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-yellow-700"
            aria-expanded="false"
        >
            <span id="consultant-ai-brief-label">
                View full analysis
            </span>

            <svg
                id="consultant-ai-brief-chevron"
                class="h-4 w-4 transition-transform duration-200"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2.5"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19 9l-7 7-7-7"
                />
            </svg>

        </button>

    </div>


    {{-- Full analysis --}}
    <div
        id="consultant-ai-brief-details"
        class="hidden border-t border-slate-100 bg-slate-50/50 px-7 py-6"
    >

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Risk --}}
            @if(!empty($consultantAiAnalysis['priority_risk']['insight']))

                <div>

                    <p class="text-sm font-bold text-slate-950">
                        Priority Risk Analysis
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        {{ $consultantAiAnalysis['priority_risk']['insight'] }}
                    </p>

                </div>

            @endif


            {{-- Strength --}}
            @if(!empty($consultantAiAnalysis['strategic_strength']['insight']))

                <div>

                    <p class="text-sm font-bold text-slate-950">
                        Strategic Strength Analysis
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        {{ $consultantAiAnalysis['strategic_strength']['insight'] }}
                    </p>

                </div>

            @endif


            {{-- Pattern --}}
            @if(!empty($consultantAiAnalysis['readiness_pattern']['insight']))

                <div>

                    <p class="text-sm font-bold text-slate-950">
                        Readiness Pattern
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        {{ $consultantAiAnalysis['readiness_pattern']['insight'] }}
                    </p>

                </div>

            @endif


            {{-- Country context --}}
            @if(!empty($consultantAiAnalysis['country_context']))

                <div>

                    <p class="text-sm font-bold text-slate-950">
                        Country Context
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        {{ $consultantAiAnalysis['country_context'] }}
                    </p>

                </div>

            @endif

        </div>

    </div>

@endif

</section>

{{-- TRANSFORMATION ROADMAP WORKSPACE --}}
<section id="roadmap-workspace" class="scroll-mt-24">
    @if($assessment->transformation_status === 'submitted')
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Header --}}
        <div class="border-b border-slate-100 px-8 py-7">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                <div class="max-w-3xl">

                    <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                        Transformation Roadmap
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-950">
                        Build the AI Transformation Roadmap
                    </h2>

                    <p class="mt-2 max-w-2xl text-slate-500">
                        Generate an initial roadmap from the organization's assessment
                        results and transformation brief, then refine it with expert
                        judgment before delivery.
                    </p>

                </div>

                <span
                    class="inline-flex w-fit items-center gap-2 rounded-full
                           bg-amber-50 px-3 py-1.5 text-sm font-semibold text-amber-700"
                >
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    Roadmap not generated
                </span>

            </div>

        </div>


        {{-- Client transformation brief --}}
        <div class="px-8 py-7">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-bold text-slate-950">
                        Client Transformation Brief
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Objectives and constraints submitted by the organization.
                    </p>
                </div>

            </div>


            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Target maturity --}}
                <div class="rounded-xl bg-slate-50 p-5">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Target Maturity
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $assessment->roadmapPreference?->target_maturity ?? 'Not specified' }}
                    </p>

                </div>


                {{-- Timeline --}}
                <div class="rounded-xl bg-slate-50 p-5">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Timeline
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $assessment->roadmapPreference?->timeframe ?? 'Not specified' }}
                    </p>

                </div>


                {{-- Investment --}}
                <div class="rounded-xl bg-slate-50 p-5">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Investment Capacity
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $assessment->roadmapPreference?->budget_level ?? 'Not specified' }}
                    </p>

                </div>


                {{-- Current readiness --}}
                <div class="rounded-xl bg-slate-50 p-5">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Current Readiness
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ number_format($assessment->company_score ?? 0, 1) }}
                        <span class="font-normal text-slate-400">/100</span>
                    </p>

                </div>

            </div>


            {{-- Strategic priorities --}}
            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Strategic Priorities
                </p>

                <div class="mt-3 flex flex-wrap gap-2">

                    @forelse(
                        $assessment->roadmapPreference?->strategic_priorities ?? []
                        as $priority
                    )

                        <span
                            class="rounded-full border border-slate-200 bg-white
                                   px-3 py-1.5 text-sm font-medium text-slate-700"
                        >
                            {{ $priority }}
                        </span>

                    @empty

                        <span class="text-sm text-slate-400">
                            No strategic priorities specified.
                        </span>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Roadmap generation --}}
        <div class="border-t border-slate-100 bg-slate-50/60 px-8 py-7">

            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                <div class="max-w-2xl">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-xl bg-yellow-100 text-yellow-700"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9.813 15.904L9 18l-.813-2.096a4.5
                                       4.5 0 00-2.591-2.591L3.5 12.5l2.096-.813
                                       a4.5 4.5 0 002.591-2.591L9 7l.813 2.096
                                       a4.5 4.5 0 002.591 2.591l2.096.813-2.096.813
                                       a4.5 4.5 0 00-2.591 2.591z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M18.259 8.715L18 9.5l-.259-.785
                                       a2.25 2.25 0 00-1.456-1.456L15.5 7
                                       l.785-.259a2.25 2.25 0 001.456-1.456
                                       L18 4.5l.259.785a2.25 2.25 0
                                       001.456 1.456L20.5 7l-.785.259
                                       a2.25 2.25 0 00-1.456 1.456z"
                                />
                            </svg>
                        </div>

                        <div>

                            <h3 class="font-bold text-slate-950">
                                Generate Roadmap Draft
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Create a structured starting point using assessment
                                results, maturity gaps and the client's priorities.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="flex-none">

    <button
        type="button"
        id="generate-roadmap-button"
        data-url="{{ route('consultant.roadmap.generate', $assessment) }}"
        class="inline-flex items-center gap-2 rounded-xl
               bg-slate-950 px-6 py-3.5 font-semibold text-white
               shadow-sm transition hover:bg-slate-800
               disabled:cursor-not-allowed disabled:opacity-60"
    >

        <svg
            id="generate-roadmap-icon"
            class="h-4 w-4"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9.813 15.904L9 18l-.813-2.096
                   a4.5 4.5 0 00-2.591-2.591L3.5
                   12.5l2.096-.813a4.5 4.5 0
                   002.591-2.591L9 7l.813 2.096
                   a4.5 4.5 0 002.591 2.591l2.096
                   .813-2.096.813a4.5 4.5 0
                   00-2.591 2.591z"
            />
        </svg>

       <span
    id="generate-roadmap-label"
    data-roadmap-button-label
>
    Generate Draft Roadmap
</span>

    </button>

</div>
            </div>


            

        </div>

    </div>
@elseif(
    in_array($assessment->transformation_status, ['in_review', 'roadmap_ready'])
    && $assessment->transformationRoadmap
)

    @php
        $roadmap = $assessment->transformationRoadmap;
    @endphp

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Header --}}
        <div class="border-b border-slate-100 px-8 py-7">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                        Transformation Roadmap
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-950">
                        Roadmap Development Workspace
                    </h2>

                    <p class="mt-2 max-w-2xl text-slate-500">
                        Review and refine the generated roadmap before delivering
                        the final transformation plan to the client.
                    </p>
                </div>

               <div class="flex flex-wrap items-center gap-4">

    @if($roadmap->status !== 'ready')

    <button 
        type="button" 
        id="regenerate-roadmap-button" 
        data-url="{{ route('consultant.roadmap.generate', $assessment) }}" 
        title="Regenerate roadmap" 
        class="group inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700 disabled:cursor-not-allowed disabled:opacity-60" 
    > 
        <svg 
            class="h-3.5 w-3.5 transition-transform duration-500 group-hover:rotate-180" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor" 
            stroke-width="2" 
        > 
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" 
            /> 
        </svg> 

        <span data-roadmap-button-label> 
            Regenerate 
        </span> 
    </button> 

    <span 
        class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700" 
    > 
        <span class="h-2 w-2 rounded-full bg-blue-500"></span> 
        Draft · In Review 
    </span>

@else

    <span 
        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700"
    >
        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
        Finalized
    </span>

    @if($roadmap->finalized_at)
        <span class="text-xs text-slate-400">
            {{ $roadmap->finalized_at->format('M j, Y · H:i') }}
        </span>
    @endif

@endif

</div>

</div>

</div>

</div>

        {{-- Context summary --}}
        <div class="border-b border-slate-100 bg-slate-50/60 px-8 py-5">

            <div class="flex flex-wrap gap-x-8 gap-y-4">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Current Readiness
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        {{ number_format($assessment->company_score ?? 0, 1) }}/100
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Target
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        {{ $assessment->roadmapPreference?->target_maturity ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Timeline
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        {{ $assessment->roadmapPreference?->timeframe ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Investment
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        {{ $assessment->roadmapPreference?->budget_level ?? '—' }}
                    </p>
                </div>

            </div>

        </div>


       {{-- Initiatives --}}
<div class="px-8 py-7">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h3 class="text-lg font-bold text-slate-950">
                Roadmap Initiatives
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Review the generated initiatives and refine them with your expert judgment.
            </p>
        </div>

        <div class="flex items-center gap-3">

            <button
                type="button"
                id="toggle-all-initiatives"
                class="text-sm font-semibold text-slate-500 transition hover:text-slate-900"
            >
                Expand all
            </button>
@if($roadmap->status !== 'ready')
            <button
    type="button"
    id="add-initiative-button"
    data-url="{{ route('consultant.roadmap.initiatives.create', $assessment) }}"
    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700"
>
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
            d="M12 4.5v15m7.5-7.5h-15"
        />
    </svg>

    Add Initiative
</button>
@endif
        </div>

    </div>


    <div class="mt-6 space-y-4" id="roadmap-initiatives">

        @forelse($roadmap->initiatives as $initiative)

            <article
                class="roadmap-initiative overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:border-slate-300"
            >

                {{-- ALWAYS VISIBLE --}}
                <div class="p-6">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                        <div class="min-w-0 flex-1">

                            {{-- Number / dimension / priority --}}
                            <div class="flex flex-wrap items-center gap-2">

                                <span class="text-xs font-bold text-slate-400">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                @if($initiative->dimension)
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        {{ $initiative->dimension }}
                                    </span>
                                @endif

                                @php
                                    $priorityClass = match(strtolower($initiative->priority ?? '')) {
                                        'high' => 'bg-red-50 text-red-600',
                                        'medium' => 'bg-amber-50 text-amber-600',
                                        'low' => 'bg-emerald-50 text-emerald-600',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp

                                <span class="rounded-full px-2.5 py-1 text-xs font-bold uppercase {{ $priorityClass }}">
                                    {{ $initiative->priority ?? '—' }} priority
                                </span>

                            </div>


                            {{-- Title --}}
                            <h4 class="mt-3 text-lg font-bold text-slate-950">
                                {{ $initiative->title }}
                            </h4>


                            {{-- Description --}}
                            @if($initiative->description)
                                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                                    {{ $initiative->description }}
                                </p>
                            @endif


                            {{-- Compact delivery information --}}
                            <div class="mt-5 flex flex-wrap gap-x-7 gap-y-3">

                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                        Timeline
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ $initiative->phase ?? '—' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                        Investment
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ $initiative->investment ?? '—' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                        Effort
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ ucfirst($initiative->effort ?? '—') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                        Impact
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ ucfirst($initiative->impact ?? '—') }}
                                    </p>
                                </div>

                                @if($initiative->standard_reference)
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                            Standard
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                            {{ $initiative->standard_reference }}
                                        </p>
                                    </div>
                                @endif

                            </div>

                        </div>


                        {{-- Actions --}}
                        <div class="flex flex-none items-center gap-2">
    @if($roadmap->status !== 'ready')

                            <button
    type="button"
    class="edit-initiative-button rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700"

    data-id="{{ $initiative->id }}"

    data-url="{{ route('consultant.roadmap.initiatives.update', $initiative) }}"

    data-title="{{ $initiative->title }}"

    data-description="{{ $initiative->description }}"

    data-business-rationale="{{ $initiative->business_rationale }}"

    data-recommended-actions='@json($initiative->recommended_actions ?? [])'

    data-expected-outcome="{{ $initiative->expected_outcome }}"

    data-success-metrics='@json($initiative->success_metrics ?? [])'

    data-dependencies='@json($initiative->dependencies ?? [])'

    data-dimension="{{ $initiative->dimension }}"

    data-priority="{{ strtolower($initiative->priority ?? 'medium') }}"

    data-phase="{{ $initiative->phase }}"

    data-investment="{{ $initiative->investment }}"

    data-effort="{{ strtolower($initiative->effort ?? '') }}"

    data-impact="{{ strtolower($initiative->impact ?? '') }}"

    data-standard-reference="{{ $initiative->standard_reference }}"

    data-consultant-guidance="{{ $initiative->consultant_guidance }}"
>
    Edit
</button>

                           <button
    type="button"
    class="remove-initiative-button rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"

    data-id="{{ $initiative->id }}"
    data-title="{{ $initiative->title }}"
    data-url="{{ route('consultant.roadmap.initiatives.delete', $initiative) }}"
>
    Remove
</button>
    @endif


                        </div>

                    </div>


                    {{-- EXPAND BUTTON --}}
                    <div class="mt-5 border-t border-slate-100 pt-4">

                        <button
                            type="button"
                            class="initiative-toggle inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-yellow-700"
                            aria-expanded="false"
                        >
                            <span class="initiative-toggle-label">
                                View details
                            </span>

                            <svg
                                class="initiative-chevron h-4 w-4 transition-transform duration-200"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>

                    </div>

                </div>


                {{-- COLLAPSIBLE DETAILS --}}
                <div class="initiative-details hidden border-t border-slate-100 bg-slate-50/30 px-6 pb-6">

                    {{-- Why this matters --}}
                    @if($initiative->business_rationale)

                        <div class="mt-6 rounded-xl border border-yellow-200 bg-yellow-50 p-5">

                            <div class="flex items-start gap-3">

                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-yellow-100 text-yellow-700">
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
                                            d="M12 18h.01M9.75 9a2.25 2.25 0 114.5 0c0 1.5-2.25 1.875-2.25 3.375"
                                        />
                                        <circle cx="12" cy="12" r="9"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-bold text-slate-950">
                                        Why this matters
                                    </p>

                                    <p class="mt-2 text-sm leading-6 text-slate-700">
                                        {{ $initiative->business_rationale }}
                                    </p>
                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- Recommended actions --}}
                    @if(!empty($initiative->recommended_actions))

                        <div class="mt-6">

                            <p class="text-sm font-bold text-slate-950">
                                Recommended Actions
                            </p>

                            <div class="mt-3 space-y-3">

                                @foreach($initiative->recommended_actions as $action)

                                    <div class="flex items-start gap-3">

                                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-yellow-400"></span>

                                        <p class="text-sm leading-6 text-slate-600">
                                            {{ $action }}
                                        </p>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif


                    {{-- Expected outcome --}}
                    @if($initiative->expected_outcome)

                        <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Expected Outcome
                            </p>

                            <p class="mt-2 text-sm leading-6 text-slate-700">
                                {{ $initiative->expected_outcome }}
                            </p>

                        </div>

                    @endif


                    {{-- Success metrics --}}
                    @if(!empty($initiative->success_metrics))

                        <div class="mt-6">

                            <p class="text-sm font-bold text-slate-950">
                                Success Metrics
                            </p>

                            <div class="mt-3 grid gap-3 sm:grid-cols-2">

                                @foreach($initiative->success_metrics as $metric)

                                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">

                                        <div class="flex items-start gap-3">

                                            <svg
                                                class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2.5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7"
                                                />
                                            </svg>

                                            <p class="text-sm leading-5 text-slate-700">
                                                {{ $metric }}
                                            </p>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif


                    {{-- Dependencies --}}
                    @if(!empty($initiative->dependencies))

                        <div class="mt-6">

                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                Dependencies
                            </p>

                            <div class="mt-2 flex flex-wrap gap-2">

                                @foreach($initiative->dependencies as $dependency)

                                    <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                        {{ $dependency }}
                                    </span>

                                @endforeach

                            </div>

                        </div>

                    @endif


                    {{-- Close details --}}
                    <div class="mt-6 border-t border-slate-200 pt-4">

                        <button
                            type="button"
                            class="initiative-close inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-yellow-700"
                        >
                            Hide details

                            <svg
                                class="h-4 w-4 rotate-180"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>

                        </button>

                    </div>

                </div>

            </article>

        @empty

            <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center">

                <p class="text-sm text-slate-500">
                    No roadmap initiatives have been generated yet.
                </p>

            </div>

        @endforelse

    </div>

</div>

        {{-- Expert guidance --}}
        <div class="border-t border-slate-100 bg-slate-50/50 px-8 py-7">

            <div>
                <h3 class="text-lg font-bold text-slate-950">
                    Expert Guidance
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Add consultant-specific context that should accompany the final roadmap.
                </p>
            </div>


            <div class="mt-6 grid gap-5 lg:grid-cols-2">

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        Consultant Notes
                    </label>

                   <textarea
    id="consultant-notes"
    data-autosave-url="{{ route('consultant.roadmap.guidance.update', $assessment) }}"
    rows="5"
    @if($roadmap->status === 'ready') disabled @endif
    placeholder="Add implementation guidance, sequencing recommendations or organizational context..."
    class="mt-2 w-full resize-none rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-700 outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
>{{ $roadmap->consultant_notes }}</textarea>
                </div>


                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        Risks & Dependencies
                    </label>

                   <textarea
    id="risks-dependencies"
    rows="5"
    @if($roadmap->status === 'ready') disabled @endif
    placeholder="Identify important dependencies, delivery risks or prerequisites..."
    class="mt-2 w-full resize-none rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-700 outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
>{{ $roadmap->risks_dependencies }}</textarea>
                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="flex flex-col gap-4 border-t border-slate-100 px-8 py-6 sm:flex-row sm:items-center sm:justify-between">

           

            <div class="flex gap-3">

               
@if($roadmap->status !== 'ready')

                <button
    type="button"
    id="finalize-roadmap-button"
    data-url="{{ route('consultant.roadmap.finalize', $assessment) }}"
    class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
>
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
            d="M4.5 12.75l6 6 9-13.5"
        />
    </svg>

    <span id="finalize-roadmap-label">
        Finalize Roadmap
    </span>
</button>
@endif
            </div>

        </div>

    </div>

@endif
</section>
</div>
<script>
/* ================================================================
 * FINALIZE ROADMAP
 * ================================================================ */

const finalizeRoadmapButton =
    document.getElementById('finalize-roadmap-button');

const finalizeRoadmapLabel =
    document.getElementById('finalize-roadmap-label');


finalizeRoadmapButton?.addEventListener('click', async function () {

    const url = finalizeRoadmapButton.dataset.url;

    if (!url) {
        return;
    }


    /*
     * Finalization is intentionally deliberate because
     * the roadmap becomes locked afterward.
     */
    const confirmed = confirm(
        'Finalize this roadmap?\n\n' +
        'Once finalized, the roadmap will be locked and can no longer ' +
        'be edited, regenerated, or have initiatives added or removed.'
    );

    if (!confirmed) {
        return;
    }


    finalizeRoadmapButton.disabled = true;

    if (finalizeRoadmapLabel) {
        finalizeRoadmapLabel.textContent = 'Finalizing...';
    }


    try {

        const response = await fetch(url, {

            method: 'POST',

            headers: {
                'Accept': 'application/json',

                'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content
                    ?? document.querySelector(
                        'input[name="_token"]'
                    )?.value
                    ?? '',
            },

        });


        const data = await response.json();


        if (!response.ok) {
            throw new Error(
                data.message || 'Could not finalize the roadmap.'
            );
        }


       /*
 * Remember the successful finalization across the reload.
 * The reloaded locked workspace will display a confirmation toast.
 */
sessionStorage.setItem(
    'yara-roadmap-finalized',
    'true'
);

window.location.reload();


    } catch (error) {

        console.error(error);

        alert(
            error.message ||
            'The roadmap could not be finalized.'
        );

        finalizeRoadmapButton.disabled = false;

        if (finalizeRoadmapLabel) {
            finalizeRoadmapLabel.textContent = 'Finalize Roadmap';
        }

    }

});

</script>

{{-- ================================================================
     EDIT ROADMAP INITIATIVE MODAL
     ================================================================ --}}
<div
    id="edit-initiative-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
>
    <div
        class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
    >

        {{-- Header --}}
        <div class="flex items-start justify-between border-b border-slate-200 px-6 py-5">

            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-yellow-600">
                    Consultant Review
                </p>

                <h2
    id="initiative-modal-title"
    class="mt-1 text-xl font-bold text-slate-950"
>
    Edit Initiative
</h2>

                <p
    id="initiative-modal-description"
    class="mt-1 text-sm text-slate-500"
>
    Refine the AI-generated recommendation using your expert judgment.
</p>
            </div>

            <button
                type="button"
                id="close-edit-initiative-modal"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                aria-label="Close"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

        </div>


        {{-- Form --}}
        <form id="edit-initiative-form" class="flex min-h-0 flex-1 flex-col">

            @csrf

            <input
                type="hidden"
                id="edit-initiative-id"
            >

            <input
                type="hidden"
                id="edit-initiative-url"
            >


            {{-- Scrollable content --}}
            <div class="flex-1 space-y-6 overflow-y-auto px-6 py-6">
<p class="text-xs text-slate-400">
    Fields marked <span class="font-bold text-red-500">*</span> are required. All other fields are optional.
</p>
                {{-- Title --}}
                <div>
                    <label
                        for="edit-title"
                        class="text-sm font-bold text-slate-700"
                    >
Initiative title <span class="text-red-500">*</span>                    </label>

                    <input
                        type="text"
                        id="edit-title"
                        name="title"
                        required
                        class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    >
                </div>


                {{-- Description --}}
                <div>
                    <label
                        for="edit-description"
                        class="text-sm font-bold text-slate-700"
                    >
Description <span class="text-red-500">*</span>                    </label>

                    <textarea
                        id="edit-description"
                        name="description"
                        rows="4"
                        required
                        class="mt-2 w-full resize-y rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    ></textarea>
                </div>


                {{-- Rationale --}}
                <div>
                    <label
                        for="edit-business-rationale"
                        class="text-sm font-bold text-slate-700"
                    >
                        Why this matters
                    </label>

                    <textarea
                        id="edit-business-rationale"
                        name="business_rationale"
                        rows="4"
                        class="mt-2 w-full resize-y rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    ></textarea>
                </div>


                {{-- Recommended actions --}}
                <div>
                    <label
                        for="edit-recommended-actions"
                        class="text-sm font-bold text-slate-700"
                    >
                        Recommended Actions
                    </label>

                    <p class="mt-1 text-xs text-slate-400">
                        One action per line.
                    </p>

                    <textarea
                        id="edit-recommended-actions"
                        rows="5"
                        class="mt-2 w-full resize-y rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    ></textarea>
                </div>


                {{-- Expected outcome --}}
                <div>
                    <label
                        for="edit-expected-outcome"
                        class="text-sm font-bold text-slate-700"
                    >
                        Expected Outcome
                    </label>

                    <textarea
                        id="edit-expected-outcome"
                        name="expected_outcome"
                        rows="3"
                        class="mt-2 w-full resize-y rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    ></textarea>
                </div>


                {{-- Success metrics --}}
                <div>
                    <label
                        for="edit-success-metrics"
                        class="text-sm font-bold text-slate-700"
                    >
                        Success Metrics
                    </label>

                    <p class="mt-1 text-xs text-slate-400">
                        One metric per line.
                    </p>

                    <textarea
                        id="edit-success-metrics"
                        rows="4"
                        class="mt-2 w-full resize-y rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    ></textarea>
                </div>


                {{-- Dependencies --}}
                <div>
                    <label
                        for="edit-dependencies"
                        class="text-sm font-bold text-slate-700"
                    >
                        Dependencies
                    </label>

                    <p class="mt-1 text-xs text-slate-400">
                        One dependency per line.
                    </p>

                    <textarea
                        id="edit-dependencies"
                        rows="3"
                        class="mt-2 w-full resize-y rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    ></textarea>
                </div>


                {{-- Classification --}}
                <div class="grid gap-5 sm:grid-cols-2">

                    <div>
                        <label
                            for="edit-dimension"
                            class="text-sm font-bold text-slate-700"
                        >
Dimension <span class="text-red-500">*</span>                        </label>

                       <select
    id="edit-dimension"
    name="dimension"
    required
    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
>
    <option value="">Select a dimension</option>

    @foreach($dimensionScores as $dimensionName => $score)
        <option value="{{ $dimensionName }}">
            {{ $dimensionName }}
        </option>
    @endforeach
</select>
                    </div>


                    <div>
                        <label
                            for="edit-priority"
                            class="text-sm font-bold text-slate-700"
                        >
                            Priority
                        </label>

                        <select
                            id="edit-priority"
                            name="priority"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                        >
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>

                </div>


                {{-- Delivery --}}
                <div class="grid gap-5 sm:grid-cols-2">

                    <div>
                        <label
                            for="edit-phase"
                            class="text-sm font-bold text-slate-700"
                        >
                            Timeline
                        </label>

                        <input
                            type="text"
                            id="edit-phase"
                            name="phase"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                        >
                    </div>


                    <div>
                        <label
                            for="edit-investment"
                            class="text-sm font-bold text-slate-700"
                        >
                            Investment
                        </label>

                        <input
                            type="text"
                            id="edit-investment"
                            name="investment"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                        >
                    </div>


                    <div>
                        <label
                            for="edit-effort"
                            class="text-sm font-bold text-slate-700"
                        >
                            Effort
                        </label>

                        <select
                            id="edit-effort"
                            name="effort"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                        >
                            <option value="">Not specified</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>


                    <div>
                        <label
                            for="edit-impact"
                            class="text-sm font-bold text-slate-700"
                        >
                            Impact
                        </label>

                        <select
                            id="edit-impact"
                            name="impact"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                        >
                            <option value="">Not specified</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                </div>


                {{-- Standard --}}
                <div>
                    <label
                        for="edit-standard-reference"
                        class="text-sm font-bold text-slate-700"
                    >
                        Standard Reference
                    </label>

                    <input
                        type="text"
                        id="edit-standard-reference"
                        name="standard_reference"
                        class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    >
                </div>


                {{-- Consultant guidance --}}
                <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-5">

                    <label
                        for="edit-consultant-guidance"
                        class="text-sm font-bold text-slate-900"
                    >
                        Consultant Guidance
                    </label>

                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Add implementation context or expert guidance that was not part of the AI-generated draft.
                    </p>

                    <textarea
                        id="edit-consultant-guidance"
                        name="consultant_guidance"
                        rows="4"
                        class="mt-3 w-full resize-y rounded-xl border border-yellow-200 bg-white px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    ></textarea>

                </div>

            </div>


            {{-- Footer --}}
            <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-6 py-4">

                <p
                    id="edit-initiative-error"
                    class="hidden text-sm font-medium text-red-600"
                ></p>

                <div class="ml-auto flex items-center gap-3">

                    <button
                        type="button"
                        id="cancel-edit-initiative"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-200 hover:text-slate-800"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        id="save-edit-initiative"
                        class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        Save changes
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>
{{-- Scroll back to top --}}
<button
    type="button"
    id="scroll-to-top"
    aria-label="Back to top"
    title="Back to top"
    class="fixed bottom-6 right-6 z-50 hidden h-12 w-12 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-lg shadow-slate-900/10 transition-all duration-200 hover:-translate-y-1 hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700 print:hidden"
>
    <svg
        class="h-5 w-5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2.5"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M5 15l7-7 7 7"
        />
    </svg>
</button>
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
let expanded = false;
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


<script>
document.addEventListener('DOMContentLoaded', function () {

    async function generateRoadmap(button) {

        const url = button.dataset.url;

        if (!url) {
            alert('Roadmap URL is missing.');
            return;
        }

        const label = button.querySelector('[data-roadmap-button-label]');
        const originalLabel = label ? label.textContent.trim() : '';

        button.disabled = true;

        if (label) {
            label.textContent =
                button.id === 'regenerate-roadmap-button'
                    ? 'Regenerating...'
                    : 'Generating roadmap...';
        }

        try {

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');

            if (!csrfToken) {
                throw new Error('CSRF token missing.');
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Unable to generate roadmap.'
                );
            }

            window.location.reload();

        } catch (error) {

            console.error('Roadmap error:', error);

            alert(error.message);

            button.disabled = false;

            if (label) {
                label.textContent = originalLabel;
            }
        }
    }


    // GENERATE ROADMAP
    const generateButton =
        document.getElementById('generate-roadmap-button');

    if (generateButton) {
        generateButton.addEventListener('click', function () {
            generateRoadmap(generateButton);
        });
    }


    // REGENERATE ROADMAP
    const regenerateButton =
        document.getElementById('regenerate-roadmap-button');

    if (regenerateButton) {
        regenerateButton.addEventListener('click', function () {
            generateRoadmap(regenerateButton);
        });
    }

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ================================================================
     * ROADMAP INITIATIVE COLLAPSE / EXPAND
     * ================================================================ */

    const initiatives = document.querySelectorAll('.roadmap-initiative');
    const toggleAllButton = document.getElementById('toggle-all-initiatives');


    function setInitiativeState(article, expanded) {

        const details = article.querySelector('.initiative-details');
        const toggle = article.querySelector('.initiative-toggle');
        const label = article.querySelector('.initiative-toggle-label');
        const chevron = article.querySelector('.initiative-chevron');

        if (!details || !toggle) {
            return;
        }


        if (expanded) {

            details.classList.remove('hidden');

            toggle.setAttribute('aria-expanded', 'true');

            if (label) {
                label.textContent = 'Hide details';
            }

            if (chevron) {
                chevron.classList.add('rotate-180');
            }

        } else {

            details.classList.add('hidden');

            toggle.setAttribute('aria-expanded', 'false');

            if (label) {
                label.textContent = 'View details';
            }

            if (chevron) {
                chevron.classList.remove('rotate-180');
            }

        }
    }


    /* Individual View details buttons */
    initiatives.forEach(function (article) {

        const toggle = article.querySelector('.initiative-toggle');
        const closeButton = article.querySelector('.initiative-close');

        toggle?.addEventListener('click', function () {

            const currentlyExpanded =
                toggle.getAttribute('aria-expanded') === 'true';

            setInitiativeState(article, !currentlyExpanded);

            updateToggleAllLabel();
        });


        closeButton?.addEventListener('click', function () {

            setInitiativeState(article, false);

            updateToggleAllLabel();

            article.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });

        });

    });
/* ================================================================
 * EDIT INITIATIVE MODAL
 * ================================================================ */

const editModal = document.getElementById('edit-initiative-modal');
const editForm = document.getElementById('edit-initiative-form');

const closeEditModalButton =
    document.getElementById('close-edit-initiative-modal');

const cancelEditButton =
    document.getElementById('cancel-edit-initiative');


function parseArrayData(value) {
    if (!value) {
        return [];
    }

    try {
        const parsed = JSON.parse(value);
        return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
        console.error('Could not parse initiative array:', error);
        return [];
    }
}


function closeEditInitiativeModal() {

    if (!editModal) {
        return;
    }

    editModal.classList.add('hidden');
    editModal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');
}


/*
 * Open modal when consultant clicks Edit.
 */
document.querySelectorAll('.edit-initiative-button').forEach(function (button) {

    button.addEventListener('click', function () {

        if (!editModal) {
            return;
        }
const modalTitle =
    document.getElementById('initiative-modal-title');

const modalDescription =
    document.getElementById('initiative-modal-description');

const saveButton =
    document.getElementById('save-edit-initiative');

if (modalTitle) {
    modalTitle.textContent = 'Edit Initiative';
}

if (modalDescription) {
    modalDescription.textContent =
        'Refine the AI-generated recommendation using your expert judgment.';
}

if (saveButton) {
    saveButton.textContent = 'Save changes';
}
        const recommendedActions =
            parseArrayData(button.dataset.recommendedActions);

        const successMetrics =
            parseArrayData(button.dataset.successMetrics);

        const dependencies =
            parseArrayData(button.dataset.dependencies);


        /*
         * Hidden metadata
         */
        document.getElementById('edit-initiative-id').value =
            button.dataset.id || '';

        document.getElementById('edit-initiative-url').value =
            button.dataset.url || '';


        /*
         * Main content
         */
        document.getElementById('edit-title').value =
            button.dataset.title || '';

        document.getElementById('edit-description').value =
            button.dataset.description || '';

        document.getElementById('edit-business-rationale').value =
            button.dataset.businessRationale || '';

        document.getElementById('edit-recommended-actions').value =
            recommendedActions.join('\n');

        document.getElementById('edit-expected-outcome').value =
            button.dataset.expectedOutcome || '';

        document.getElementById('edit-success-metrics').value =
            successMetrics.join('\n');

        document.getElementById('edit-dependencies').value =
            dependencies.join('\n');


        /*
         * Classification / delivery
         */
        document.getElementById('edit-dimension').value =
            button.dataset.dimension || '';

        document.getElementById('edit-priority').value =
            button.dataset.priority || 'medium';

        document.getElementById('edit-phase').value =
            button.dataset.phase || '';

        document.getElementById('edit-investment').value =
            button.dataset.investment || '';

        document.getElementById('edit-effort').value =
            button.dataset.effort || '';

        document.getElementById('edit-impact').value =
            button.dataset.impact || '';

        document.getElementById('edit-standard-reference').value =
            button.dataset.standardReference || '';

        document.getElementById('edit-consultant-guidance').value =
            button.dataset.consultantGuidance || '';


        /*
         * Clear previous error.
         */
        const errorBox =
            document.getElementById('edit-initiative-error');

        if (errorBox) {
            errorBox.textContent = '';
            errorBox.classList.add('hidden');
        }


        /*
         * Show modal.
         */
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    });

});


/*
 * X button
 */
closeEditModalButton?.addEventListener(
    'click',
    closeEditInitiativeModal
);


/*
 * Cancel button
 */
cancelEditButton?.addEventListener(
    'click',
    closeEditInitiativeModal
);


/*
 * Clicking dark backdrop closes modal.
 */
editModal?.addEventListener('click', function (event) {

    if (event.target === editModal) {
        closeEditInitiativeModal();
    }

});


/*
 * Escape closes modal.
 */
document.addEventListener('keydown', function (event) {

    if (
        event.key === 'Escape'
        && editModal
        && !editModal.classList.contains('hidden')
    ) {
        closeEditInitiativeModal();
    }

});
/* ================================================================
 * SAVE EDITED INITIATIVE
 * ================================================================ */

editForm?.addEventListener('submit', async function (event) {

    event.preventDefault();

    const saveButton =
        document.getElementById('save-edit-initiative');

    const errorBox =
        document.getElementById('edit-initiative-error');

    const url =
        document.getElementById('edit-initiative-url')?.value;

   const initiativeId =
    document.getElementById('edit-initiative-id')?.value;

/*
 * If we have an initiative ID, we're editing.
 * If there is no ID, we're creating a new initiative.
 */
const isEditing = Boolean(initiativeId);

if (!url) {
    return;
}

    /*
     * Convert multiline fields back into arrays.
     */
    const linesToArray = function (value) {

        return value
            .split('\n')
            .map(function (item) {
                return item.trim();
            })
            .filter(function (item) {
                return item !== '';
            });

    };


    const payload = {

        title:
            document.getElementById('edit-title').value.trim(),

        description:
            document.getElementById('edit-description').value.trim(),

        business_rationale:
            document.getElementById('edit-business-rationale').value.trim(),

        recommended_actions:
            linesToArray(
                document.getElementById('edit-recommended-actions').value
            ),

        expected_outcome:
            document.getElementById('edit-expected-outcome').value.trim(),

        success_metrics:
            linesToArray(
                document.getElementById('edit-success-metrics').value
            ),

        dependencies:
            linesToArray(
                document.getElementById('edit-dependencies').value
            ),

        dimension:
            document.getElementById('edit-dimension').value.trim(),

        priority:
            document.getElementById('edit-priority').value,

        phase:
            document.getElementById('edit-phase').value.trim(),

        investment:
            document.getElementById('edit-investment').value.trim(),

        effort:
            document.getElementById('edit-effort').value,

        impact:
            document.getElementById('edit-impact').value,

        standard_reference:
            document.getElementById('edit-standard-reference').value.trim(),

        consultant_guidance:
            document.getElementById('edit-consultant-guidance').value.trim(),

    };


    /*
     * Loading state.
     */
   if (saveButton) {
    saveButton.disabled = true;
    saveButton.textContent =
        isEditing ? 'Saving...' : 'Adding...';
}

    if (errorBox) {
        errorBox.classList.add('hidden');
        errorBox.textContent = '';
    }


    try {

        const response = await fetch(url, {

method: isEditing ? 'PATCH' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',

                'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content
                    ?? document.querySelector(
                        '#edit-initiative-form input[name="_token"]'
                    )?.value
                    ?? '',
            },

            body: JSON.stringify(payload),

        });


        const data = await response.json();


        if (!response.ok) {

            /*
             * Laravel validation errors.
             */
            if (response.status === 422 && data.errors) {

                const firstError =
                    Object.values(data.errors)
                        .flat()
                        .find(Boolean);

                throw new Error(
                    firstError || 'Please check the initiative fields.'
                );
            }


            throw new Error(
                data.message || 'Could not update the initiative.'
            );
        }


        /*
         * Find the Edit button belonging to this initiative.
         */
        const editButton = isEditing
    ? document.querySelector(
        `.edit-initiative-button[data-id="${initiativeId}"]`
    )
    : null;


        /*
         * Update the button's stored data.
         *
         * This is important because reopening Edit should show
         * the newly saved version, not the old AI-generated values.
         */
        if (editButton && data.initiative) {

            const item = data.initiative;

            editButton.dataset.title =
                item.title ?? '';

            editButton.dataset.description =
                item.description ?? '';

            editButton.dataset.businessRationale =
                item.business_rationale ?? '';

            editButton.dataset.recommendedActions =
                JSON.stringify(item.recommended_actions ?? []);

            editButton.dataset.expectedOutcome =
                item.expected_outcome ?? '';

            editButton.dataset.successMetrics =
                JSON.stringify(item.success_metrics ?? []);

            editButton.dataset.dependencies =
                JSON.stringify(item.dependencies ?? []);

            editButton.dataset.dimension =
                item.dimension ?? '';

            editButton.dataset.priority =
                item.priority ?? 'medium';

            editButton.dataset.phase =
                item.phase ?? '';

            editButton.dataset.investment =
                item.investment ?? '';

            editButton.dataset.effort =
                item.effort ?? '';

            editButton.dataset.impact =
                item.impact ?? '';

            editButton.dataset.standardReference =
                item.standard_reference ?? '';

            editButton.dataset.consultantGuidance =
                item.consultant_guidance ?? '';
        }


        /*
         * Close modal.
         */
        closeEditInitiativeModal();


        /*
         * For this first version, refresh the roadmap content so every
         * displayed field reflects the saved database values.
         *
         * We'll replace this with direct DOM updates next so there is
         * genuinely zero page reload.
         */
        window.location.reload();


    } catch (error) {

        console.error(error);

        if (errorBox) {
            errorBox.textContent =
                error.message || 'Could not save changes.';

            errorBox.classList.remove('hidden');
        }

    } finally {

       if (saveButton) {
    saveButton.disabled = false;
    saveButton.textContent =
        isEditing ? 'Save changes' : 'Add Initiative';
}

    }

});

    /* Expand all / Collapse all */
    function updateToggleAllLabel() {

        if (!toggleAllButton || !initiatives.length) {
            return;
        }

        const allExpanded = Array.from(initiatives).every(function (article) {

            const toggle = article.querySelector('.initiative-toggle');

            return toggle?.getAttribute('aria-expanded') === 'true';

        });

        toggleAllButton.textContent =
            allExpanded ? 'Collapse all' : 'Expand all';
    }


    toggleAllButton?.addEventListener('click', function () {

        const allExpanded = Array.from(initiatives).every(function (article) {

            const toggle = article.querySelector('.initiative-toggle');

            return toggle?.getAttribute('aria-expanded') === 'true';

        });


        initiatives.forEach(function (article) {

            setInitiativeState(article, !allExpanded);

        });


        updateToggleAllLabel();

    });
    /* ================================================================
 * ADD INITIATIVE
 * ================================================================ */

const addInitiativeButton =
    document.getElementById('add-initiative-button');

addInitiativeButton?.addEventListener('click', function () {

    if (!editModal) {
        return;
    }

    /*
     * Tell the form that we're creating, not editing.
     */
    document.getElementById('edit-initiative-id').value = '';

    document.getElementById('edit-initiative-url').value =
        addInitiativeButton.dataset.url || '';


    /*
     * Change modal heading.
     */
    const modalTitle =
        document.getElementById('initiative-modal-title');

    const modalDescription =
        document.getElementById('initiative-modal-description');

    if (modalTitle) {
        modalTitle.textContent = 'Add Initiative';
    }

    if (modalDescription) {
        modalDescription.textContent =
            'Add a consultant-defined initiative to this transformation roadmap.';
    }


    /*
     * Empty all fields.
     */
    document.getElementById('edit-title').value = '';
    document.getElementById('edit-description').value = '';
    document.getElementById('edit-business-rationale').value = '';
    document.getElementById('edit-recommended-actions').value = '';
    document.getElementById('edit-expected-outcome').value = '';
    document.getElementById('edit-success-metrics').value = '';
    document.getElementById('edit-dependencies').value = '';

    document.getElementById('edit-dimension').value = '';

    document.getElementById('edit-priority').value =
        'medium';

    document.getElementById('edit-phase').value = '';
    document.getElementById('edit-investment').value = '';
    document.getElementById('edit-effort').value = '';
    document.getElementById('edit-impact').value = '';

    document.getElementById('edit-standard-reference').value = '';
    document.getElementById('edit-consultant-guidance').value = '';


    /*
     * Clear previous errors.
     */
    const errorBox =
        document.getElementById('edit-initiative-error');

    if (errorBox) {
        errorBox.textContent = '';
        errorBox.classList.add('hidden');
    }


    /*
     * Change save button text.
     */
    const saveButton =
        document.getElementById('save-edit-initiative');

    if (saveButton) {
        saveButton.textContent = 'Add Initiative';
    }


    /*
     * Open modal.
     */
    editModal.classList.remove('hidden');
    editModal.classList.add('flex');

    document.body.classList.add('overflow-hidden');

});
/* ================================================================
 * REMOVE ROADMAP INITIATIVE
 * ================================================================ */

document.querySelectorAll('.remove-initiative-button').forEach(function (button) {

    button.addEventListener('click', async function () {

        const initiativeId = button.dataset.id;
        const initiativeTitle = button.dataset.title;
        const url = button.dataset.url;

        if (!url || !initiativeId) {
            return;
        }

        const confirmed = confirm(
            `Remove "${initiativeTitle}" from this roadmap?\n\n` +
            `This action cannot be undone.`
        );

        if (!confirmed) {
            return;
        }

        const originalText = button.textContent;

        button.disabled = true;
        button.textContent = 'Removing...';

        try {

            const response = await fetch(url, {
                method: 'DELETE',

                headers: {
                    'Accept': 'application/json',

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content
                        ?? document.querySelector(
                            'input[name="_token"]'
                        )?.value
                        ?? '',
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Could not remove the initiative.'
                );
            }

            /*
             * For now reload so initiative numbers and roadmap
             * state are guaranteed to match the database.
             */
            window.location.reload();

        } catch (error) {

            console.error(error);

            alert(
                error.message ||
                'The initiative could not be removed.'
            );

            button.disabled = false;
            button.textContent = originalText;
        }

    });
    /* ================================================================
 * ROADMAP GUIDANCE — SILENT AUTOSAVE
 * ================================================================ */

const consultantNotes =
    document.getElementById('consultant-notes');

const risksDependencies =
    document.getElementById('risks-dependencies');

let guidanceAutosaveTimer = null;


/*
 * Save both guidance fields together.
 */
async function autosaveRoadmapGuidance() {

    if (!consultantNotes || !risksDependencies) {
        return;
    }

    /*
     * Disabled means the roadmap is finalized.
     */
    if (consultantNotes.disabled || risksDependencies.disabled) {
        return;
    }

    const url = consultantNotes.dataset.autosaveUrl;

    if (!url) {
        return;
    }

    try {

        const response = await fetch(url, {

            method: 'PATCH',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',

                'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content
                    ?? document.querySelector(
                        'input[name="_token"]'
                    )?.value
                    ?? '',
            },

            body: JSON.stringify({
                consultant_notes: consultantNotes.value,
                risks_dependencies: risksDependencies.value,
            }),

        });


        if (!response.ok) {

            const data = await response.json().catch(() => ({}));

            throw new Error(
                data.message || 'Could not save roadmap guidance.'
            );
        }

    } catch (error) {

        /*
         * Silent autosave:
         * don't interrupt the consultant with alerts while typing.
         * Log failures for debugging instead.
         */
        console.error(
            'Roadmap guidance autosave failed:',
            error
        );
    }
}


/*
 * Wait 800ms after the consultant stops typing.
 */
function scheduleGuidanceAutosave() {

    clearTimeout(guidanceAutosaveTimer);

    guidanceAutosaveTimer = setTimeout(
        autosaveRoadmapGuidance,
        800
    );
}


consultantNotes?.addEventListener(
    'input',
    scheduleGuidanceAutosave
);

risksDependencies?.addEventListener(
    'input',
    scheduleGuidanceAutosave
);

});

    /* ================================================================
     * SCROLL TO TOP BUTTON
     * ================================================================ */

    const scrollTopButton = document.getElementById('scroll-to-top');


    function updateScrollTopButton() {

        if (!scrollTopButton) {
            return;
        }


        if (window.scrollY > 500) {

            scrollTopButton.classList.remove('hidden');
            scrollTopButton.classList.add('flex');

        } else {

            scrollTopButton.classList.add('hidden');
            scrollTopButton.classList.remove('flex');

        }

    }


    window.addEventListener('scroll', updateScrollTopButton, {
        passive: true
    });


    scrollTopButton?.addEventListener('click', function () {

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    });


    updateScrollTopButton();

});
/* ================================================================
 * ROADMAP FINALIZED TOAST
 * ================================================================ */

const roadmapFinalizedToast =
    document.getElementById('roadmap-finalized-toast');

const closeRoadmapFinalizedToast =
    document.getElementById('close-roadmap-finalized-toast');

let roadmapFinalizedToastTimer = null;


function hideRoadmapFinalizedToast() {

    if (!roadmapFinalizedToast) {
        return;
    }

    roadmapFinalizedToast.classList.add(
        'opacity-0',
        'translate-y-[-10px]'
    );

    setTimeout(() => {
        roadmapFinalizedToast.classList.add('hidden');
    }, 300);
}


if (
    roadmapFinalizedToast &&
    sessionStorage.getItem('yara-roadmap-finalized') === 'true'
) {

    /*
     * Consume the flag so refreshing the page manually
     * does not show the toast again.
     */
    sessionStorage.removeItem('yara-roadmap-finalized');

    roadmapFinalizedToast.classList.remove('hidden');

    requestAnimationFrame(() => {

        roadmapFinalizedToast.classList.remove(
            'opacity-0',
            'translate-y-[-10px]'
        );

    });

    roadmapFinalizedToastTimer =
        setTimeout(hideRoadmapFinalizedToast, 5000);
}


closeRoadmapFinalizedToast?.addEventListener('click', function () {

    clearTimeout(roadmapFinalizedToastTimer);

    hideRoadmapFinalizedToast();

});
/* ================================================================
 * CONSULTANT AI ASSESSMENT BRIEF
 * ================================================================ */

document.addEventListener('click', function (event) {

    const toggle = event.target.closest(
        '#consultant-ai-brief-toggle'
    );

    if (!toggle) {
        return;
    }

    const details =
        document.getElementById('consultant-ai-brief-details');

    const label =
        document.getElementById('consultant-ai-brief-label');

    const chevron =
        document.getElementById('consultant-ai-brief-chevron');

    const expanded =
        toggle.getAttribute('aria-expanded') === 'true';

    toggle.setAttribute(
        'aria-expanded',
        expanded ? 'false' : 'true'
    );

    details?.classList.toggle(
        'hidden',
        expanded
    );

    if (label) {
        label.textContent =
            expanded
                ? 'View full analysis'
                : 'Hide full analysis';
    }

    chevron?.classList.toggle(
        'rotate-180',
        !expanded
    );

});
/* ================================================================
 * CONSULTANT AI BRIEF — GENERATE / REGENERATE WITHOUT PAGE RELOAD
 * ================================================================ */

document.addEventListener('submit', async function (event) {

    const form = event.target.closest(
        '#consultant-ai-summary-form, #consultant-ai-summary-regenerate-form'
    );

    if (!form) {
        return;
    }
        event.preventDefault();

        const button = form.querySelector('button[type="submit"]');

        if (!button) {
            return;
        }

        const originalHtml = button.innerHTML;

        button.disabled = true;
        button.innerHTML = `
            <span class="inline-flex items-center gap-2">
                <svg
                    class="h-4 w-4 animate-spin"
                    viewBox="0 0 24 24"
                    fill="none"
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

                Generating...
            </span>
        `;

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

            if (!response.ok) {

                if (response.status === 422 && data.errors) {

                    const firstError = Object.values(data.errors)
                        .flat()
                        .find(Boolean);

                    throw new Error(
                        firstError || 'Could not generate the AI brief.'
                    );
                }

                throw new Error(
                    data.message || 'Could not generate the AI brief.'
                );
            }

const container =
    document.getElementById('consultant-ai-summary-container');

if (!container || !data.analysis) {
    throw new Error('The AI brief was generated but could not be displayed.');
}

const analysis = data.analysis;

const escapeHtml = function (value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
};

const risk = analysis.priority_risk || {};
const strength = analysis.strategic_strength || {};
const pattern = analysis.readiness_pattern || {};

container.innerHTML = `
    <div class="flex flex-col gap-5 p-7 sm:flex-row sm:items-start sm:justify-between">

        <div>
            <div class="flex flex-wrap items-center gap-3">

                <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                    AI Assessment Brief
                </p>

                <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-2.5 py-1 text-[11px] font-semibold text-yellow-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
                    AI Generated
                </span>

            </div>

            <h2 class="mt-2 text-xl font-bold text-slate-950">
                Readiness signals for consultant review
            </h2>

            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                AI-generated interpretation of the assessment evidence.
                Use this brief as supporting context when reviewing the
                transformation roadmap.
            </p>
        </div>

        <div class="flex flex-none items-center gap-3">

            <span class="text-xs text-slate-400">
                Generated ${escapeHtml(data.generated_at || 'just now')}
            </span>

            <form
                id="consultant-ai-summary-regenerate-form"
                action="${escapeHtml(form.action)}"
                method="POST"
            >
                <input
                    type="hidden"
                    name="_token"
                    value="${escapeHtml(
                        form.querySelector('input[name="_token"]')?.value || ''
                    )}"
                >

                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-slate-900"
                >
                    <svg
                        class="h-3.5 w-3.5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.023 9.348h4.992V4.356M2.985 19.644v-4.992h4.992M4.93 9.348a7.5 7.5 0 0112.321-2.786l3.764 2.786M3 14.652l3.764 2.786a7.5 7.5 0 0012.321-2.786"
                        />
                    </svg>

                    Regenerate
                </button>
            </form>

        </div>
    </div>

    ${analysis.headline ? `
        <div class="border-t border-slate-100 px-7 py-5">
            <p class="text-base font-semibold leading-7 text-slate-800">
                “${escapeHtml(analysis.headline)}”
            </p>
        </div>
    ` : ''}

    <div class="grid border-t border-slate-100 md:grid-cols-3">

        <div class="p-6 md:border-r md:border-slate-100">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                    Priority Risk
                </p>
            </div>

            <p class="mt-3 font-bold text-slate-950">
                ${escapeHtml(risk.title || '—')}
            </p>

            ${risk.dimension ? `
                <p class="mt-1 text-xs font-semibold text-red-600">
                    ${escapeHtml(risk.dimension)}
                </p>
            ` : ''}
        </div>


        <div class="border-t border-slate-100 p-6 md:border-r md:border-t-0">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                    Strategic Strength
                </p>
            </div>

            <p class="mt-3 font-bold text-slate-950">
                ${escapeHtml(strength.title || '—')}
            </p>

            ${strength.dimension ? `
                <p class="mt-1 text-xs font-semibold text-emerald-600">
                    ${escapeHtml(strength.dimension)}
                </p>
            ` : ''}
        </div>


        <div class="border-t border-slate-100 p-6 md:border-t-0">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                    Readiness Pattern
                </p>
            </div>

            <p class="mt-3 font-bold text-slate-950">
                ${escapeHtml(pattern.title || '—')}
            </p>
        </div>

    </div>


    <div class="border-t border-slate-100 px-7 py-4">

        <button
            type="button"
            id="consultant-ai-brief-toggle"
            class="group inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-yellow-700"
            aria-expanded="false"
        >
            <span id="consultant-ai-brief-label">
                View full analysis
            </span>

            <svg
                id="consultant-ai-brief-chevron"
                class="h-4 w-4 transition-transform duration-200"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2.5"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>

    </div>


    <div
        id="consultant-ai-brief-details"
        class="hidden border-t border-slate-100 bg-slate-50/50 px-7 py-6"
    >
        <div class="grid gap-6 lg:grid-cols-2">

            ${risk.insight ? `
                <div>
                    <p class="text-sm font-bold text-slate-950">
                        Priority Risk Analysis
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        ${escapeHtml(risk.insight)}
                    </p>
                </div>
            ` : ''}


            ${strength.insight ? `
                <div>
                    <p class="text-sm font-bold text-slate-950">
                        Strategic Strength Analysis
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        ${escapeHtml(strength.insight)}
                    </p>
                </div>
            ` : ''}


            ${pattern.insight ? `
                <div>
                    <p class="text-sm font-bold text-slate-950">
                        Readiness Pattern
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        ${escapeHtml(pattern.insight)}
                    </p>
                </div>
            ` : ''}

        </div>
    </div>
`;
        } catch (error) {

            console.error(error);

            alert(
                error.message ||
                'The AI brief could not be generated. Please try again.'
            );

        } finally {

            button.disabled = false;
            button.innerHTML = originalHtml;

        }

    

});
</script>
@endsection