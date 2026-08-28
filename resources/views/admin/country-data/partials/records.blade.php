<div class="overflow-x-auto">

    <table class="min-w-full divide-y divide-slate-200">

        <thead class="bg-slate-50/80 backdrop-blur">

            <tr>

                <th
                    class="px-6 py-4 text-left text-xs font-semibold
                           uppercase tracking-wider text-slate-500"
                >
                    Country
                </th>

                <th
                    class="px-6 py-4 text-left text-xs font-semibold
                           uppercase tracking-wider text-slate-500"
                >
                    Year
                </th>

                <th
                    class="px-6 py-4 text-left text-xs font-semibold
                           uppercase tracking-wider text-slate-500"
                >
                    Score
                </th>

                <th
                    class="px-6 py-4 text-left text-xs font-semibold
                           uppercase tracking-wider text-slate-500"
                >
                    Final Rank
                </th>

                <th
                    class="px-6 py-4 text-left text-xs font-semibold
                           uppercase tracking-wider text-slate-500"
                >
                    Official Rank
                </th>

                <th
                    class="px-6 py-4 text-left text-xs font-semibold
                           uppercase tracking-wider text-slate-500"
                >
                    Score Type
                </th>

                <th
                    class="px-6 py-4 text-left text-xs font-semibold
                           uppercase tracking-wider text-slate-500"
                >
                    Data Quality
                </th>

            </tr>

        </thead>


        <tbody class="divide-y divide-slate-100 bg-white">

            @forelse($records as $record)

                @php
                    $recordScore = (float) $record->score;

                    if ($recordScore < 40) {
                        $scoreBarClass = 'bg-gradient-to-r from-red-500 to-red-400';
                    } elseif ($recordScore < 60) {
                        $scoreBarClass = 'bg-gradient-to-r from-amber-500 to-amber-400';
                    } elseif ($recordScore < 80) {
                        $scoreBarClass = 'bg-gradient-to-r from-blue-500 to-blue-400';
                    } else {
                        $scoreBarClass = 'bg-gradient-to-r from-emerald-500 to-emerald-400';
                    }
                @endphp

                <tr class="group transition-colors duration-150 hover:bg-slate-50/80">

                    {{-- Country --}}
                    <td class="whitespace-nowrap px-6 py-4">

                        <div class="flex items-center gap-2.5">
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-slate-300 transition group-hover:bg-amber-400"></span>

                            <div class="font-semibold text-slate-900">
                                {{ $record->country }}
                            </div>
                        </div>

                    </td>


                    {{-- Year --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                        {{ $record->year }}

                    </td>


                    {{-- Score --}}
                    <td class="whitespace-nowrap px-6 py-4">

                        <div class="flex items-center gap-3">

                            <div class="flex items-baseline gap-1">
                                <span class="font-bold tabular-nums text-slate-950">
                                    {{ number_format($recordScore, 1) }}
                                </span>

                                <span class="text-xs text-slate-400">
                                    /100
                                </span>
                            </div>

                            <div class="h-1.5 w-16 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full {{ $scoreBarClass }} transition-all duration-500"
                                    style="width: {{ min(100, max(0, $recordScore)) }}%;"
                                ></div>
                            </div>

                        </div>

                    </td>


                    {{-- Final rank --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm">

                        @if($record->final_rank)
                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">
                                #{{ $record->final_rank }}
                            </span>
                        @else
                            <span class="text-slate-300">—</span>
                        @endif

                    </td>


                    {{-- Official rank --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                        {{ $record->official_rank
                            ? '#' . $record->official_rank
                            : '—' }}

                    </td>


                    {{-- Score type --}}
                    <td class="whitespace-nowrap px-6 py-4">

                        @if($record->score_type)

                            <span
                                class="rounded-full bg-blue-50 px-3 py-1
                                       text-xs font-semibold text-blue-700
                                       ring-1 ring-inset ring-blue-100"
                            >
                                {{ $record->score_type }}
                            </span>

                        @else

                            <span class="text-sm text-slate-400">
                                —
                            </span>

                        @endif

                    </td>


                    {{-- Data quality --}}
                    <td class="whitespace-nowrap px-6 py-4">

                        @if(($record->missing_values ?? 0) == 0)

                            <span
                                class="inline-flex items-center gap-1.5
                                       rounded-full bg-emerald-50 px-3 py-1
                                       text-xs font-semibold text-emerald-700
                                       ring-1 ring-inset ring-emerald-100"
                            >
                                <span
                                    class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                                ></span>

                                Complete
                            </span>

                        @else

                            <span
                                class="inline-flex items-center gap-1.5
                                       rounded-full bg-amber-50 px-3 py-1
                                       text-xs font-semibold text-amber-700
                                       ring-1 ring-inset ring-amber-100"
                            >
                                {{ $record->missing_values }}
                                missing
                            </span>

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

                            <div
                                class="mx-auto flex h-11 w-11 items-center
                                       justify-center rounded-full bg-slate-100
                                       text-slate-400 ring-1 ring-inset ring-slate-200/70"
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
                                        stroke-width="1.8"
                                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                                    />
                                </svg>
                            </div>

                            <p class="mt-3 font-semibold text-slate-700">
                                No benchmark records found
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Try another country or benchmark year.
                            </p>

                        </div>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>


{{-- Pagination --}}
@if($records->hasPages())

    <div
        class="flex flex-col gap-3 border-t border-slate-200 bg-slate-50/40
               px-6 py-4 sm:flex-row sm:items-center sm:justify-between"
    >

        <p class="text-sm text-slate-500">

            Showing

            <span class="font-semibold text-slate-700">
                {{ $records->firstItem() }}
            </span>

            to

            <span class="font-semibold text-slate-700">
                {{ $records->lastItem() }}
            </span>

            of

            <span class="font-semibold text-slate-700">
                {{ $records->total() }}
            </span>

            records

        </p>


        <div class="flex items-center gap-2">

            {{-- Previous --}}
            @if($records->onFirstPage())

                <span
                    class="cursor-not-allowed rounded-lg border
                           border-slate-200 px-3 py-2 text-sm
                           text-slate-300"
                >
                    Previous
                </span>

            @else

                <a
                    href="{{ $records->previousPageUrl() }}"
                    data-country-page
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2
                           text-sm font-medium text-slate-600 shadow-sm
                           transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-50 hover:shadow"
                >
                    Previous
                </a>

            @endif


            <span
                class="rounded-lg bg-slate-950 px-3 py-2
                       text-sm font-semibold text-white shadow-sm"
            >
                Page {{ $records->currentPage() }}
                of {{ $records->lastPage() }}
            </span>


            {{-- Next --}}
            @if($records->hasMorePages())

                <a
                    href="{{ $records->nextPageUrl() }}"
                    data-country-page
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2
                           text-sm font-medium text-slate-600 shadow-sm
                           transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-50 hover:shadow"
                >
                    Next
                </a>

            @else

                <span
                    class="cursor-not-allowed rounded-lg border
                           border-slate-200 px-3 py-2 text-sm
                           text-slate-300"
                >
                    Next
                </span>

            @endif

        </div>

    </div>

@endif