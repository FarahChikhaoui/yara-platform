@extends('layouts.admin')

@section('content')

<div class="flex flex-col min-h-0" style="height: 100%;">

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-4 flex flex-shrink-0 items-start justify-between gap-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                Consulting Knowledge Base
            </p>
            <div class="mt-1 flex items-center gap-4">
                <h1 class="text-4xl font-bold text-slate-950">
                    Recommendation Rules
                </h1>
                <a href="{{ route('recommendation-rules.create') }}"
                   class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-400 text-2xl font-medium text-slate-950 transition hover:scale-105">
                    +
                </a>
            </div>
            <p class="mt-2 text-slate-500">
                Manage the consulting knowledge and recommendation rules that power YARA's personalized AI transformation roadmaps.
            </p>
        </div>
    </div>

    {{-- ── STATS CARDS (clickable filters) ───────────────────────────── --}}
    <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-4 flex-shrink-0">

        <button onclick="filterByStatus('all')"
                class="stat-card text-left rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-400 hover:shadow-md"
                data-filter="all">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Rules</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $rules->count() }}</p>
            <p class="mt-1 text-xs text-slate-400">Total recommendation rules</p>
        </button>

        <button onclick="filterByStatus('active')"
                class="stat-card text-left rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-400 hover:shadow-md"
                data-filter="active">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active Rules</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $rules->where('is_active', true)->count() }}</p>
            <p class="mt-1 text-xs text-slate-400">Click to filter active</p>
        </button>

        <button onclick="filterByStatus('inactive')"
                class="stat-card text-left rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-red-300 hover:shadow-md"
                data-filter="inactive">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Dimensions Covered</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $rules->pluck('dimension_id')->unique()->count() }}</p>
            <p class="mt-1 text-xs text-slate-400">Click to filter inactive</p>
        </button>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Categories</p>
            <p class="mt-2 text-3xl font-bold text-purple-600">{{ $rules->pluck('category')->filter()->unique()->count() }}</p>
            <p class="mt-1 text-xs text-slate-400">Unique categories</p>
        </div>

    </div>

    {{-- ── SUCCESS FLASH ───────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="mb-4 flex-shrink-0 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- ── SEARCH + FILTER BAR ─────────────────────────────────────────── --}}
    <div class="mb-3 flex flex-shrink-0 flex-wrap items-center gap-3">

        {{-- Search input --}}
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input id="searchInput"
                   type="text"
                   placeholder="Search by title, dimension, category..."
                   class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-yellow-400 focus:outline-none focus:ring-1 focus:ring-yellow-400"
                   oninput="applyFilters()">
        </div>

        {{-- Impact filter --}}
        <select id="impactFilter"
                onchange="applyFilters()"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-yellow-400 focus:outline-none focus:ring-1 focus:ring-yellow-400">
            <option value="">All Impacts</option>
            <option value="Critical">Critical</option>
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
        </select>

        {{-- Category filter --}}
        <select id="categoryFilter"
                onchange="applyFilters()"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-yellow-400 focus:outline-none focus:ring-1 focus:ring-yellow-400">
            <option value="">All Categories</option>
            <option value="Strategy">Strategy</option>
            <option value="Governance">Governance</option>
            <option value="Technology">Technology</option>
            <option value="Data">Data</option>
            <option value="Risk">Risk</option>
            <option value="People">People</option>
            <option value="Adoption">Adoption</option>
        </select>

        {{-- Clear filters --}}
        <button onclick="clearFilters()"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
            Clear
        </button>

        {{-- Bulk delete --}}
        <button id="bulkDeleteBtn"
                onclick="bulkDelete()"
                class="hidden rounded-xl bg-red-50 border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100">
            Delete selected (<span id="selectedCount">0</span>)
        </button>

    </div>

    {{-- ── TABLE ───────────────────────────────────────────────────────── --}}
    <div class="flex-1 min-h-0 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm flex flex-col">

        <div class="flex-1 min-h-0 overflow-x-auto overflow-y-auto">

            <table id="rulesTable" class="w-full min-w-[1300px] text-left">

                <thead class="sticky top-0 z-10 border-b border-slate-200 bg-slate-50">
                    <tr>

                        {{-- Checkbox all --}}
                        <th class="px-4 py-4 w-10">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"
                                   class="rounded border-slate-300 text-yellow-500 focus:ring-yellow-400">
                        </th>

                        {{-- Row number --}}
                        <th class="px-3 py-4 text-xs font-bold uppercase text-slate-400 w-10">#</th>

                        {{-- Sticky dimension column --}}
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 sticky left-0 bg-slate-50 z-20 cursor-pointer select-none"
                            onclick="sortTable('dimension')">
                            <span class="flex items-center gap-1">
                                Dimension
                                <svg id="sort-dimension" class="opacity-30" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                            </span>
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500">Trigger</th>

                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500">Consulting Action</th>

                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 cursor-pointer select-none"
                            onclick="sortTable('impact')">
                            <span class="flex items-center gap-1">
                                Impact
                                <svg id="sort-impact" class="opacity-30" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                            </span>
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500">Effort</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500">Timeline</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500">Investment</th>

                        <th class="px-5 py-4 text-xs font-bold uppercase text-slate-500 cursor-pointer select-none"
                            onclick="sortTable('status')">
                            <span class="flex items-center gap-1">
                                Status
                                <svg id="sort-status" class="opacity-30" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                            </span>
                        </th>

                        <th class="px-5 py-4 text-right text-xs font-bold uppercase text-slate-500">Actions</th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100" id="tableBody">

                    @php
                        $categoryClasses = [
                            'Strategy'   => 'bg-blue-100 text-blue-700',
                            'Governance' => 'bg-emerald-100 text-emerald-700',
                            'Technology' => 'bg-violet-100 text-violet-700',
                            'Data'       => 'bg-orange-100 text-orange-700',
                            'Risk'       => 'bg-red-100 text-red-700',
                            'People'     => 'bg-cyan-100 text-cyan-700',
                            'Adoption'   => 'bg-yellow-100 text-yellow-700',
                        ];
                        $impactClasses = [
                            'Low'      => 'bg-slate-100 text-slate-700',
                            'Medium'   => 'bg-yellow-100 text-yellow-700',
                            'High'     => 'bg-orange-100 text-orange-700',
                            'Critical' => 'bg-red-100 text-red-700',
                        ];
                        $impactOrder = ['Critical' => 4, 'High' => 3, 'Medium' => 2, 'Low' => 1];
                        $rowNum = 0;
                    @endphp

                    @forelse($rules as $rule)
                        @php $rowNum++ @endphp

                        <tr class="rule-row transition hover:bg-slate-50 cursor-pointer"
                            data-id="{{ $rule->id }}"
                            data-dimension="{{ strtolower($rule->dimension->name ?? '') }}"
                            data-title="{{ strtolower($rule->action_title ?? '') }}"
                            data-category="{{ $rule->category ?? '' }}"
                            data-impact="{{ $rule->business_impact ?? '' }}"
                            data-impact-order="{{ $impactOrder[$rule->business_impact] ?? 0 }}"
                            data-status="{{ $rule->is_active ? 'active' : 'inactive' }}"
                            onclick="openDrawer({{ $rule->id }})"
                        >

                            {{-- Checkbox --}}
                            <td class="px-4 py-5 align-top" onclick="event.stopPropagation()">
                                <input type="checkbox"
                                       class="row-checkbox rounded border-slate-300 text-yellow-500 focus:ring-yellow-400"
                                       value="{{ $rule->id }}"
                                       onchange="updateBulkBar()">
                            </td>

                            {{-- Row number --}}
                            <td class="px-3 py-5 align-top text-xs text-slate-400 font-mono">
                                {{ $rowNum }}
                            </td>

                            {{-- Dimension (sticky) --}}
                            <td class="px-5 py-5 align-top sticky left-0 bg-white z-10 group-hover:bg-slate-50">
                                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-800">
                                    {{ $rule->dimension->code ?? 'N/A' }}
                                </span>
                                <p class="mt-2 max-w-[160px] text-sm font-semibold text-slate-800 leading-snug">
                                    {{ $rule->dimension->name ?? 'Unknown' }}
                                </p>
                                @if($rule->category)
                                    <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $categoryClasses[$rule->category] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $rule->category }}
                                    </span>
                                @endif
                            </td>

                            {{-- Trigger --}}
                            <td class="px-5 py-5 align-top">
                                @if($rule->question)
                                    <p class="max-w-[180px] text-sm font-medium text-slate-800 leading-snug">
                                        {{ \Illuminate\Support\Str::limit($rule->question->question_text, 80) }}
                                    </p>
                                @else
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">
                                        Dimension-level rule
                                    </span>
                                @endif
                                <p class="mt-2 text-xs text-slate-500">
                                    Triggered at score ≤ {{ $rule->max_answer_score }}
                                </p>
                            </td>

                            {{-- Consulting Action --}}
                            <td class="px-5 py-5 align-top">
                                <p class="max-w-xs font-semibold text-slate-900 leading-snug">
                                    {{ $rule->action_title }}
                                </p>
                                <p class="mt-1 max-w-xs text-sm text-slate-500 leading-relaxed">
                                    {{ \Illuminate\Support\Str::limit($rule->action_description, 100) }}
                                </p>
                                @if($rule->standard_reference)
                                    <p class="mt-2 text-xs font-medium text-slate-400">
                                        {{ $rule->standard_reference }}
                                    </p>
                                @endif
                            </td>

                            {{-- Impact --}}
                            <td class="px-5 py-5 align-top">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $impactClasses[$rule->business_impact] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $rule->business_impact }}
                                </span>
                            </td>

                            {{-- Effort --}}
                            <td class="px-5 py-5 align-top text-sm font-semibold text-slate-700">
                                {{ $rule->effort }}
                            </td>

                            {{-- Timeline --}}
                            <td class="px-5 py-5 align-top text-sm text-slate-600">
                                @if($rule->timeline_min_months !== null || $rule->timeline_max_months !== null)
                                    {{ $rule->timeline_min_months ?? 0 }} – {{ $rule->timeline_max_months ?? $rule->timeline_min_months }} months
                                @else
                                    —
                                @endif
                            </td>

                            {{-- Investment --}}
                            <td class="px-5 py-5 align-top text-sm text-slate-600 whitespace-nowrap">
                                @if($rule->investment_min !== null || $rule->investment_max !== null)
                                    @php
                                        $min = (float)($rule->investment_min ?? 0);
                                        $max = (float)($rule->investment_max ?? $min);
                                        $fmt = fn($n) => $n >= 1000 ? '$'.number_format($n/1000, 0).'K' : '$'.number_format($n, 0);
                                    @endphp
                                    {{ $fmt($min) }}@if($rule->investment_max !== null) – {{ $fmt($max) }}@endif
                                @else
                                    —
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-5 align-top">
                                @if($rule->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">Inactive</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-5 align-top" onclick="event.stopPropagation()">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('recommendation-rules.edit', $rule) }}"
                                       class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-yellow-600 transition hover:bg-yellow-50"
                                       title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('recommendation-rules.destroy', $rule) }}" method="POST"
                                          onsubmit="return confirm('Delete this recommendation rule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-red-600 transition hover:bg-red-50"
                                                title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-16 text-center">
                                <p class="text-lg font-semibold text-slate-700">No recommendation rules yet</p>
                                <p class="mt-2 text-sm text-slate-500">Create your first consulting action to start building the YARA knowledge base.</p>
                                <a href="{{ route('recommendation-rules.create') }}"
                                   class="mt-5 inline-flex rounded-xl bg-slate-950 px-5 py-3 font-semibold text-white transition hover:bg-slate-800">
                                    Create First Rule
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- No results message --}}
        <div id="noResults" class="hidden py-16 text-center flex-shrink-0">
            <p class="text-lg font-semibold text-slate-700">No rules match your filters</p>
            <p class="mt-1 text-sm text-slate-400">Try adjusting your search or clearing the filters.</p>
        </div>

    </div>

</div>

{{-- ── DETAIL DRAWER ───────────────────────────────────────────────────── --}}
<div id="drawer"
     class="fixed inset-y-0 right-0 z-50 flex w-[480px] flex-col bg-white shadow-2xl border-l border-slate-200 translate-x-full transition-transform duration-300 ease-in-out">

    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
        <h2 class="text-lg font-bold text-slate-900" id="drawerTitle">Rule Details</h2>
        <button onclick="closeDrawer()"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto px-6 py-5 space-y-5" id="drawerContent">
        <p class="text-slate-400 text-sm">Loading...</p>
    </div>

    <div class="border-t border-slate-200 px-6 py-4 flex gap-3" id="drawerActions"></div>

</div>

{{-- Drawer backdrop --}}
<div id="drawerBackdrop"
     class="fixed inset-0 z-40 bg-black/20 hidden"
     onclick="closeDrawer()"></div>

{{-- ── RULE DATA FOR DRAWER (JSON) ────────────────────────────────────── --}}
<script>
const RULES_DATA = {
    @foreach($rules as $rule)
    {{ $rule->id }}: {
        id: {{ $rule->id }},
        dimension_code: "{{ $rule->dimension->code ?? 'N/A' }}",
        dimension_name: "{{ addslashes($rule->dimension->name ?? 'Unknown') }}",
        category: "{{ $rule->category ?? '' }}",
        trigger_type: "{{ $rule->question ? 'question' : 'dimension' }}",
        trigger_text: "{{ $rule->question ? addslashes($rule->question->question_text) : 'Dimension-level rule' }}",
        max_score: {{ $rule->max_answer_score ?? 'null' }},
        action_title: "{{ addslashes($rule->action_title ?? '') }}",
        action_description: "{{ addslashes($rule->action_description ?? '') }}",
        expected_outcomes: "{{ addslashes($rule->expected_outcomes ?? '') }}",
        standard_reference: "{{ addslashes($rule->standard_reference ?? '') }}",
        business_rationale: "{{ addslashes($rule->business_rationale ?? '') }}",
        business_impact: "{{ $rule->business_impact ?? '' }}",
        effort: "{{ $rule->effort ?? '' }}",
        timeline: "{{ ($rule->timeline_min_months ?? 0) . ' – ' . ($rule->timeline_max_months ?? $rule->timeline_min_months ?? 0) }} months",
        investment: "{{ $rule->investment_min !== null ? ('$'.number_format((float)($rule->investment_min)/1000,0).'K') : '' }}{{ $rule->investment_max !== null ? (' – $'.number_format((float)$rule->investment_max/1000,0).'K') : '' }}",
        currency: "{{ $rule->currency ?? 'USD' }}",
        is_active: {{ $rule->is_active ? 'true' : 'false' }},
        edit_url: "{{ route('recommendation-rules.edit', $rule) }}",
    },
    @endforeach
};
</script>

{{-- ── JAVASCRIPT ──────────────────────────────────────────────────────── --}}
<script>
// ── FILTER STATE ──────────────────────────────────────────────────────────
let currentStatusFilter = 'all';
let sortCol = null;
let sortDir = 1;

// ── APPLY ALL FILTERS + SEARCH ────────────────────────────────────────────
function applyFilters() {
    const search   = document.getElementById('searchInput').value.toLowerCase();
    const impact   = document.getElementById('impactFilter').value;
    const category = document.getElementById('categoryFilter').value;
    const rows     = document.querySelectorAll('.rule-row');
    let visible    = 0;

    rows.forEach(row => {
        const matchSearch   = !search   || row.dataset.dimension.includes(search) || row.dataset.title.includes(search) || row.dataset.category.toLowerCase().includes(search);
        const matchImpact   = !impact   || row.dataset.impact === impact;
        const matchCategory = !category || row.dataset.category === category;
        const matchStatus   = currentStatusFilter === 'all' || row.dataset.status === currentStatusFilter;

        const show = matchSearch && matchImpact && matchCategory && matchStatus;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('noResults').classList.toggle('hidden', visible > 0);
    updateStatCardHighlight();
}

// ── STATUS FILTER VIA STAT CARDS ──────────────────────────────────────────
function filterByStatus(status) {
    currentStatusFilter = status;
    applyFilters();
}

function updateStatCardHighlight() {
    document.querySelectorAll('.stat-card').forEach(btn => {
        const active = btn.dataset.filter === currentStatusFilter;
        btn.classList.toggle('ring-2', active);
        btn.classList.toggle('ring-yellow-400', active);
    });
}

// ── CLEAR ─────────────────────────────────────────────────────────────────
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('impactFilter').value = '';
    document.getElementById('categoryFilter').value = '';
    currentStatusFilter = 'all';
    applyFilters();
}

// ── SORTING ───────────────────────────────────────────────────────────────
function sortTable(col) {
    if (sortCol === col) {
        sortDir *= -1;
    } else {
        sortCol = col;
        sortDir = 1;
    }

    // Reset all sort icons
    document.querySelectorAll('[id^="sort-"]').forEach(el => el.classList.add('opacity-30'));
    const icon = document.getElementById('sort-' + col);
    if (icon) icon.classList.remove('opacity-30');

    const tbody  = document.getElementById('tableBody');
    const rows   = Array.from(tbody.querySelectorAll('.rule-row'));

    rows.sort((a, b) => {
        let va, vb;
        if (col === 'dimension') { va = a.dataset.dimension; vb = b.dataset.dimension; }
        if (col === 'impact')    { va = parseInt(a.dataset.impactOrder); vb = parseInt(b.dataset.impactOrder); return sortDir * (vb - va); }
        if (col === 'status')    { va = a.dataset.status; vb = b.dataset.status; }
        return sortDir * va.localeCompare(vb);
    });

    rows.forEach(r => tbody.appendChild(r));
}

// ── BULK SELECT ───────────────────────────────────────────────────────────
function toggleSelectAll(master) {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        if (cb.closest('tr').style.display !== 'none') {
            cb.checked = master.checked;
        }
    });
    updateBulkBar();
}

function updateBulkBar() {
    const checked = document.querySelectorAll('.row-checkbox:checked').length;
    const btn = document.getElementById('bulkDeleteBtn');
    document.getElementById('selectedCount').textContent = checked;
    btn.classList.toggle('hidden', checked === 0);
    document.getElementById('selectAll').indeterminate =
        checked > 0 && checked < document.querySelectorAll('.row-checkbox').length;
}

function bulkDelete() {
    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    if (!ids.length) return;
    if (!confirm(`Delete ${ids.length} selected rule(s)? This cannot be undone.`)) return;

    // Create and submit a form for each — or do them sequentially
    // Simple approach: reload after all deletions
    ids.forEach((id, idx) => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/recommendation-rules/${id}`;
        form.innerHTML = `
            @csrf
            <input name="_method" value="DELETE">
        `;
        // Only submit last one normally, others via fetch
        if (idx < ids.length - 1) {
            fetch(`/admin/recommendation-rules/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/x-www-form-urlencoded' },
                body: '_method=DELETE',
            });
        } else {
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// ── DETAIL DRAWER ─────────────────────────────────────────────────────────
function openDrawer(id) {
    const rule = RULES_DATA[id];
    if (!rule) return;

    const impactColors = {
        Critical: 'bg-red-100 text-red-700',
        High: 'bg-orange-100 text-orange-700',
        Medium: 'bg-yellow-100 text-yellow-700',
        Low: 'bg-slate-100 text-slate-700',
    };

    document.getElementById('drawerTitle').textContent = rule.action_title || 'Rule Details';

    document.getElementById('drawerContent').innerHTML = `
        <div class="flex items-center gap-2 flex-wrap">
            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-800">${rule.dimension_code}</span>
            <span class="text-sm font-semibold text-slate-700">${rule.dimension_name}</span>
            ${rule.category ? `<span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">${rule.category}</span>` : ''}
            <span class="rounded-full ${rule.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} px-3 py-1 text-xs font-bold ml-auto">${rule.is_active ? 'Active' : 'Inactive'}</span>
        </div>

        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Trigger</p>
            <p class="text-sm text-slate-700">${rule.trigger_text}</p>
            <p class="mt-1 text-xs text-slate-400">Triggered at score ≤ ${rule.max_score}</p>
        </div>

        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Description</p>
            <p class="text-sm text-slate-700 leading-relaxed">${rule.action_description}</p>
        </div>

        ${rule.expected_outcomes ? `
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
            <p class="text-xs font-bold uppercase tracking-wide text-emerald-700 mb-2">Expected Business Outcomes</p>
            <p class="text-sm text-slate-700 leading-relaxed">${rule.expected_outcomes}</p>
        </div>` : ''}

        ${rule.business_rationale ? `
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Business Rationale</p>
            <p class="text-sm text-slate-700 leading-relaxed">${rule.business_rationale}</p>
        </div>` : ''}

        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Impact</p>
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold ${impactColors[rule.business_impact] || 'bg-slate-100 text-slate-700'}">${rule.business_impact}</span>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Effort</p>
                <p class="text-sm font-semibold text-slate-700">${rule.effort}</p>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Timeline</p>
                <p class="text-sm font-semibold text-slate-700">${rule.timeline}</p>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Investment</p>
                <p class="text-sm font-semibold text-slate-700">${rule.investment || '—'}</p>
            </div>
        </div>

        ${rule.standard_reference ? `
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Standard Reference</p>
            <p class="text-sm text-slate-500">${rule.standard_reference}</p>
        </div>` : ''}
    `;

    document.getElementById('drawerActions').innerHTML = `
        <a href="${rule.edit_url}"
           class="flex-1 text-center rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            Edit Rule
        </a>
        <button onclick="closeDrawer()"
                class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Close
        </button>
    `;

    document.getElementById('drawer').classList.remove('translate-x-full');
    document.getElementById('drawerBackdrop').classList.remove('hidden');
}

function closeDrawer() {
    document.getElementById('drawer').classList.add('translate-x-full');
    document.getElementById('drawerBackdrop').classList.add('hidden');
}

// Close drawer on Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDrawer();
});
</script>

@endsection