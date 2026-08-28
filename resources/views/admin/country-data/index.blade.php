@extends('layouts.admin')

@section('content')

@php
    // ------------------------------------------------------------------
    // Lightweight KPI stats derived purely from data already passed to
    // this view ($datasets, $years). Nothing new is queried here, so
    // this can't break if the controller doesn't provide extra vars.
    // ------------------------------------------------------------------
    $datasetsCollection = collect($datasets);

    $totalDatasets = $datasetsCollection->count();

    $activeDataset = $datasetsCollection->first(function ($dataset) {
        return $dataset->is_active;
    });

    $totalCountryRecords = $datasetsCollection->sum('records_count');

    $yearsCovered = collect($years)->count();

    $latestDataset = $datasetsCollection->sortByDesc('year')->first();

    $maxRecordsInDataset = $datasetsCollection->max('records_count') ?: 0;

    $datasetsByYear = $datasetsCollection->sortByDesc('year')->take(6);
@endphp

<div class="space-y-8">
<div class="pointer-events-none fixed -top-24 left-1/2 -z-10 h-72 w-[36rem] -translate-x-1/2 rounded-full bg-yellow-300/10 blur-3xl"></div>

    {{-- Header --}}
<div class="relative overflow-hidden rounded-3xl border border-slate-200/70 bg-gradient-to-br from-white via-white to-slate-50 p-7 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]">

    <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-amber-300/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

        {{-- Title --}}
        <div class="min-w-0">

            <div class="mb-2 flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </span>

                <span class="text-sm font-semibold uppercase tracking-[0.16em] text-amber-600">
                    Benchmark Intelligence
                </span>
            </div>

            <h1 class="text-4xl font-bold tracking-tight text-slate-950">
                Country Benchmark Data
            </h1>

            <p class="mt-2 max-w-3xl text-lg text-slate-500">
                Manage the external AI readiness data powering YARA's
                national benchmarks, historical intelligence and country comparisons.
            </p>

        </div>


        {{-- Actions --}}
        <div class="flex shrink-0 items-center gap-2">

            <button
                type="button"
                id="open-datasets-modal"
                class="inline-flex h-10 items-center justify-center gap-2
                       rounded-lg border border-slate-300 bg-white
                       px-4 text-sm font-semibold text-slate-700
                       shadow-sm transition-all duration-200
                       hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-50 hover:shadow-md"
            >
                <svg
                    class="h-4 w-4 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>

                Datasets
            </button>


           <a
        href="{{ route('admin.country-data.import') }}"
        class="inline-flex h-10 items-center justify-center gap-2
               rounded-lg bg-gradient-to-r from-slate-950 to-slate-800 px-4
               text-sm font-semibold text-white
               shadow-md shadow-slate-900/20 transition-all duration-200
               hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/25"
    >
        <svg
            class="h-4 w-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 5v14M5 12h14"
            />
        </svg>

        Import dataset
    </a>

        </div>

    </div>

</div>

    {{-- KPI overview --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total datasets --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-24px_rgba(15,23,42,0.25)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_20px_36px_-24px_rgba(15,23,42,0.3)]">

            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Total datasets
                </p>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-slate-950 group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 1.105 3.582 2 8 2s8-.895 8-2V7M4 7c0 1.105 3.582 2 8 2s8-.895 8-2M4 7c0-1.105 3.582-2 8-2s8 .895 8 2m0 5c0 1.105-3.582 2-8 2s-8-.895-8-2"/>
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
                {{ $totalDatasets }}
            </p>

            <p class="mt-1 text-sm text-slate-400">
                Registered benchmark releases
            </p>

        </div>


        {{-- Active dataset --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-24px_rgba(15,23,42,0.25)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_20px_36px_-24px_rgba(15,23,42,0.3)]">

            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Active benchmark
                </p>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-500 group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            @if($activeDataset)
                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
                    {{ $activeDataset->year }}
                </p>

                <p class="mt-1 truncate text-sm text-slate-400">
                    {{ $activeDataset->source }}
                </p>
            @else
                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-300">
                    —
                </p>

                <p class="mt-1 text-sm text-slate-400">
                    No active dataset set
                </p>
            @endif

        </div>


        {{-- Total country records --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-24px_rgba(15,23,42,0.25)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_20px_36px_-24px_rgba(15,23,42,0.3)]">

            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Country records
                </p>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition group-hover:bg-amber-500 group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
                {{ number_format($totalCountryRecords) }}
            </p>

            <p class="mt-1 text-sm text-slate-400">
                Across all imported datasets
            </p>

        </div>


        {{-- Years covered --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-24px_rgba(15,23,42,0.25)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_1px_2px_rgba(15,23,42,0.05),0_20px_36px_-24px_rgba(15,23,42,0.3)]">

            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Years covered
                </p>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition group-hover:bg-blue-500 group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
                {{ $yearsCovered }}
            </p>

            <p class="mt-1 text-sm text-slate-400">
                @if($latestDataset)
                    Latest: {{ $latestDataset->year }}
                @else
                    No benchmark years yet
                @endif
            </p>

        </div>

    </div>


    {{-- Dataset coverage mini-visual --}}
    @if($datasetsByYear->isNotEmpty())

        <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-24px_rgba(15,23,42,0.25)]">

            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Dataset Coverage
                    </p>

                    <h3 class="mt-1 text-base font-bold text-slate-950">
                        Country records by benchmark year
                    </h3>
                </div>

                <span class="text-xs text-slate-400">
                    Most recent {{ $datasetsByYear->count() }} {{ $datasetsByYear->count() === 1 ? 'release' : 'releases' }}
                </span>
            </div>

            <div class="mt-5 space-y-3">

                @foreach($datasetsByYear as $dataset)

                    @php
                        $barWidth = $maxRecordsInDataset > 0
                            ? max(4, round(($dataset->records_count / $maxRecordsInDataset) * 100))
                            : 4;
                    @endphp

                    <div class="flex items-center gap-4">

                        <span class="w-14 shrink-0 text-sm font-bold text-slate-700">
                            {{ $dataset->year }}
                        </span>

                        <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full rounded-full {{ $dataset->is_active ? 'bg-gradient-to-r from-emerald-500 to-emerald-400' : 'bg-gradient-to-r from-slate-400 to-slate-300' }} transition-all duration-700 ease-out"
                                style="width: {{ $barWidth }}%;"
                            ></div>
                        </div>

                        <span class="w-24 shrink-0 text-right text-xs font-semibold text-slate-500">
                            {{ number_format($dataset->records_count) }}
                        </span>

                        @if($dataset->is_active)
                            <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700">
                                <span class="h-1 w-1 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        @else
                            <span class="w-[58px] shrink-0"></span>
                        @endif

                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- Benchmark repository --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04),0_16px_32px_-24px_rgba(15,23,42,0.25)]">

        <div class="border-b border-slate-200 bg-slate-50/60 p-6">

            <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Benchmark Repository
                    </p>

                    <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
                        Country Records
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Inspect country-level AI readiness observations imported into YARA.
                    </p>
                </div>


                {{-- Filters --}}
                <form
                    id="country-filter-form"
                    method="GET"
                    action="{{ route('admin.country-data.index') }}"
                    class="flex flex-col gap-3 sm:flex-row"
                >

                    <input
                        id="country-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search country..."
                        autocomplete="off"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5
                               text-sm shadow-sm transition focus:border-amber-400 focus:ring-amber-400
                               sm:w-64"
                    >


                    <select
                        id="country-year"
                        name="year"
                        class="rounded-xl border border-slate-300 px-4 py-2.5
                               text-sm shadow-sm transition focus:border-amber-400 focus:ring-amber-400"
                    >
                        <option value="">
                            All years
                        </option>

                        @foreach($years as $year)
                            <option
                                value="{{ $year }}"
                                {{ (string) request('year') === (string) $year
                                    ? 'selected'
                                    : '' }}
                            >
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>


                    <button
                        type="submit"
                        class="rounded-xl bg-gradient-to-r from-slate-950 to-slate-800 px-5 py-2.5
                               text-sm font-semibold text-white shadow-sm
                               transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    >
                        Filter
                    </button>


                    <button
                        id="country-filter-reset"
                        type="button"
                        class="rounded-xl border border-slate-300 px-4 py-2.5
                               text-sm font-semibold text-slate-600
                               transition hover:bg-slate-50"
                    >
                        Reset
                    </button>

                </form>

            </div>

        </div>


        {{-- Only this part gets replaced by AJAX --}}
        <div
            id="country-records-container"
            class="transition-opacity duration-150"
        >
            @include('admin.country-data.partials.records')
        </div>

    </div>

</div>
{{-- ============================================================
     DATASETS MODAL
============================================================ --}}
<div
    id="datasets-modal"
    class="fixed inset-0 z-50 hidden"
    aria-hidden="true"
>
    {{-- Backdrop --}}
    <div
        id="datasets-modal-backdrop"
        class="absolute inset-0 bg-slate-950/40 backdrop-blur-[2px]"
    ></div>


    {{-- Modal positioning --}}
    <div
        class="relative flex min-h-full items-center
               justify-center p-4 sm:p-6"
    >

        {{-- Modal --}}
        <div
            class="relative w-full max-w-4xl overflow-hidden
                   rounded-2xl border border-slate-200
                   bg-white shadow-2xl"
        >

            {{-- Header --}}
            <div
                class="flex items-start justify-between
                       border-b border-slate-200 px-7 py-6"
            >

                <div>

                    <p
                        class="text-xs font-bold uppercase
                               tracking-[0.18em] text-slate-400"
                    >
                        Benchmark Data
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-slate-950">
                        Imported Datasets
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Benchmark releases currently registered in YARA.
                    </p>

                </div>


                <button
                    type="button"
                    id="close-datasets-modal"
                    class="flex h-9 w-9 items-center justify-center
                           rounded-lg text-slate-400 transition
                           hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Close"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

            </div>


            {{-- Dataset list --}}
            <div class="max-h-[65vh] overflow-y-auto">

                @forelse($datasets as $dataset)

                    <div
                        class="flex flex-col gap-4 border-b
                               border-slate-100 px-7 py-5
                               transition hover:bg-slate-50/60
                               last:border-b-0
                               sm:flex-row sm:items-center
                               sm:justify-between"
                    >

                        {{-- Information --}}
                        <div class="flex min-w-0 items-center gap-4">

                            {{-- Year --}}
                            <div
                                class="flex h-12 w-16 shrink-0
                                       items-center justify-center
                                       rounded-xl bg-slate-100
                                       text-sm font-bold text-slate-800"
                            >
                                {{ $dataset->year }}
                            </div>


                            <div class="min-w-0">

                                <div class="flex flex-wrap items-center gap-2">

                                    <p
                                        class="truncate text-sm
                                               font-semibold text-slate-900"
                                    >
                                        {{ $dataset->source }}
                                    </p>


                                    @if($dataset->is_active)

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full bg-emerald-50
                                                   px-2.5 py-1 text-xs
                                                   font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200/70"
                                        >
                                            <span
                                                class="h-1.5 w-1.5
                                                       rounded-full bg-emerald-500"
                                            ></span>

                                            Active
                                        </span>

                                    @else

                                        <span
                                            class="rounded-full bg-slate-100
                                                   px-2.5 py-1 text-xs
                                                   font-semibold text-slate-500"
                                        >
                                            Inactive
                                        </span>

                                    @endif

                                </div>


                                <p class="mt-1 text-xs text-slate-500">

                                    {{ number_format($dataset->records_count) }}
                                    country records

                                    <span class="mx-1.5 text-slate-300">•</span>

                                    {{ $dataset->etl_method ?? 'Python ETL' }}

                                </p>

                            </div>

                        </div>


                        {{-- Actions --}}
                        <div
                            class="flex shrink-0 items-center gap-2
                                   sm:justify-end"
                        >

                            {{-- View --}}
                            <a
                                href="{{ route(
                                    'admin.country-data.index',
                                    ['year' => $dataset->year]
                                ) }}"
                                class="rounded-lg border border-slate-200
                                       bg-white px-3.5 py-2
                                       text-xs font-semibold text-slate-700
                                       shadow-sm transition hover:bg-slate-50"
                            >
                                View
                            </a>


                            {{-- Delete --}}
                            @if(!$dataset->is_active)

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.country-data.datasets.destroy',
                                        $dataset
                                    ) }}"
                                    onsubmit="
                                        return confirm(
                                            'Delete the {{ $dataset->year }} benchmark dataset and all of its country records? This action cannot be undone.'
                                        );
                                    "
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-lg px-3.5 py-2
                                               text-xs font-semibold
                                               text-red-600 transition
                                               hover:bg-red-50"
                                    >
                                        Delete
                                    </button>

                                </form>

                            @else

                                <span
                                    class="px-3 py-2 text-xs
                                           font-medium text-slate-400"
                                    title="Active datasets cannot be deleted"
                                >
                                    Protected
                                </span>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="px-7 py-14 text-center">

                        <p class="text-sm font-semibold text-slate-700">
                            No benchmark datasets
                        </p>

                        <p class="mt-1 text-sm text-slate-400">
                            Import your first processed dataset to get started.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- Footer --}}
            <div
                class="flex items-center justify-between
                       border-t border-slate-200 bg-slate-50
                       px-7 py-4"
            >

               
            </div>

        </div>

    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('datasets-modal');
    const openButton = document.getElementById('open-datasets-modal');
    const closeButton = document.getElementById('close-datasets-modal');
    const backdrop = document.getElementById('datasets-modal-backdrop');


    if (!modal || !openButton) {
        return;
    }


    function openModal() {
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');
    }


    function closeModal() {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('overflow-hidden');
    }


    openButton.addEventListener('click', openModal);

    closeButton?.addEventListener('click', closeModal);

    backdrop?.addEventListener('click', closeModal);


    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape'
            && !modal.classList.contains('hidden')
        ) {
            closeModal();
        }

    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('country-filter-form');
    const searchInput = document.getElementById('country-search');
    const yearSelect = document.getElementById('country-year');
    const resetButton = document.getElementById('country-filter-reset');
    const container = document.getElementById('country-records-container');

    let searchTimer = null;
    let activeController = null;


    /**
     * Load only the records partial.
     */
    async function loadRecords(url = null) {

        /*
         * Cancel the previous request if the user types
         * or changes filters quickly.
         */
        if (activeController) {
            activeController.abort();
        }

        activeController = new AbortController();


        const params = new URLSearchParams(
            new FormData(form)
        );

        const requestUrl = url
            ? url
            : `${form.action}?${params.toString()}`;


        /*
         * Small visual loading state.
         */
        container.style.opacity = '0.45';
        container.style.pointerEvents = 'none';


        try {

            const response = await fetch(requestUrl, {
                method: 'GET',

                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                },

                signal: activeController.signal
            });


            if (!response.ok) {
                throw new Error(
                    `Request failed with status ${response.status}`
                );
            }


            const html = await response.text();

            container.innerHTML = html;


            /*
             * Keep the browser URL synchronized without
             * reloading the page.
             */
            const browserUrl = new URL(
                requestUrl,
                window.location.origin
            );

            window.history.replaceState(
                {},
                '',
                browserUrl.pathname + browserUrl.search
            );

        } catch (error) {

            /*
             * AbortError is expected when a newer request
             * replaces an older one.
             */
            if (error.name !== 'AbortError') {
                console.error(
                    'Country benchmark filtering failed:',
                    error
                );
            }

        } finally {

            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';

        }
    }


    /**
     * Filter button.
     */
    form.addEventListener('submit', function (event) {

        event.preventDefault();

        loadRecords();

    });


    /**
     * Changing the year immediately refreshes records.
     */
    yearSelect.addEventListener('change', function () {

        loadRecords();

    });


    /**
     * Search automatically after the user stops typing.
     */
    searchInput.addEventListener('input', function () {

        clearTimeout(searchTimer);

        searchTimer = setTimeout(function () {

            loadRecords();

        }, 350);

    });


    /**
     * Reset filters without reloading the page.
     */
    resetButton.addEventListener('click', function () {

        searchInput.value = '';
        yearSelect.value = '';

        loadRecords();

    });


    /**
     * AJAX pagination.
     *
     * Pagination HTML comes from the partial, so we use
     * event delegation instead of attaching listeners
     * directly to each link.
     */
    container.addEventListener('click', function (event) {

        const paginationLink = event.target.closest(
            'a[data-country-page]'
        );

        if (!paginationLink) {
            return;
        }

        event.preventDefault();

        loadRecords(paginationLink.href);

    });

});
</script>

@endsection