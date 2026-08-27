@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-yellow-600">
                Operations
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-950">
                Transformation Requests
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Assign consultants and track paid Transformation engagements
                from submission through final roadmap delivery.
            </p>
        </div>

    </div>


   
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Unassigned
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-950">
                {{ $unassignedCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Waiting for consultant assignment
            </p>
        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Assigned
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-950">
                {{ $assignedCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Assigned but review not started
            </p>
        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                In Review
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-950">
                {{ $inReviewCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Currently being reviewed
            </p>
        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Roadmaps Ready
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-950">
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
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <h2 class="text-lg font-bold text-slate-950">
                        Request Queue
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        <span id="transformation-visible-count">{{ $requests->count() }}</span> transformation engagements
                    </p>
                </div>

                <div id="transformation-filters" class="flex flex-wrap gap-2">

                    <button
                        type="button"
                        data-filter="all"
                        class="transformation-filter-btn is-active rounded-full bg-slate-950 px-4 py-2 text-xs font-bold text-white"
                    >
                        All
                    </button>

                    <button
                        type="button"
                        data-filter="unassigned"
                        class="transformation-filter-btn rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600"
                    >
                        Unassigned
                    </button>

                    <button
                        type="button"
                        data-filter="in_review"
                        class="transformation-filter-btn rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600"
                    >
                        In Review
                    </button>

                    <button
                        type="button"
                        data-filter="ready"
                        class="transformation-filter-btn rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600"
                    >
                        Ready
                    </button>

                </div>

            </div>

        </div>


       <div class="overflow-x-auto">

    <table id="transformation-table" class="w-full text-left">

        <thead class="border-b border-slate-200 bg-slate-50">
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
                @endphp

                <tr class="transformation-row transition hover:bg-slate-50" data-filter="{{ $rowFilter }}">

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

                            <span class="font-bold text-amber-600">
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

                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
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
                                <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                    Roadmap Ready
                                </span>

                                @if($assessment->transformationRoadmap?->finalized_at)
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $assessment->transformationRoadmap->finalized_at->format('d M Y') }}
                                    </p>
                                @endif
                            </div>

                        @elseif($status === 'in_review')

                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                In Review
                            </span>

                        @elseif($status === 'submitted')

                            <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                Submitted
                            </span>

                        @else

                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                {{ ucfirst(str_replace('_', ' ', $status ?? 'Unknown')) }}
                            </span>

                        @endif

                    </td>


                    {{-- CONSULTANT --}}
                    <td class="px-6 py-5">

                        @if($assessment->assignedConsultant)

                            <div>
                                <p class="text-sm font-bold text-slate-800">
                                    {{ $assessment->assignedConsultant->name }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $assessment->assignedConsultant->email }}
                                </p>
                            </div>

                        @else

                            <div>
                                <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-600">
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
class="w-[170px] rounded-lg border border-slate-200 bg-white py-2 pl-3 pr-8 text-xs font-medium text-slate-700 outline-none transition cursor-pointer focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
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
    class="whitespace-nowrap rounded-lg bg-slate-950 px-4 py-2 text-xs font-bold text-white transition hover:bg-slate-800"
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
                        class="px-6 py-12 text-center text-sm text-slate-500"
                    >
                        No paid Transformation requests found.
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