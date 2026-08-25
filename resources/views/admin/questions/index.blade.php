@extends('layouts.admin')

@section('content')

<div class="flex flex-col min-h-0" style="height: 100%;">

    {{-- HEADER --}}
    <div class="flex items-start justify-between mb-5 flex-shrink-0">
        <div>
            <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
                Admin Management
            </p>
            <div class="flex items-center gap-4 mt-1">
                <h1 class="text-4xl font-bold text-slate-950">Questions</h1>
                <a href="{{ route('questions.create') }}"
                   class="flex items-center gap-2 px-4 h-10 rounded-full bg-yellow-400 text-slate-950 text-sm font-semibold hover:scale-105 transition">
                    + Add Question
                </a>
            </div>
            <p class="mt-2 text-slate-500">
                Manage YARA assessment questions and their answer options.
            </p>
        </div>

        {{-- Stats --}}
        <div class="flex gap-3">
            <div class="text-center bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $questions->count() }}</p>
            </div>
            <div class="text-center bg-white rounded-2xl border border-green-200 shadow-sm px-5 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-green-500">Active</p>
                <p class="text-2xl font-bold text-green-700 mt-1">{{ $questions->where('is_active', true)->count() }}</p>
            </div>
            <div class="text-center bg-white rounded-2xl border border-red-100 shadow-sm px-5 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-400">No Options</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $questions->filter(fn($q) => !$q->answerOptions || $q->answerOptions->count() === 0)->count() }}</p>
            </div>
            <div class="text-center bg-white rounded-2xl border border-yellow-300 shadow-sm px-5 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-yellow-600">Pulse Check</p>
                <p class="text-2xl font-bold text-yellow-700 mt-1" id="pulseCount">
                    {{ $questions->where('include_in_pulse', true)->count() }}
                </p>
                <p class="text-[10px] text-slate-400 mt-0.5">target 15–20</p>
            </div>
        </div>
    </div>

    {{-- DIMENSION FILTER + PULSE FILTER --}}
    <div class="mb-4 flex-shrink-0 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            <button onclick="filterDimension('all')" data-dim="all"
                    class="dim-btn px-4 py-2 rounded-full text-xs font-bold border border-yellow-400 bg-yellow-400 text-slate-950 transition">
                All
            </button>
            @foreach($questions->pluck('dimension')->filter()->unique('id')->sortBy('code') as $dim)
                <button onclick="filterDimension('{{ $dim->code }}')" data-dim="{{ $dim->code }}"
                        class="dim-btn px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-white text-slate-600 hover:border-yellow-400 hover:bg-yellow-50 transition">
                    {{ $dim->code }} — {{ $dim->name }}
                </button>
            @endforeach
        </div>

        <button onclick="togglePulseFilter()" id="pulseFilterBtn"
                class="flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold border border-yellow-300 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            Show Pulse Check Only
        </button>
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
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 w-8">#</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 w-24">Dimension</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500">Question & Answer Options</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 w-16">Order</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 w-16">Weight</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 w-20">Status</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 w-24 text-center">Pulse Check</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 text-right w-28">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100" id="questionsBody">

                    @forelse($questions as $i => $question)
                        <tr class="question-row hover:bg-slate-50 transition align-top"
                            data-dim="{{ $question->dimension->code ?? '' }}"
                            data-pulse="{{ $question->include_in_pulse ? '1' : '0' }}"
                            data-question-id="{{ $question->id }}">

                            {{-- Row number --}}
                            <td class="px-5 py-5 text-xs text-slate-400 font-mono align-top">
                                {{ $i + 1 }}
                            </td>

                            {{-- Dimension --}}
                            <td class="px-5 py-5 align-top">
                                <span class="inline-flex items-center justify-center rounded-xl bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1.5 whitespace-nowrap">
                                    {{ $question->dimension->code ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Question + answer options --}}
                            <td class="px-5 py-5 max-w-xl align-top">
                                <p class="font-semibold text-slate-900 leading-snug">
                                    {{ $question->question_text }}
                                </p>

                                @if($question->answerOptions && $question->answerOptions->count())
                                    <div class="mt-3 space-y-1.5">
                                        @foreach($question->answerOptions as $option)
                                            <div class="flex items-start gap-2 text-sm">
                                                <span class="font-bold text-slate-500 flex-shrink-0 w-5">
                                                    {{ chr(64 + $loop->iteration) }}.
                                                </span>
                                                <span class="text-slate-700 flex-1">{{ $option->label }}</span>
                                                <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full whitespace-nowrap flex-shrink-0">
                                                    Score {{ $option->score }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-2 inline-flex items-center gap-1.5 text-xs text-red-500 bg-red-50 border border-red-100 rounded-lg px-3 py-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        No answer options — add them when editing
                                    </div>
                                @endif
                            </td>

                            {{-- Order --}}
                            <td class="px-5 py-5 align-top">
                                <span class="text-sm font-mono text-slate-600">{{ $question->order }}</span>
                            </td>

                            {{-- Weight --}}
                            <td class="px-5 py-5 align-top">
                                <span class="text-sm font-semibold text-slate-700">{{ $question->weight }}</span>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-5 align-top">
                                @if($question->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 text-green-700 text-xs font-bold px-3 py-1">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 text-red-700 text-xs font-bold px-3 py-1">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Pulse Check toggle --}}
                            <td class="px-5 py-5 align-top text-center">
                                <button type="button"
                                        onclick="togglePulse(this)"
                                        data-toggle-url="{{ route('questions.toggle-pulse', $question) }}"
                                        class="pulse-toggle inline-flex h-6 w-11 items-center rounded-full transition {{ $question->include_in_pulse ? 'bg-yellow-400' : 'bg-slate-200' }}"
                                        aria-pressed="{{ $question->include_in_pulse ? 'true' : 'false' }}">
                                    <span class="pulse-dot inline-block h-4 w-4 transform rounded-full bg-white shadow transition {{ $question->include_in_pulse ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-5 align-top">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('questions.edit', $question) }}"
                                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-yellow-700 hover:bg-yellow-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('questions.destroy', $question) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this question and all its answer options?');">
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
                            <td colspan="8" class="px-6 py-16 text-center">
                                <p class="text-lg font-semibold text-slate-700">No questions yet</p>
                                <p class="mt-2 text-sm text-slate-500">Add the 110 YARA assessment questions to get started.</p>
                                <a href="{{ route('questions.create') }}"
                                   class="mt-5 inline-flex rounded-xl bg-slate-950 px-5 py-3 font-semibold text-white hover:bg-slate-800 transition">
                                    Create First Question
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            {{-- No results --}}
            <div id="noResults" class="hidden py-16 text-center">
                <p class="text-lg font-semibold text-slate-700">No questions for this dimension</p>
                <p class="mt-1 text-sm text-slate-400">Select a different dimension or show all.</p>
            </div>

        </div>
    </div>

</div>

<script>
let pulseFilterActive = false;

function filterDimension(dim) {
    document.querySelectorAll('.dim-btn').forEach(btn => {
        const active = btn.dataset.dim === dim;
        btn.className = active
            ? 'dim-btn px-4 py-2 rounded-full text-xs font-bold border border-yellow-400 bg-yellow-400 text-slate-950 transition'
            : 'dim-btn px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-white text-slate-600 hover:border-yellow-400 hover:bg-yellow-50 transition';
    });
    applyFilters(dim);
}

function togglePulseFilter() {
    pulseFilterActive = !pulseFilterActive;

    const btn = document.getElementById('pulseFilterBtn');
    btn.textContent = pulseFilterActive ? 'Show All Questions' : 'Show Pulse Check Only';
    btn.className = pulseFilterActive
        ? 'flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold border border-yellow-400 bg-yellow-400 text-slate-950 transition'
        : 'flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold border border-yellow-300 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 transition';

    const currentDim = document.querySelector('.dim-btn.bg-yellow-400')?.dataset.dim || 'all';
    applyFilters(currentDim);
}

function applyFilters(dim) {
    const rows = document.querySelectorAll('.question-row');
    let visible = 0;
    rows.forEach(row => {
        const dimMatch = dim === 'all' || row.dataset.dim === dim;
        const pulseMatch = !pulseFilterActive || row.dataset.pulse === '1';
        const show = dimMatch && pulseMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('noResults').classList.toggle('hidden', visible > 0);
}

function togglePulse(button) {
    const url = button.dataset.toggleUrl;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

    if (!csrfMeta) {
        console.error('Missing <meta name="csrf-token"> in layout head.');
        alert('Missing CSRF token meta tag — check your layout head.');
        return;
    }

    button.disabled = true;

    fetch(url, {
        method: 'PATCH',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfMeta.getAttribute('content'),
        },
    })
    .then(async response => {
        if (!response.ok) {
            const body = await response.text();
            throw new Error(`HTTP ${response.status}: ${body}`);
        }
        return response.json();
    })
    .then(data => {
        const isOn = data.include_in_pulse;
        const dot = button.querySelector('.pulse-dot');
        const row = button.closest('.question-row');

        button.classList.toggle('bg-yellow-400', isOn);
        button.classList.toggle('bg-slate-200', !isOn);
        button.setAttribute('aria-pressed', isOn ? 'true' : 'false');
        dot.classList.toggle('translate-x-6', isOn);
        dot.classList.toggle('translate-x-1', !isOn);
        row.dataset.pulse = isOn ? '1' : '0';

        const countEl = document.getElementById('pulseCount');
        const currentCount = Number(countEl.textContent.trim());
        countEl.textContent = currentCount + (isOn ? 1 : -1);

        if (pulseFilterActive) {
            const currentDim = document.querySelector('.dim-btn.bg-yellow-400')?.dataset.dim || 'all';
            applyFilters(currentDim);
        }
    })
    .catch(error => {
        console.error('Pulse toggle failed:', error);
        alert('Could not update Pulse Check status. Check the console for details.');
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>

@endsection