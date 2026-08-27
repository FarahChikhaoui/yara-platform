@extends('layouts.admin')

@section('content')

<div class="flex flex-col min-h-0" style="height: 100%;">

    {{-- HEADER --}}
    <div class="flex items-start justify-between mb-5 flex-shrink-0">
        <div>
            <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
                Admin Management
            </p>
            <h1 class="text-4xl font-bold text-slate-950 mt-1">Assessments</h1>
            <p class="mt-2 text-slate-500">
                Monitor submitted assessments across all organizations.
            </p>
        </div>

        {{-- Stats --}}
        @php
            $total      = $assessments->count();
            $completed  = $assessments->where('status', 'completed')->count();
            $inProgress = $assessments->where('status', '!=', 'completed')->count();
        @endphp
        <div class="flex gap-3">
            <div class="text-center bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $total }}</p>
            </div>
            <div class="text-center bg-white rounded-2xl border border-green-200 shadow-sm px-5 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-green-500">Completed</p>
                <p class="text-2xl font-bold text-green-700 mt-1">{{ $completed }}</p>
            </div>
            <div class="text-center bg-white rounded-2xl border border-yellow-200 shadow-sm px-5 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-yellow-600">In Progress</p>
                <p class="text-2xl font-bold text-yellow-700 mt-1">{{ $inProgress }}</p>
            </div>
        </div>
    </div>

    {{-- STATUS FILTER --}}
    <div class="mb-4 flex-shrink-0 flex items-center gap-2">
        <button onclick="filterStatus('all')" data-status="all"
                class="status-btn px-4 py-2 rounded-full text-xs font-bold border border-yellow-400 bg-yellow-400 text-slate-950 transition">
            All
        </button>
        <button onclick="filterStatus('completed')" data-status="completed"
                class="status-btn px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-white text-slate-600 hover:border-green-400 hover:bg-green-50 transition">
            Completed
        </button>
        <button onclick="filterStatus('in_progress')" data-status="in_progress"
                class="status-btn px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-white text-slate-600 hover:border-yellow-400 hover:bg-yellow-50 transition">
            In Progress
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
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 w-8">#</th>
                   <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Company</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">User</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Date</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100" id="assessmentsBody">

                    @forelse($assessments as $i => $assessment)
                        @php $isCompleted = $assessment->status === 'completed'; @endphp

                        <tr class="assessment-row hover:bg-slate-50 transition"
                            data-status="{{ $isCompleted ? 'completed' : 'in_progress' }}">

                            {{-- Row number --}}
                            <td class="px-6 py-5 text-xs text-slate-400 font-mono">
                                {{ $i + 1 }}
                            </td>


                            {{-- Company --}}
                            <td class="px-6 py-5">
                                @if($assessment->company)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 flex-shrink-0">
                                            {{ strtoupper(substr($assessment->company->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">{{ $assessment->company->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            {{-- User --}}
                            <td class="px-6 py-5">
                                @if($assessment->user)
                                    <p class="text-sm text-slate-700 font-medium">{{ $assessment->user->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $assessment->user->email ?? '' }}</p>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-5">
                                @if($isCompleted)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 text-green-700 text-xs font-bold px-3 py-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                                        In Progress
                                    </span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-5">
                                @if($assessment->created_at)
                                    <p class="text-sm text-slate-700">{{ $assessment->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $assessment->created_at->diffForHumans() }}</p>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <p class="text-lg font-semibold text-slate-700">No assessments yet</p>
                                <p class="mt-2 text-sm text-slate-500">Assessments will appear here once organizations start submitting.</p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            <div id="noResults" class="hidden py-16 text-center">
                <p class="text-lg font-semibold text-slate-700">No assessments match this filter</p>
                <p class="mt-1 text-sm text-slate-400">Try selecting a different status.</p>
            </div>

        </div>
    </div>

</div>

<script>
function filterStatus(status) {
    document.querySelectorAll('.status-btn').forEach(btn => {
        const active = btn.dataset.status === status;
        btn.className = active
            ? 'status-btn px-4 py-2 rounded-full text-xs font-bold border border-yellow-400 bg-yellow-400 text-slate-950 transition'
            : 'status-btn px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-white text-slate-600 hover:border-yellow-400 hover:bg-yellow-50 transition';
    });

    const rows = document.querySelectorAll('.assessment-row');
    let visible = 0;
    rows.forEach(row => {
        const show = status === 'all' || row.dataset.status === status;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('noResults').classList.toggle('hidden', visible > 0);
}
</script>

@endsection