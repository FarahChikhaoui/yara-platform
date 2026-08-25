@extends('layouts.admin')

@section('content')

<div class="flex flex-col min-h-0" style="height: 100%;">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6 flex-shrink-0">
        <div>
            <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
                Admin Management
            </p>
            <div class="flex items-center gap-4 mt-1">
                <h1 class="text-4xl font-bold text-slate-950">Maturity Levels</h1>
                <a href="{{ route('maturity-levels.create') }}"
                   class="flex items-center gap-2 px-4 h-10 rounded-full bg-yellow-400 text-slate-950 text-sm font-semibold hover:scale-105 transition">
                    + Add Level
                </a>
            </div>
            <p class="mt-2 text-slate-500">
                Manage maturity levels and score ranges for assessments.
            </p>
        </div>

        {{-- Total coverage indicator --}}
        @php
            $totalMin = $maturityLevels->min('min_score') ?? 0;
            $totalMax = $maturityLevels->max('max_score') ?? 0;
            $levelCount = $maturityLevels->count();
        @endphp
        <div class="text-right">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Score Coverage</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalMin }} – {{ $totalMax }}</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ $levelCount }} levels defined</p>
        </div>
    </div>

    {{-- SCORE RANGE OVERVIEW BAR --}}
    @if($maturityLevels->count() > 0)
    @php
        $levelColors = [
            1 => ['bg' => '#EF4444', 'light' => '#FEE2E2', 'text' => '#991B1B'],
            2 => ['bg' => '#F97316', 'light' => '#FFEDD5', 'text' => '#9A3412'],
            3 => ['bg' => '#EAB308', 'light' => '#FEF9C3', 'text' => '#713F12'],
            4 => ['bg' => '#22C55E', 'light' => '#DCFCE7', 'text' => '#14532D'],
            5 => ['bg' => '#3B82F6', 'light' => '#DBEAFE', 'text' => '#1E3A8A'],
        ];
        $maxScore = $maturityLevels->max('max_score') ?: 100;
    @endphp
    <div class="mb-6 flex-shrink-0 bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-3">Score Distribution Overview</p>
        <div class="relative h-8 rounded-xl overflow-hidden flex">
            @foreach($maturityLevels->sortBy('level') as $lvl)
                @php
                    $colors = $levelColors[$lvl->level] ?? ['bg' => '#94A3B8', 'light' => '#F1F5F9', 'text' => '#475569'];
                    $width = $maxScore > 0 ? (($lvl->max_score - $lvl->min_score) / $maxScore) * 100 : 0;
                @endphp
                <div class="flex items-center justify-center text-xs font-bold transition hover:opacity-80"
                     style="width: {{ $width }}%; background: {{ $colors['bg'] }}; color: white;"
                     title="Level {{ $lvl->level }}: {{ $lvl->name }} ({{ $lvl->min_score }}–{{ $lvl->max_score }})">
                    L{{ $lvl->level }}
                </div>
            @endforeach
        </div>
        <div class="flex mt-2 gap-4 flex-wrap">
            @foreach($maturityLevels->sortBy('level') as $lvl)
                @php $colors = $levelColors[$lvl->level] ?? ['bg' => '#94A3B8', 'light' => '#F1F5F9', 'text' => '#475569']; @endphp
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: {{ $colors['bg'] }}"></span>
                    <span class="text-xs text-slate-600">Level {{ $lvl->level }}: {{ $lvl->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(session('success'))
        <div class="mb-4 flex-shrink-0 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="flex-1 min-h-0 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm flex flex-col">
        <div class="flex-1 min-h-0 overflow-y-auto">
            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Level</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Name</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Score Range</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Visual Range</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Description</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($maturityLevels->sortBy('level') as $level)
                        @php
                            $colors = $levelColors[$level->level] ?? ['bg' => '#94A3B8', 'light' => '#F1F5F9', 'text' => '#475569'];
                            $barWidth = $maxScore > 0 ? ($level->max_score / $maxScore) * 100 : 0;
                            $barStart = $maxScore > 0 ? ($level->min_score / $maxScore) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-slate-50 transition group">

                            {{-- Level badge --}}
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-sm font-bold"
                                      style="background: {{ $colors['light'] }}; color: {{ $colors['text'] }}">
                                    {{ $level->level }}
                                </span>
                            </td>

                            {{-- Name --}}
                            <td class="px-6 py-5">
                                <p class="font-bold text-slate-900">{{ $level->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5 font-mono">
                                    {{ $level->min_score }} – {{ $level->max_score }}
                                </p>
                            </td>

                            {{-- Score range numbers --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold"
                                          style="background: {{ $colors['light'] }}; color: {{ $colors['text'] }}">
                                        {{ $level->min_score }}
                                    </span>
                                    <span class="text-slate-300">→</span>
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold"
                                          style="background: {{ $colors['light'] }}; color: {{ $colors['text'] }}">
                                        {{ $level->max_score }}
                                    </span>
                                </div>
                            </td>

                            {{-- Visual bar --}}
                            <td class="px-6 py-5 w-48">
                                <div class="relative h-2.5 bg-slate-100 rounded-full overflow-hidden w-40">
                                    <div class="absolute top-0 h-full rounded-full transition-all"
                                         style="left: {{ $barStart }}%; width: {{ $barWidth - $barStart }}%; background: {{ $colors['bg'] }}">
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">{{ round($barWidth - $barStart) }}% of range</p>
                            </td>

                            {{-- Description --}}
                            <td class="px-6 py-5 text-slate-600 max-w-sm">
                                <p class="text-sm leading-relaxed">{{ $level->description }}</p>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('maturity-levels.edit', $level) }}"
                                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-yellow-700 hover:bg-yellow-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('maturity-levels.destroy', $level) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete Level {{ $level->level }}: {{ $level->name }}? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <p class="text-lg font-semibold text-slate-700">No maturity levels yet</p>
                                <p class="mt-2 text-sm text-slate-500">Define the levels that YARA will use to score organizations.</p>
                                <a href="{{ route('maturity-levels.create') }}"
                                   class="mt-5 inline-flex rounded-xl bg-slate-950 px-5 py-3 font-semibold text-white hover:bg-slate-800 transition">
                                    Create First Level
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
