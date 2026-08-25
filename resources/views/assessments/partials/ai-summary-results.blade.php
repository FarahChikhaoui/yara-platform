  @if($summaryIsStale)
            <div class="mt-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-white">
                    !
                </div>
                <p class="text-sm leading-6 text-amber-800">
                    <span class="font-semibold">This assessment was updated</span> after this analysis was generated. Consider regenerating for an interpretation that matches the current scores.
                </p>
            </div>
        @endif

        {{-- Headline verdict --}}
        @if(!empty($aiAnalysis['headline']))
            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-6 py-5">
                <p class="text-lg font-bold leading-snug text-slate-900">
                    "{{ $aiAnalysis['headline'] }}"
                </p>
            </div>
        @endif

        {{-- Data confidence --}}
        @if(isset($assessedDimensionCount, $totalDimensionCount))
            <p class="mt-3 text-xs text-slate-400">
                Based on {{ $assessedDimensionCount }} of {{ $totalDimensionCount }}
                {{ $totalDimensionCount === 1 ? 'dimension' : 'dimensions' }} assessed.
            </p>
        @endif

        {{-- 3 MAIN INSIGHTS --}}
        <div class="mt-6 grid gap-5 md:grid-cols-3">

            {{-- RISK --}}
            <div class="rounded-2xl border border-red-200 bg-red-50 p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-red-600">
                    Priority Risk
                </p>

                <h3 class="mt-3 text-lg font-bold text-slate-900">
                    {{ $aiAnalysis['priority_risk']['title'] ?? '' }}
                </h3>

                <p class="mt-1 text-sm font-semibold text-red-700">
                    {{ $aiAnalysis['priority_risk']['dimension'] ?? '' }}
                </p>

                <p class="mt-4 text-sm leading-6 text-slate-600">
                    {{ $aiAnalysis['priority_risk']['insight'] ?? '' }}
                </p>

               
            </div>


            {{-- STRENGTH --}}
            <div class="rounded-2xl border border-green-200 bg-green-50 p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-green-700">
                    Strategic Strength
                </p>

                <h3 class="mt-3 text-lg font-bold text-slate-900">
                    {{ $aiAnalysis['strategic_strength']['title'] ?? '' }}
                </h3>

                <p class="mt-1 text-sm font-semibold text-green-700">
                    {{ $aiAnalysis['strategic_strength']['dimension'] ?? '' }}
                </p>

                <p class="mt-4 text-sm leading-6 text-slate-600">
                    {{ $aiAnalysis['strategic_strength']['insight'] ?? '' }}
                </p>
            </div>


            {{-- READINESS PATTERN --}}
<div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-6">

    <p class="text-xs font-semibold uppercase tracking-wider text-yellow-700">
        Readiness Pattern
    </p>

    <h3 class="mt-3 text-xl font-bold text-slate-900">
        {{ $aiAnalysis['readiness_pattern']['title'] ?? '' }}
    </h3>

    <p class="mt-4 text-sm leading-6 text-slate-600">
        {{ $aiAnalysis['readiness_pattern']['insight'] ?? '' }}
    </p>

</div> {{-- closes Readiness Pattern --}}

</div> {{-- closes 3 MAIN INSIGHTS grid --}}

{{-- COUNTRY CONTEXT --}}
        @if(!empty($aiAnalysis['country_context']))
            <div class="mt-6 rounded-xl bg-slate-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Country Benchmark Context
                </p>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ $aiAnalysis['country_context'] }}
                </p>
            </div>
        @endif