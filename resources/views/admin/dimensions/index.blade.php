@extends('layouts.admin')

@section('content')

<div class="flex flex-col min-h-0" style="height: 100%;">

    {{-- HEADER --}}
    <div class="flex items-start justify-between mb-6 flex-shrink-0">
        <div>
            <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
                Admin Management
            </p>
            <div class="flex items-center gap-4 mt-1">
                <h1 class="text-4xl font-bold text-slate-950">Dimensions</h1>
                <a href="{{ route('dimensions.create') }}"
                   class="flex items-center gap-2 px-4 h-10 rounded-full bg-yellow-400 text-slate-950 text-sm font-semibold hover:scale-105 transition">
                    + Add Dimension
                </a>
            </div>
            <p class="mt-2 text-slate-500">
                Manage the YARA assessment dimensions used in the questionnaire.
            </p>
        </div>

        {{-- Total weight indicator --}}
        @php
            $totalWeight = $dimensions->sum('weight');
            $weightOk = abs($totalWeight - 100) < 0.1;
        @endphp
        <div class="text-right bg-white rounded-2xl border shadow-sm px-5 py-4 {{ $weightOk ? 'border-green-200' : 'border-orange-300' }}">
            <p class="text-xs font-semibold uppercase tracking-wide {{ $weightOk ? 'text-green-600' : 'text-orange-500' }}">
                Total Weight
            </p>
            <p class="text-3xl font-bold mt-1 {{ $weightOk ? 'text-green-700' : 'text-orange-600' }}">
                {{ number_format($totalWeight, 2) }}%
            </p>
            <p class="text-xs mt-0.5 {{ $weightOk ? 'text-green-500' : 'text-orange-400' }}">
                {{ $weightOk ? '✓ Adds up to 100%' : '⚠ Should total 100%' }}
            </p>
        </div>
    </div>

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
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 w-16">Code</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Dimension</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Description</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 w-32">Weight</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 w-36 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($dimensions as $dimension)
                        <tr class="hover:bg-slate-50 transition group">

                            {{-- Code --}}
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center justify-center rounded-xl bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1.5">
                                    {{ $dimension->code ?? '—' }}
                                </span>
                            </td>

                            {{-- Name --}}
                            <td class="px-6 py-5 font-bold text-slate-900">
                                {{ $dimension->name }}
                            </td>

                            {{-- Description --}}
                            <td class="px-6 py-5 text-slate-700 max-w-md text-sm leading-relaxed">
                                {{ $dimension->description }}
                            </td>

                            {{-- Weight with bar --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full"
                                             style="width: {{ min($dimension->weight, 100) }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-800 whitespace-nowrap">
                                        {{ $dimension->weight }}%
                                    </span>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('dimensions.edit', $dimension) }}"
                                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-yellow-700 hover:bg-yellow-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('dimensions.destroy', $dimension) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete {{ $dimension->name }}? This cannot be undone.');">
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
                            <td colspan="5" class="px-6 py-16 text-center">
                                <p class="text-lg font-semibold text-slate-700">No dimensions yet</p>
                                <p class="mt-2 text-sm text-slate-500">Add the 11 YARA dimensions to get started.</p>
                                <a href="{{ route('dimensions.create') }}"
                                   class="mt-5 inline-flex rounded-xl bg-slate-950 px-5 py-3 font-semibold text-white hover:bg-slate-800 transition">
                                    Create First Dimension
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
