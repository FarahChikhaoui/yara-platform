@extends('layouts.admin')

@section('content')

<div class="relative space-y-8">

    {{-- Ambient background accent --}}
    <div class="pointer-events-none fixed -top-24 left-1/2 -z-10 h-72 w-[36rem] -translate-x-1/2 rounded-full bg-yellow-300/10 blur-3xl"></div>

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

        <div>
            <p class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-yellow-600">
                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                Operations
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                Transformation Requests
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Assign consultants and track paid Transformation engagements
                from submission through final roadmap delivery.
            </p>
        </div>

    </div>


   
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-26px_rgba(15,23,42,0.25)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_20px_36px_-24px_rgba(15,23,42,0.3)]">

            <div class="flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    Unassigned
                </p>

                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition group-hover:bg-red-500 group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.8 2.5 17.3A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.7L13.7 3.8a2 2 0 0 0-3.4 0Z"/></svg>
                </div>
            </div>

            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                {{ $unassignedCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Waiting for consultant assignment
            </p>
        </div>


        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-26px_rgba(15,23,42,0.25)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_20px_36px_-24px_rgba(15,23,42,0.3)]">

            <div class="flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    Assigned
                </p>

                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-slate-950 group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>

            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                {{ $assignedCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Assigned but review not started
            </p>
        </div>


        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-26px_rgba(15,23,42,0.25)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_20px_36px_-24px_rgba(15,23,42,0.3)]">

            <div class="flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    In Review
                </p>

                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-500 transition group-hover:bg-blue-500 group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
            </div>

            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                {{ $inReviewCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Currently being reviewed
            </p>
        </div>


        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-26px_rgba(15,23,42,0.25)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_20px_36px_-24px_rgba(15,23,42,0.3)]">

            <div class="flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                    Roadmaps Ready
                </p>

                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-500 transition group-hover:bg-emerald-500 group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                {{ $readyCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Finalized and delivered
            </p>
        </div>

    </div>


    {{-- =====================================================
         REQUEST QUEUE
    ====================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]">

        <div class="border-b border-slate-200 bg-slate-50/60 px-6 py-5">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <h2 class="text-lg font-bold tracking-tight text-slate-950">
                        Request Queue
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        <span id="transformation-visible-count" class="font-semibold text-slate-700">{{ $requests->count() }}</span> transformation engagements
                    </p>
                </div>

                <div id="transformation-filters" class="flex flex-wrap gap-2">

                    <button
                        type="button"
                        data-filter="all"
                        class="transformation-filter-btn is-active rounded-full bg-slate-950 px-4 py-2 text-xs font-bold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    >
                        All
                    </button>

                    <button
                        type="button"
                        data-filter="unassigned"
                        class="transformation-filter-btn rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 hover:shadow-md"
                    >
                        Unassigned
                    </button>

                    <button
                        type="button"
                        data-filter="in_review"
                        class="transformation-filter-btn rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 hover:shadow-md"
                    >
                        In Review
                    </button>

                    <button
                        type="button"
                        data-filter="ready"
                        class="transformation-filter-btn rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 hover:shadow-md"
                    >
                        Ready
                    </button>

                </div>

            </div>

        </div>


       <div class="overflow-x-auto">

    <table id="transformation-table" class="w-full text-left">

        <thead class="border-b border-slate-200 bg-slate-50/80 backdrop-blur">
            <tr class="text-xs font-bold uppercase tracking-wide text-slate-500">

                <th class="px-6 py-4">Organization</th>

                <th class="px-6 py-4">Readiness</th>

                <th class="px-6 py-4">Target</th>

                <th class="px-6 py-4">Timeline</th>

                <th class="px-6 py-4">Status</th>

                <th class="px-6 py-4">Consultant</th>

<th class="px-6 py-4">Assignment</th>
            </tr>
        </thead>


        <tbody class="divide-y divide-slate-100">

            @forelse($requests as $assessment)

                @php
                    $status = $assessment->transformation_status;

                    $rowFilter = ! $assessment->assignedConsultant
                        ? 'unassigned'
                        : ($status === 'in_review'
                            ? 'in_review'
                            : ($status === 'roadmap_ready'
                                ? 'ready'
                                : 'other'));

                    $consultantInitial = $assessment->assignedConsultant
                        ? strtoupper(substr(
                            $assessment->assignedConsultant->name
                                ?: $assessment->assignedConsultant->first_name ?? '?',
                            0,
                            1
                        ))
                        : null;
                @endphp

                <tr class="transformation-row group transition-colors duration-150 hover:bg-slate-50/80" data-filter="{{ $rowFilter }}">

                    {{-- ORGANIZATION --}}
                    <td class="px-6 py-5">

                        <p class="font-bold text-slate-950">
                            {{ $assessment->company?->name ?? 'Unknown organization' }}
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Request #{{ $assessment->id }}
                        </p>

                    </td>


                    {{-- READINESS --}}
                    <td class="px-6 py-5">

                        @if($assessment->company_score !== null)

                            <span class="font-bold tabular-nums text-amber-600">
                                {{ number_format($assessment->company_score, 1) }}
                            </span>

                            <span class="text-xs text-slate-400">
                                /100
                            </span>

                        @else

                            <span class="text-slate-400">—</span>

                        @endif

                    </td>


                    {{-- TARGET --}}
                    <td class="px-6 py-5">

                        @if($assessment->target_maturity_level)

                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-200/70">
                                Level {{ $assessment->target_maturity_level }}
                            </span>

                        @else

                            <span class="text-sm text-slate-400">—</span>

                        @endif

                    </td>


                    {{-- TIMELINE --}}
                    <td class="px-6 py-5 text-sm font-medium text-slate-700">

                        {{ $assessment->transformation_timeline ?? '—' }}

                    </td>


                    {{-- STATUS --}}
                    <td class="px-6 py-5">

                        @if($status === 'roadmap_ready')

                            <div>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200/70">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Roadmap Ready
                                </span>

                                @if($assessment->transformationRoadmap?->finalized_at)
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $assessment->transformationRoadmap->finalized_at->format('d M Y') }}
                                    </p>
                                @endif
                            </div>

                        @elseif($status === 'in_review')

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-200/70">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                In Review
                            </span>

                        @elseif($status === 'submitted')

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-200/70">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                Submitted
                            </span>

                        @else

                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-200/70">
                                {{ ucfirst(str_replace('_', ' ', $status ?? 'Unknown')) }}
                            </span>

                        @endif

                    </td>


                    {{-- CONSULTANT --}}
                    <td class="px-6 py-5">

                        @if($assessment->assignedConsultant)

                            <div class="flex items-center gap-2.5">

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-950 text-xs font-bold text-white">
                                    {{ $consultantInitial }}
                                </div>

                                <div>
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ $assessment->assignedConsultant->name }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-slate-400">
                                        {{ $assessment->assignedConsultant->email }}
                                    </p>
                                </div>

                            </div>

                        @else

                            <div>
                                <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-600 ring-1 ring-inset ring-red-200/70">
                                    Unassigned
                                </span>
                            </div>

                        @endif

                    </td>


                    {{-- ASSIGNMENT --}}
<td class="px-6 py-5">

    @if($status === 'roadmap_ready')

        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700">
            ✓ Completed
        </span>

    @else

        <form
            method="POST"
            action="{{ route('admin.transformations.assign', $assessment) }}"
class="flex items-center gap-2"        >
            @csrf
            @method('PATCH')

            <select
    name="consultant_id"
    required
class="w-[170px] rounded-lg border border-slate-200 bg-white py-2 pl-3 pr-8 text-xs font-medium text-slate-700 shadow-sm outline-none transition cursor-pointer focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                <option value="">
                    Select consultant
                </option>

                @foreach($consultants as $consultant)

                    <option
                        value="{{ $consultant->id }}"
                        @selected(
                            $assessment->assigned_consultant_id === $consultant->id
                        )
                    >
                        {{ $consultant->name ?: trim(
                            $consultant->first_name . ' ' . $consultant->last_name
                        ) }}
                    </option>

                @endforeach

            </select>

           <button
    type="submit"
    class="whitespace-nowrap rounded-lg bg-gradient-to-r from-slate-950 to-slate-800 px-4 py-2 text-xs font-bold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
>
    {{ $assessment->assigned_consultant_id
        ? 'Reassign'
        : 'Assign' }}
</button>

        </form>

    @endif

</td>

                </tr>

            @empty

                <tr>
                    <td
                        colspan="7"
                        class="px-6 py-16 text-center"
                    >
                        <div class="mx-auto max-w-sm">

                            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-400 ring-1 ring-inset ring-slate-200/70">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h4m0 0l-3-3m3 3l-3 3M4 7h6l2 3h8a1 1 0 011 1v8a2 2 0 01-2 2H6a2 2 0 01-2-2V8a1 1 0 011-1z"/>
                                </svg>
                            </div>

                            <p class="mt-3 text-sm font-semibold text-slate-700">
                                No paid Transformation requests found.
                            </p>

                        </div>
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

    </div>

</div>

<script>
(function () {
    var filterButtons = document.querySelectorAll('.transformation-filter-btn');
    var rows = document.querySelectorAll('.transformation-row');
    var visibleCountEl = document.getElementById('transformation-visible-count');

    var activeClasses = ['bg-slate-950', 'text-white'];
    var inactiveClasses = ['border', 'border-slate-200', 'text-slate-600'];

    function setActiveButton(button) {
        filterButtons.forEach(function (btn) {
            btn.classList.remove('is-active');
            activeClasses.forEach(function (c) { btn.classList.remove(c); });
            inactiveClasses.forEach(function (c) { btn.classList.add(c); });
        });

        button.classList.add('is-active');
        inactiveClasses.forEach(function (c) { button.classList.remove(c); });
        activeClasses.forEach(function (c) { button.classList.add(c); });
    }

    function applyFilter(filter) {
        var visible = 0;

        rows.forEach(function (row) {
            var matches = filter === 'all' || row.getAttribute('data-filter') === filter;
            row.style.display = matches ? '' : 'none';
            if (matches) visible++;
        });

        if (visibleCountEl) {
            visibleCountEl.textContent = visible;
        }
    }

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            setActiveButton(button);
            applyFilter(button.getAttribute('data-filter'));
        });
    });
})();
</script>

@endsection
