@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="pointer-events-none fixed -top-24 left-1/2 -z-10 h-72 w-[36rem] -translate-x-1/2 rounded-full bg-yellow-300/10 blur-3xl"></div>

            <div class="flex flex-wrap items-start justify-between gap-4">

                <div>
                    <p class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-amber-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Governance
                    </p>

                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                        Audit Logs
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Trace important administrative and consultant actions across the YARA platform.
                    </p>
                </div>

                <div class="flex h-12 w-12 flex-none items-center justify-center rounded-2xl bg-slate-950 text-yellow-400 shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                    </svg>
                </div>

            </div>

            {{-- Summary stats --}}
            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Total actions
                    </p>
                    <p class="mt-1.5 text-xl font-bold text-slate-950">
                        {{ number_format($logs->total()) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Showing on page
                    </p>
                    <p class="mt-1.5 text-xl font-bold text-slate-950">
                        {{ $logs->count() }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Action types tracked
                    </p>
                    <p class="mt-1.5 text-xl font-bold text-slate-950">
                        {{ count($actions) }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Page
                    </p>
                    <p class="mt-1.5 text-xl font-bold text-slate-950">
                        {{ $logs->currentPage() }} <span class="text-sm font-medium text-slate-400">/ {{ max($logs->lastPage(), 1) }}</span>
                    </p>
                </div>

            </div>
        </div>

{{-- Filters --}}
@php
    $hasActiveFilters =
        request()->filled('search') ||
        request()->filled('action') ||
        request()->filled('date_from') ||
        request()->filled('date_to');

    $activeFilterCount = collect([
        request('search'),
        request('action'),
        request('date_from'),
        request('date_to'),
    ])->filter()->count();

    $hasDateFilter =
        request()->filled('date_from') || request()->filled('date_to');
@endphp

<div class="mb-6 rounded-xl border border-slate-200 bg-white">

    <form
        method="GET"
        action="{{ route('admin.audit-logs.index') }}"
        class="flex flex-wrap items-center gap-2 px-3.5 py-2.5"
    >
        <svg class="h-4 w-4 shrink-0 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
        </svg>

        {{-- Search --}}
        <div class="relative min-w-[160px] flex-1">
            <label for="search" class="sr-only">Search</label>

            <svg class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
            </svg>

            <input
                id="search"
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search user, action, resource…"
                class="h-8 w-full rounded-lg border border-slate-200 bg-slate-50/70
                       pl-8 pr-2 text-xs text-slate-700
                       placeholder:text-slate-400
                       transition focus:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-100"
            >
        </div>

        {{-- Action --}}
        <div class="relative">
            <label for="action" class="sr-only">Action</label>

            <select
                id="action"
                name="action"
                class="h-8 w-36 appearance-none rounded-lg border border-slate-200 bg-slate-50/70
                       pl-2.5 pr-6 text-xs text-slate-700
                       transition focus:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-100"
            >
                <option value="">All actions</option>

                @foreach($actions as $action)
                    <option
                        value="{{ $action }}"
                        @selected(request('action') === $action)
                    >
                        {{ ucwords(str_replace('.', ' ', $action)) }}
                    </option>
                @endforeach
            </select>

            <svg class="pointer-events-none absolute right-2 top-1/2 h-3 w-3 -translate-y-1/2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>

        {{-- Dates (tucked behind a small disclosure so the bar stays slim) --}}
        <details class="relative" @if($hasDateFilter) open @endif>
            <summary class="flex h-8 cursor-pointer list-none items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50/70 px-2.5 text-xs font-medium text-slate-600 transition hover:bg-slate-100 [&::-webkit-details-marker]:hidden">
                <svg class="h-3.5 w-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0V11.25A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                Dates
                @if($hasDateFilter)
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                @endif
            </summary>

            <div class="absolute left-0 top-full z-20 mt-1.5 flex items-end gap-2 rounded-xl border border-slate-200 bg-white p-3 shadow-lg">
                <div>
                    <label for="date_from" class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                        From
                    </label>
                    <input
                        id="date_from"
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="h-8 rounded-lg border border-slate-200 px-2 text-xs text-slate-700
                               transition focus:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-100"
                    >
                </div>

                <div>
                    <label for="date_to" class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                        To
                    </label>
                    <input
                        id="date_to"
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="h-8 rounded-lg border border-slate-200 px-2 text-xs text-slate-700
                               transition focus:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-100"
                    >
                </div>
            </div>
        </details>

        {{-- Apply --}}
        <button
            type="submit"
            class="inline-flex h-8 items-center gap-1.5 whitespace-nowrap rounded-lg bg-slate-950 px-3
                   text-xs font-semibold text-white
                   transition hover:bg-slate-800 active:scale-[0.98]"
        >
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
            </svg>
            Apply
        </button>

        @if($hasActiveFilters)
            <span class="inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-slate-950 px-1.5 text-[11px] font-bold text-white">
                {{ $activeFilterCount }}
            </span>

            <a
                href="{{ route('admin.audit-logs.index') }}"
                class="inline-flex items-center gap-1 text-xs font-medium text-slate-400 transition hover:text-slate-900"
            >
                Clear
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        @endif

    </form>

    {{-- Active filter chips --}}
    @if($hasActiveFilters)
        <div class="flex flex-wrap items-center gap-1.5 border-t border-slate-100 px-3.5 py-2">

            @if(request()->filled('search'))
                <a href="{{ route('admin.audit-logs.index', request()->except('search')) }}"
                   class="group inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 py-0.5 pl-2.5 pr-1.5 text-[11px] font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-100">
                    <span class="text-slate-400">Search</span>
                    “{{ request('search') }}”
                    <svg class="h-3 w-3 text-slate-400 transition group-hover:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif

            @if(request()->filled('action'))
                <a href="{{ route('admin.audit-logs.index', request()->except('action')) }}"
                   class="group inline-flex items-center gap-1 rounded-full border border-blue-100 bg-blue-50 py-0.5 pl-2.5 pr-1.5 text-[11px] font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-100">
                    <span class="text-blue-400">Action</span>
                    {{ ucwords(str_replace('.', ' ', request('action'))) }}
                    <svg class="h-3 w-3 text-blue-400 transition group-hover:text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif

            @if(request()->filled('date_from'))
                <a href="{{ route('admin.audit-logs.index', request()->except('date_from')) }}"
                   class="group inline-flex items-center gap-1 rounded-full border border-amber-100 bg-amber-50 py-0.5 pl-2.5 pr-1.5 text-[11px] font-medium text-amber-700 transition hover:border-amber-200 hover:bg-amber-100">
                    <span class="text-amber-500">From</span>
                    {{ request('date_from') }}
                    <svg class="h-3 w-3 text-amber-500 transition group-hover:text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif

            @if(request()->filled('date_to'))
                <a href="{{ route('admin.audit-logs.index', request()->except('date_to')) }}"
                   class="group inline-flex items-center gap-1 rounded-full border border-amber-100 bg-amber-50 py-0.5 pl-2.5 pr-1.5 text-[11px] font-medium text-amber-700 transition hover:border-amber-200 hover:bg-amber-100">
                    <span class="text-amber-500">To</span>
                    {{ request('date_to') }}
                    <svg class="h-3 w-3 text-amber-500 transition group-hover:text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif

        </div>
    @endif

</div>
        {{-- Logs table --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-900">
                            Platform Activity
                        </h2>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ $logs->total() }} recorded actions
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50/80 backdrop-blur">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                User
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Action
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Resource
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Description
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Date
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse($logs as $log)

                            @php
                                $userName = $log->user?->name ?? 'System';
                                $isSystem = ! $log->user;
                                $initials = collect(explode(' ', trim($userName)))
                                    ->filter()
                                    ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                                    ->take(2)
                                    ->implode('') ?: 'S';

                                $actionLower = strtolower($log->action ?? '');

                                $actionStyle = match(true) {
                                    str_contains($actionLower, 'delete') || str_contains($actionLower, 'remove') => 'bg-red-50 text-red-700 border border-red-100',
                                    str_contains($actionLower, 'create') || str_contains($actionLower, 'generate') || str_contains($actionLower, 'submit') => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                    str_contains($actionLower, 'update') || str_contains($actionLower, 'edit') || str_contains($actionLower, 'review') => 'bg-blue-50 text-blue-700 border border-blue-100',
                                    str_contains($actionLower, 'login') || str_contains($actionLower, 'logout') || str_contains($actionLower, 'auth') => 'bg-purple-50 text-purple-700 border border-purple-100',
                                    default => 'bg-slate-100 text-slate-700 border border-slate-200',
                                };

                                $actionDot = match(true) {
                                    str_contains($actionLower, 'delete') || str_contains($actionLower, 'remove') => 'bg-red-500',
                                    str_contains($actionLower, 'create') || str_contains($actionLower, 'generate') || str_contains($actionLower, 'submit') => 'bg-emerald-500',
                                    str_contains($actionLower, 'update') || str_contains($actionLower, 'edit') || str_contains($actionLower, 'review') => 'bg-blue-500',
                                    str_contains($actionLower, 'login') || str_contains($actionLower, 'logout') || str_contains($actionLower, 'auth') => 'bg-purple-500',
                                    default => 'bg-slate-400',
                                };
                            @endphp

                            <tr class="transition hover:bg-slate-50">

                                {{-- User --}}
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-3">

                                        @if($isSystem)
                                            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a7.71 7.71 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                                {{ $initials }}
                                            </div>
                                        @endif

                                        <div>
                                            <div class="text-sm font-semibold text-slate-900">
                                                {{ $userName }}
                                            </div>

                                            @if($log->user?->email)
                                                <div class="mt-0.5 text-xs text-slate-500">
                                                    {{ $log->user->email }}
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                {{-- Action --}}
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $actionStyle }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $actionDot }}"></span>
                                        {{ str_replace('.', ' ', $log->action) }}
                                    </span>
                                </td>

                                {{-- Resource --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    @if($log->entity_type)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 font-mono text-xs text-slate-600 ring-1 ring-inset ring-slate-200">
                                            {{ $log->entity_type }}

                                            @if($log->entity_id)
                                                <span class="text-slate-400">
                                                    #{{ $log->entity_id }}
                                                </span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                {{-- Description --}}
                                <td class="max-w-xs px-6 py-4 text-sm text-slate-600">
                                    <span class="line-clamp-2" title="{{ $log->description }}">
                                        {{ $log->description ?? '—' }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td class="whitespace-nowrap px-6 py-4" title="{{ $log->created_at->diffForHumans() }}">
                                    <div class="text-sm font-medium text-slate-700">
                                        {{ $log->created_at->format('d M Y') }}
                                    </div>

                                    <div class="mt-0.5 text-xs text-slate-400">
                                        {{ $log->created_at->format('H:i') }}
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                                        </svg>
                                    </div>

                                    <div class="mt-3 text-sm font-semibold text-slate-700">
                                        No audit activity yet
                                    </div>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Important platform actions will appear here.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-500">
                        Showing <span class="font-semibold text-slate-700">{{ $logs->firstItem() }}</span>
                        to <span class="font-semibold text-slate-700">{{ $logs->lastItem() }}</span>
                        of <span class="font-semibold text-slate-700">{{ $logs->total() }}</span> results
                    </p>

                    {{ $logs->links() }}
                </div>
            @endif

        </div>

    </div>
</div>

@endsection