@extends('layouts.yara')

@section('content')

<div class="space-y-8">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

        <div>
            <div class="flex items-center gap-2">
                <span class="flex h-6 w-6 items-center justify-center rounded-md bg-yellow-50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-3.5 w-3.5 text-yellow-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 4.5l8.25 5.25M4.5 9.75v9a1 1 0 0 0 1 1H9v-5.25a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1V19.5h3.5a1 1 0 0 0 1-1v-9" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
                    Workspace
                </p>
            </div>

            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Welcome, {{ Auth::user()->name }}
            </h1>

            <p class="mt-2 text-slate-500">
                Manage your organization and AI readiness assessments.
            </p>
        </div>

        @if(Auth::user()->company_id)
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-950 text-sm font-bold text-white">
                    {{ strtoupper(substr(Auth::user()->company->name, 0, 1)) }}
                </span>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-slate-900">
                        {{ Auth::user()->company->name }}
                    </p>
                    <p class="text-xs text-slate-400">
                        {{ Auth::user()->company->industry ?? 'No industry set' }}
                    </p>
                </div>
            </div>
        @endif

    </div>

    @if(!Auth::user()->company_id)

        {{-- ONBOARDING: CREATE ORGANIZATION --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

            <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-50">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-5 w-5 text-yellow-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 21V4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21M4.5 21H3m17.25 0V9.375c0-.621-.504-1.125-1.125-1.125h-4.125M20.25 21H21m-9-14.25h.008v.008H12V6.75Zm0 3h.008v.008H12V9.75Zm0 3h.008v.008H12v-.008Zm-3-6h.008v.008H9V6.75Zm0 3h.008v.008H9V9.75Zm0 3h.008v.008H9v-.008Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">
                            Create your organization profile
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Takes less than a minute — you can edit this later.
                        </p>
                    </div>
                </div>

                <form method="POST" action="/company/store" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Company name
                        </label>
                        <input
                            id="name"
                            name="name"
                            required
                            placeholder="e.g. Yara Industries"
                            class="w-full rounded-lg border-slate-300 text-slate-900 placeholder:text-slate-400 focus:border-slate-950 focus:ring-slate-950"
                        >
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div>
                            <label for="industry" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Industry
                            </label>
                            <input
                                id="industry"
                                name="industry"
                                placeholder="e.g. Manufacturing"
                                class="w-full rounded-lg border-slate-300 text-slate-900 placeholder:text-slate-400 focus:border-slate-950 focus:ring-slate-950"
                            >
                        </div>

                        <div>
                            <label for="country" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Country
                            </label>
                            <select
                                id="country"
                                name="country"
                                required
                                class="w-full rounded-lg border-slate-300 text-slate-900 focus:border-slate-950 focus:ring-slate-950"
                            >
                                <option value="">Select country</option>

                                @foreach($countries as $country)
                                    <option value="{{ $country }}">
                                        {{ $country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <button class="group inline-flex items-center gap-2 rounded-xl bg-slate-950 px-6 py-3 font-semibold text-white transition hover:bg-slate-800">
                        Save organization
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition group-hover:translate-x-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                        </svg>
                    </button>

                </form>

            </div>

            <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-slate-950 to-slate-800 p-8 text-white">

                <p class="text-sm font-semibold uppercase tracking-wide text-yellow-400">
                    What happens next
                </p>

                <p class="mt-2 text-slate-300">
                    Once your organization is set up, you'll be able to:
                </p>

                <ul class="mt-6 space-y-4 text-sm">

                    @foreach([
                        'Run a guided AI readiness assessment for your organization',
                        'Get a maturity score benchmarked against clear milestones',
                        'Track how your readiness improves across future assessments',
                    ] as $step)
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-yellow-400/15">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 text-yellow-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </span>
                            <span class="text-slate-200">{{ $step }}</span>
                        </li>
                    @endforeach

                </ul>

            </div>

        </div>

    @else

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

                <div class="flex items-start justify-between gap-6">

                    <div class="flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-yellow-50">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-5 w-5 text-yellow-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">
                                AI Readiness Assessment
                            </h2>

                            <p class="mt-2 text-slate-500">
                                Launch a new assessment to evaluate your organization's AI maturity.
                            </p>
                        </div>
                    </div>

                   @php
    $inProgressAssessment = $assessments
        ->where('status', 'in_progress')
        ->sortByDesc('created_at')
        ->first();
@endphp

@if($inProgressAssessment)

    <a href="{{ route('assessment.resume', $inProgressAssessment) }}"
       class="group inline-flex shrink-0 items-center gap-2 rounded-xl bg-slate-950 px-6 py-3 font-semibold text-white transition hover:bg-slate-800">
        Continue Assessment

        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             class="h-4 w-4 transition group-hover:translate-x-0.5">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
        </svg>
    </a>

@else

    <a href="/assessment/start"
       class="group inline-flex shrink-0 items-center gap-2 rounded-xl bg-slate-950 px-6 py-3 font-semibold text-white transition hover:bg-slate-800">
        Start Assessment

        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             class="h-4 w-4 transition group-hover:translate-x-0.5">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
        </svg>
    </a>

@endif

                </div>

            </div>

            <div class="bg-gradient-to-br from-slate-950 to-slate-800 rounded-2xl shadow-sm p-8 text-white">

                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-300">
                        Organization
                    </p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-4 w-4 text-yellow-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 21V4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21M4.5 21H3m17.25 0V9.375c0-.621-.504-1.125-1.125-1.125h-4.125M20.25 21H21" />
                        </svg>
                    </span>
                </div>

                <h2 class="mt-2 text-2xl font-bold">
                    {{ Auth::user()->company->name }}
                </h2>

                <div class="mt-6 space-y-3 border-t border-white/10 pt-5 text-sm">

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Industry</span>
                        <span class="font-medium">{{ Auth::user()->company->industry ?? '—' }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Country</span>
                        <span class="font-medium">{{ Auth::user()->company->country ?? '—' }}</span>
                    </div>

                </div>

            </div>

        </div>

        {{-- AI READINESS PROGRESS --}}

        @if($completedAssessments->count() > 0)

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

                <div>
                    <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
                        Progress Intelligence
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-900">
                        AI Readiness Progress
                    </h2>

                    <p class="mt-2 text-slate-500">
                        Track how your organization's AI readiness evolves across assessments.
                    </p>
                </div>


                {{-- KPI cards --}}
                <div class="mt-7 grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Current score --}}
                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">

                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Current Score
                            </p>
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-3.5 w-3.5 text-yellow-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                            </span>
                        </div>

                        <div class="mt-3 flex items-end gap-1">

                            <span class="text-3xl font-bold text-slate-900">
                                {{ number_format($currentScore, 1) }}
                            </span>

                            <span class="mb-1 text-sm text-slate-400">
                                /100
                            </span>

                        </div>

                    </div>


                    {{-- Change from previous --}}
                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">

                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Change Since Previous
                            </p>
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-3.5 w-3.5 text-yellow-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.28m5.94 2.28-2.28 5.941" />
                                </svg>
                            </span>
                        </div>

                        @if($scoreChange !== null)

                            <p class="mt-3 flex items-center gap-1 text-3xl font-bold
                                {{ $scoreChange > 0
                                    ? 'text-emerald-600'
                                    : ($scoreChange < 0
                                        ? 'text-red-500'
                                        : 'text-slate-700') }}">

                                {{ $scoreChange > 0 ? '+' : '' }}
                                {{ number_format($scoreChange, 1) }}

                                @if($scoreChange > 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 10.5 7.5-7.5 7.5 7.5M12 3v18" />
                                    </svg>
                                @elseif($scoreChange < 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 13.5-7.5 7.5-7.5-7.5M12 21V3" />
                                    </svg>
                                @endif

                            </p>

                        @else

                            <p class="mt-4 text-lg font-semibold text-slate-400">
                                Not enough data
                            </p>

                        @endif

                    </div>


                    {{-- Number of completed assessments --}}
                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">

                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Completed Assessments
                            </p>
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-3.5 w-3.5 text-yellow-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </span>
                        </div>

                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ $completedAssessments->count() }}
                        </p>

                    </div>

                </div>


                {{-- Chart area --}}
                <div class="mt-7">

                    <div class="flex items-center justify-between">

                        <p class="text-sm font-semibold text-slate-700">
                            Organizational readiness over time
                        </p>

                        <p class="flex items-center gap-1.5 text-xs text-slate-400">
                            <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                            Company score
                        </p>

                    </div>

                    <div class="mt-4 h-72 rounded-2xl border border-slate-100 bg-slate-50 p-4">

                        <canvas id="organizationProgressChart"></canvas>

                    </div>

                </div>

            </div>

        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

            <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h2 class="text-2xl font-bold text-slate-900">
            Previous Assessments
        </h2>

        <p class="mt-1 text-slate-500">
            Review your previous assessment submissions and results.
        </p>
    </div>


    <div class="flex items-center gap-3">

        @if($assessments->count())
            <span class="hidden shrink-0 rounded-full bg-slate-100
                         px-3 py-1 text-xs font-semibold text-slate-500
                         sm:inline-block">
                {{ $assessments->count() }} total
            </span>
        @endif


        @if($completedAssessments->count() >= 2)

            <a
                href="{{ route('assessment.comparison') }}"
                class="group inline-flex shrink-0 items-center gap-2
                       rounded-xl bg-slate-950 px-4 py-2.5
                       text-sm font-semibold text-white
                       shadow-sm transition hover:bg-slate-800"
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
                        d="M8 7h11m0 0-3-3m3 3-3 3M16 17H5m0 0 3 3m-3-3 3-3"
                    />
                </svg>

                Compare Assessments

            </a>

        @endif
@if($completedAssessments->count() >= 1)

    <a
        href="{{ route('assessment.simulator') }}"
        class="group inline-flex shrink-0 items-center gap-2
               rounded-xl border border-slate-200 bg-white px-4 py-2.5
               text-sm font-semibold text-slate-700
               shadow-sm transition
               hover:border-slate-300 hover:bg-slate-50"
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
                d="M4 19V9m5 10V5m5 14v-7m5 7V3"
            />
        </svg>

        Explore Scenarios

    </a>

@endif
    </div>

</div>

            @if($assessments->count())

                <div class="overflow-x-auto">

                    <table class="w-full text-left">

                        <thead class="border-b border-slate-200">
                            <tr>
                                <th class="pb-4 text-xs uppercase tracking-wide text-slate-500">
                                    Assessment
                                </th>
                                <th class="pb-4 text-xs uppercase tracking-wide text-slate-500">
                                    Score & Maturity
                                </th>
                                <th class="pb-4 text-xs uppercase tracking-wide text-slate-500">
                                    Status
                                </th>

                                <th class="pb-4 text-xs uppercase tracking-wide text-slate-500">
                                    Date
                                </th>

                                <th class="pb-4 text-xs uppercase tracking-wide text-slate-500 text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @foreach($assessments as $assessment)

                                <tr class="transition hover:bg-slate-50/70">

                                    <td class="py-5">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-50 border border-slate-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-4 w-4 text-slate-400">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                            </span>
                                           <span class="font-semibold text-slate-900">
    @if(
        $assessment->payment_status === 'paid'
        && $assessment->engagement_type === 'transformation'
    )
        Transformation Roadmap
    @else
        {{ $assessment->title }}
    @endif
</span>
                                        </div>
                                    </td>
                                    <td class="py-5">
                                        @if($assessment->status === 'completed' && $assessment->company_score !== null)

                                            @php
                                                $assessmentMaturity = $maturityLevels->first(function ($level) use ($assessment) {
                                                    return $assessment->company_score >= $level->min_score
                                                        && $assessment->company_score <= $level->max_score;
                                                });
                                            @endphp

                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900">
                                                    {{ number_format($assessment->company_score, 1) }}
                                                </span>
                                                <span class="text-xs font-normal text-slate-400">/100</span>
                                            </div>

                                            <div class="mt-1.5 h-1.5 w-28 overflow-hidden rounded-full bg-slate-100">
                                                <div class="h-full rounded-full bg-yellow-400" style="width: {{ min(100, max(0, $assessment->company_score)) }}%"></div>
                                            </div>

                                            @if($assessmentMaturity)
                                                <div class="mt-1.5 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
                                                    Level {{ $assessmentMaturity->level }}
                                                    — {{ $assessmentMaturity->label ?? $assessmentMaturity->name }}
                                                </div>
                                            @endif

                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="py-5">

                                        @if(
    $assessment->engagement_type === 'transformation'
    && in_array($assessment->transformation_status, ['submitted', 'in_review'])
)

    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

        @if($assessment->transformation_status === 'in_review')
            Expert Review
        @else
            Awaiting Review
        @endif
    </span>

@elseif(
    $assessment->engagement_type === 'transformation'
    && $assessment->transformation_status === 'roadmap_ready'
)

    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
        Roadmap Ready
    </span>

@elseif($assessment->status === 'completed')

    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
        Completed
    </span>

@else

    <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700">
        <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
        In Progress
    </span>

@endif

                                    </td>

                                    <td class="py-5 text-slate-500">
                                        {{ $assessment->created_at->format('d M Y') }}
                                    </td>

                                    <td class="py-5 text-right">

                                        @if(
    $assessment->engagement_type === 'transformation'
    && in_array($assessment->transformation_status, ['submitted', 'in_review'])
)

    <a
        href="{{ route('assessment.transformation.submitted', $assessment) }}"
        class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-100"
    >
        View Status

        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            class="h-3.5 w-3.5"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
            />
        </svg>
    </a>


@elseif(
    $assessment->engagement_type === 'transformation'
    && $assessment->transformation_status === 'roadmap_ready'
)

    <a
        href="/assessment/results/{{ $assessment->id }}"
        class="inline-flex items-center gap-1.5 rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
    >
        View Roadmap

        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            class="h-3.5 w-3.5"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
            />
        </svg>
    </a>


@elseif($assessment->status === 'completed')

    <a
        href="/assessment/results/{{ $assessment->id }}"
        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
    >
        View Results

        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            class="h-3.5 w-3.5"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
            />
        </svg>
    </a>


@elseif($assessment->status === 'in_progress')

    <a
        href="{{ route('assessment.resume', $assessment) }}"
        class="inline-flex items-center gap-1.5 rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
    >
        Continue Assessment

        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            class="h-3.5 w-3.5"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
            />
        </svg>
    </a>

@endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="border border-dashed border-slate-300 rounded-2xl p-10 text-center">

                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-19.5 0v6a2.25 2.25 0 0 0 2.25 2.25h15a2.25 2.25 0 0 0 2.25-2.25v-6m-19.5 0h4.5a1.5 1.5 0 0 1 1.5 1.5v.75a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5v-.75a1.5 1.5 0 0 1 1.5-1.5h4.5" />
                        </svg>
                    </span>

                    <p class="mt-4 font-semibold text-slate-700">
                        No assessments yet
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Use the "Start Assessment" button above to launch your first AI readiness assessment.
                    </p>

                </div>

            @endif

        </div>

    @endif

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const progressData = @json($progressData);

    const canvas = document.getElementById('organizationProgressChart');

    if (!canvas || progressData.length === 0) {
        return;
    }

    const labels = progressData.map(item => item.date);
    const scores = progressData.map(item => item.score);

    const ctx = canvas.getContext('2d');
    const fillGradient = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 260);
    fillGradient.addColorStop(0, 'rgba(250, 204, 21, 0.28)');
    fillGradient.addColorStop(1, 'rgba(250, 204, 21, 0.02)');

    new Chart(canvas, {

        type: 'line',

        data: {
            labels: labels,

            datasets: [{
                label: 'Organization Score',

                data: scores,

                borderColor: '#facc15',
                backgroundColor: fillGradient,

                borderWidth: 3,

                pointRadius: 4,
                pointHoverRadius: 6,

                pointBackgroundColor: '#facc15',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,

                tension: 0.35,

                fill: true
            }]
        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                intersect: false,
                mode: 'index'
            },

            plugins: {

                legend: {
                    display: false
                },

                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    cornerRadius: 8,
                    titleFont: { weight: '600' },
                    callbacks: {
                        label: function(context) {
                            return 'Score: ' +
                                context.parsed.y.toFixed(1);
                        }
                    }
                }

            },

            scales: {

                y: {
                    beginAtZero: true,
                    max: 100,

                    ticks: {
                        stepSize: 20,
                        color: '#94a3b8'
                    },

                    grid: {
                        color: '#e2e8f0'
                    }
                },

                x: {
                    ticks: {
                        color: '#94a3b8'
                    },

                    grid: {
                        display: false
                    }
                }

            }

        }

    });

});
</script>

@endsection