@extends('layouts.yara')

@section('content')
{{-- FLOATING BACK TO DASHBOARD --}}
<a
    href="{{ route('dashboard') }}"
    title="Back to Dashboard"
    aria-label="Back to Dashboard"
    class="fixed left-6 top-1/2 z-50
           flex h-11 w-11 -translate-y-1/2
           items-center justify-center
           rounded-full border border-slate-200
           bg-white text-slate-700 shadow-lg
           transition-all duration-200
           hover:-translate-x-1
           hover:border-slate-300
           hover:bg-slate-950 hover:text-white"
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        class="h-5 w-5"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15 18l-6-6 6-6"
        />
    </svg>
</a>
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- HEADER --}}
    <div class="wis-fade mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                Scenario Planning
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
                What-if Simulator
            </h1>

            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-500">
                Explore how changes across your AI capabilities could influence
                your organization's overall AI readiness.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <button
                type="button"
                id="preset-optimistic"
                class="wis-btn-ghost"
            >
                <svg viewBox="0 0 20 20" fill="none" class="h-4 w-4"><path d="M10 3v14M10 3l5 5M10 3L5 8" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Best case
            </button>
            <button
                type="button"
                id="preset-conservative"
                class="wis-btn-ghost"
            >
                <svg viewBox="0 0 20 20" fill="none" class="h-4 w-4"><path d="M10 17V3M10 17l5-5M10 17l-5-5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Conservative
            </button>
            <button
                type="button"
                id="reset-simulator"
                class="wis-btn-primary"
            >
                Reset
            </button>
        </div>
    </div>


    {{-- SCORE SUMMARY --}}
    <div class="mb-6 grid gap-4 md:grid-cols-3">

        <div class="wis-fade wis-card">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Current Readiness
            </p>

            <div class="mt-3 flex items-end gap-2">
                <span class="wis-num text-4xl font-bold text-slate-950">
                    {{ number_format($assessment->company_score, 1) }}
                </span>
                <span class="mb-1 text-sm text-slate-400">/ 100</span>
            </div>

            <div class="wis-track mt-4">
                <div class="wis-track-fill" style="width: {{ $assessment->company_score }}%; background: #94a3b8;"></div>
            </div>
        </div>


        <div class="wis-fade wis-card-dark relative overflow-hidden">
            <div class="wis-mesh" aria-hidden="true"></div>

            <div class="relative">
                <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span class="wis-dot"></span>
                    Scenario Readiness
                </p>

                <div class="mt-3 flex items-end gap-2">
                    <span
                        id="scenario-score"
                        class="wis-num text-4xl font-bold text-white transition-colors duration-300"
                    >
                        {{ number_format($assessment->company_score, 1) }}
                    </span>

                    <span class="mb-1 text-sm text-slate-400">/ 100</span>
                </div>

                <div class="wis-track mt-4 bg-white/10">
                    <div id="scenario-track-fill" class="wis-track-fill" style="width: {{ $assessment->company_score }}%; background: linear-gradient(90deg, #eab308, #facc15);"></div>
                </div>
            </div>
        </div>


        <div id="impact-card" class="wis-fade wis-card wis-impact-neutral">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Potential Impact
            </p>

            <div class="mt-3 flex items-center gap-2">
                <svg id="impact-arrow" viewBox="0 0 20 20" fill="none" class="h-5 w-5 shrink-0 opacity-0 transition-all duration-300">
                    <path d="M10 15V5M10 5l4.5 4.5M10 5L5.5 9.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p
                    id="scenario-change"
                    class="wis-num text-4xl font-bold text-slate-950 transition-colors duration-300"
                >
                    +0.0
                </p>
            </div>

            <p class="mt-1 text-xs text-slate-400">
                readiness points
            </p>
        </div>

    </div>


    {{-- MAIN SIMULATOR --}}
    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">

        {{-- SLIDERS --}}
        <section class="wis-fade rounded-3xl border border-slate-200 bg-white p-6 lg:p-8">

            <div>
                <h2 class="text-lg font-bold text-slate-950">
                    Adjust Capabilities
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Drag the controls to explore different scenarios. The bar beneath
                    each label shows how much that capability weighs in your overall score.
                </p>
            </div>


            <div class="mt-8 space-y-7">

                @foreach($dimensions as $index => $dimension)

                    <div
                        class="simulator-dimension wis-dim"
                        data-weight="{{ $dimension['weight_percentage'] }}"
                        data-current="{{ $dimension['current_score'] }}"
                    >

                        <div class="mb-2 flex items-start justify-between gap-4">

                            <div>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $dimension['name'] }}
                                </p>

                                <div class="mt-1.5 flex items-center gap-2">
                                    <div class="wis-weight-track" role="img" aria-label="{{ $dimension['weight_percentage'] }} percent of overall score">
                                        <div class="wis-weight-fill" style="width: {{ $dimension['weight_percentage'] }}%;"></div>
                                    </div>
                                    <span class="whitespace-nowrap text-[11px] font-medium text-slate-400">
                                        {{ number_format($dimension['weight_percentage'], 0) }}% of score
                                    </span>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <span
                                    class="dimension-delta wis-delta"
                                    data-index="{{ $index }}"
                                ></span>

                                <div class="rounded-xl bg-slate-100 px-3 py-2 text-right">
                                    <span
                                        class="dimension-value text-sm font-bold text-slate-950"
                                    >
                                        {{ number_format($dimension['current_score'], 1) }}
                                    </span>

                                    <span class="text-xs text-slate-400">
                                        / 100
                                    </span>
                                </div>
                            </div>

                        </div>


                        <input
                            type="range"
                            min="0"
                            max="100"
                            step="0.1"
value="{{ $dimension['current_score'] }}"
                            class="dimension-slider wis-slider h-2 w-full cursor-pointer"
                            data-index="{{ $index }}"
                            aria-label="{{ $dimension['name'] }} scenario score"
                        >

                        <div class="mt-2 flex justify-between text-[11px] font-medium text-slate-300">
                            <span>0</span>
                            <span>25</span>
                            <span>50</span>
                            <span>75</span>
                            <span>100</span>
                        </div>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- LIVE VISUALIZATION --}}
        <section class="wis-fade rounded-3xl border border-slate-200 bg-white p-6 lg:p-8">

            <div>
                <h2 class="text-lg font-bold text-slate-950">
                    Scenario Impact
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Compare your current capability profile with the simulated scenario.
                </p>
            </div>


            <div class="mt-8">
                <canvas id="scenario-chart"></canvas>
            </div>


        </section>

    </div>



</div>


<style>
    :root {
        --wis-accent-400: #facc15;
        --wis-accent-500: #eab308;
        --wis-accent-600: #ca8a04;
        --wis-positive: #10b981;
        --wis-positive-bg: #ecfdf5;
        --wis-negative: #f43f5e;
        --wis-negative-bg: #fff1f2;
    }

    .wis-num { font-variant-numeric: tabular-nums; }

    .wis-card {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 1.5rem;
        transition: border-color .25s ease, box-shadow .25s ease;
    }

    .wis-card-dark {
        border-radius: 1rem;
        background: #0b1220;
        padding: 1.5rem;
    }

    .wis-mesh {
        position: absolute;
        inset: -40%;
        background:
            radial-gradient(circle at 20% 20%, rgba(234,179,8,.20), transparent 55%),
            radial-gradient(circle at 80% 70%, rgba(250,204,21,.14), transparent 55%);
        animation: wis-drift 14s ease-in-out infinite alternate;
    }

    @keyframes wis-drift {
        from { transform: translate3d(0,0,0) scale(1); }
        to   { transform: translate3d(2%, -2%, 0) scale(1.05); }
    }

    .wis-dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 999px;
        background: var(--wis-accent-400);
        box-shadow: 0 0 0 3px rgba(250,204,21,.18);
    }

    .wis-track {
        height: 6px;
        width: 100%;
        border-radius: 999px;
        background: #f1f5f9;
        overflow: hidden;
    }

    .wis-track-fill {
        height: 100%;
        border-radius: 999px;
        transition: width .35s cubic-bezier(.4,0,.2,1);
    }

    .wis-weight-track {
        height: 4px;
        width: 72px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .wis-weight-fill {
        height: 100%;
        border-radius: 999px;
        background: #cbd5e1;
    }

    .wis-impact-neutral { color: inherit; }
    .wis-impact-neutral #scenario-change { color: #0f172a; }

    #impact-card.wis-impact-positive {
        border-color: rgba(16,185,129,.35);
        background: var(--wis-positive-bg);
    }
    #impact-card.wis-impact-positive #scenario-change,
    #impact-card.wis-impact-positive #impact-arrow { color: var(--wis-positive); }

    #impact-card.wis-impact-negative {
        border-color: rgba(244,63,94,.30);
        background: var(--wis-negative-bg);
    }
    #impact-card.wis-impact-negative #scenario-change,
    #impact-card.wis-impact-negative #impact-arrow { color: var(--wis-negative); }
    #impact-card.wis-impact-negative #impact-arrow { transform: rotate(180deg); }

    #impact-arrow.wis-visible { opacity: 1; }

    .wis-delta {
        min-width: 1px;
        font-size: 11px;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        padding: 2px 6px;
        border-radius: 999px;
        opacity: 0;
        transform: translateY(2px);
        transition: opacity .2s ease, transform .2s ease;
    }
    .wis-delta.wis-visible { opacity: 1; transform: translateY(0); }
    .wis-delta.wis-up { color: var(--wis-positive); background: var(--wis-positive-bg); }
    .wis-delta.wis-down { color: var(--wis-negative); background: var(--wis-negative-bg); }

    .wis-dim {
        border-radius: .875rem;
        padding: .5rem .5rem .25rem;
        margin: -.5rem -.5rem -.25rem;
        transition: background-color .2s ease;
    }
    .wis-dim.wis-active { background: #fafaf9; }

    /* Custom range slider */
    .wis-slider {
        appearance: none;
        -webkit-appearance: none;
        background: linear-gradient(to right, var(--wis-accent-500) var(--wis-fill, 0%), #e2e8f0 var(--wis-fill, 0%));
        border-radius: 999px;
        outline: none;
    }
    .wis-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #fff;
        border: 3px solid var(--wis-accent-500);
        box-shadow: 0 1px 3px rgba(15,23,42,.25);
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .wis-slider::-webkit-slider-thumb:hover { transform: scale(1.12); }
    .wis-slider::-webkit-slider-thumb:active {
        box-shadow: 0 0 0 6px rgba(234,179,8,.18);
    }
    .wis-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #fff;
        border: 3px solid var(--wis-accent-500);
        box-shadow: 0 1px 3px rgba(15,23,42,.25);
        cursor: pointer;
    }
    .wis-slider::-moz-range-track {
        background: transparent;
    }
    .wis-slider:focus-visible::-webkit-slider-thumb {
        box-shadow: 0 0 0 4px rgba(234,179,8,.35);
    }

    .wis-btn-primary {
        border-radius: .75rem;
        background: #0f172a;
        color: #fff;
        padding: .5rem 1rem;
        font-size: .875rem;
        font-weight: 600;
        transition: background-color .2s ease;
    }
    .wis-btn-primary:hover { background: #1e293b; }

    .wis-btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        border-radius: .75rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        padding: .5rem .875rem;
        font-size: .875rem;
        font-weight: 600;
        transition: background-color .2s ease, border-color .2s ease;
    }
    .wis-btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; }

    .wis-fade {
        animation: wis-fade-up .5s ease both;
    }
    .wis-fade:nth-child(1) { animation-delay: 0ms; }
    .wis-fade:nth-child(2) { animation-delay: 60ms; }
    .wis-fade:nth-child(3) { animation-delay: 120ms; }

    @keyframes wis-fade-up {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (prefers-reduced-motion: reduce) {
        .wis-fade, .wis-mesh, .wis-track-fill, .wis-delta { animation: none !important; transition: none !important; }
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const currentCompanyScore =
        {{ (float) $assessment->company_score }};

    const dimensionElements =
        Array.from(document.querySelectorAll('.simulator-dimension'));

    const sliders =
        Array.from(document.querySelectorAll('.dimension-slider'));

    const deltaElements =
        Array.from(document.querySelectorAll('.dimension-delta'));

    const scenarioScoreElement =
        document.getElementById('scenario-score');

    const scenarioChangeElement =
        document.getElementById('scenario-change');


    const scenarioTrackFill =
        document.getElementById('scenario-track-fill');

    const impactCard =
        document.getElementById('impact-card');

    const impactArrow =
        document.getElementById('impact-arrow');


    const resetButton =
        document.getElementById('reset-simulator');

    const optimisticButton =
        document.getElementById('preset-optimistic');

    const conservativeButton =
        document.getElementById('preset-conservative');

    const reduceMotion =
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;


    /*
     * Capability names.
     */
    const labels = @json(
        $dimensions->pluck('name')->values()
    );


    /*
     * Original scores remain fixed.
     */
    const currentScores = @json(
        $dimensions->pluck('current_score')->values()
    ).map(Number);


    /*
     * Scenario starts at current capability scores.
     */
    const scenarioScores = [...currentScores];


    /*
     * Each capability's share of the organization's
     * weighted assessment score.
     */
    const weights = @json(
        $dimensions->pluck('weight_percentage')->values()
    ).map(Number);


    /*
     * Radar chart
     */
    const context =
        document.getElementById('scenario-chart').getContext('2d');


    const scenarioChart = new Chart(context, {

        type: 'radar',

        data: {

            labels: labels,

            datasets: [
                {
                    label: 'Current',
                    data: currentScores,
                    borderColor: '#94a3b8',
                    backgroundColor: 'rgba(148, 163, 184, 0.08)',
                    borderWidth: 2,
                    pointRadius: 2
                },
                {
                    label: 'Scenario',
                    data: scenarioScores,
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234, 179, 8, 0.12)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#eab308'
                }
            ]
        },

        options: {

            responsive: true,

            maintainAspectRatio: true,

            animation: {
                duration: reduceMotion ? 0 : 300
            },

            scales: {
                r: {
                    min: 0,
                    max: 100,

                    ticks: {
                        stepSize: 20,
                        display: false
                    },

                    grid: {
                        color: '#e2e8f0'
                    },

                    angleLines: {
                        color: '#e2e8f0'
                    },

                    pointLabels: {
                        color: '#475569',
                        font: {
                            size: 11,
                            weight: '600'
                        }
                    }
                }
            },

            plugins: {
                legend: {
                    position: 'bottom',

                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });


    /*
     * Calculate scenario score.
     *
     * Each dimension contributes according to the
     * combined weight of its underlying questions.
     */
    function calculateScenarioScore() {

        let score = 0;

        scenarioScores.forEach(function (dimensionScore, index) {

            score += dimensionScore * (weights[index] / 100);

        });

        return score;
    }


    /*
     * Small helper: animate a number element from its
     * current displayed value to a target value.
     */
    function animateNumber(element, from, to) {

        if (reduceMotion || Math.abs(to - from) < 0.05) {
            element.textContent = to.toFixed(1);
            return;
        }

        const duration = 250;
        const start = performance.now();

        function tick(now) {

            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = from + (to - from) * eased;

            element.textContent = value.toFixed(1);

            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        }

        requestAnimationFrame(tick);
    }


    function updateSimulator() {

        const previousScore =
            parseFloat(scenarioScoreElement.textContent) || currentCompanyScore;

        const scenarioScore = calculateScenarioScore();

        let difference =
            scenarioScore - currentCompanyScore;

        if (Math.abs(difference) < 0.05) {
            difference = 0;
        }

        animateNumber(scenarioScoreElement, previousScore, scenarioScore);

        scenarioTrackFill.style.width =
            Math.max(0, Math.min(100, scenarioScore)) + '%';


        scenarioChangeElement.textContent =
            (difference >= 0 ? '+' : '') +
            difference.toFixed(1);


        /*
         * Impact card state + arrow.
         */
        impactCard.classList.remove(
            'wis-impact-positive',
            'wis-impact-negative',
            'wis-impact-neutral'
        );

        impactArrow.classList.remove('wis-visible');

        if (difference > 0) {
            impactCard.classList.add('wis-impact-positive');
            impactArrow.classList.add('wis-visible');
        } else if (difference < 0) {
            impactCard.classList.add('wis-impact-negative');
            impactArrow.classList.add('wis-visible');
        } else {
            impactCard.classList.add('wis-impact-neutral');
        }



        /*
         * Per-dimension delta chips.
         */
        deltaElements.forEach(function (chip, index) {

            const dimDifference = scenarioScores[index] - currentScores[index];

            chip.classList.remove('wis-visible', 'wis-up', 'wis-down');

            if (Math.abs(dimDifference) < 0.5) {
                chip.textContent = '';
                return;
            }

            chip.textContent =
                (dimDifference > 0 ? '+' : '') + dimDifference.toFixed(0);

            chip.classList.add('wis-visible', dimDifference > 0 ? 'wis-up' : 'wis-down');

            dimensionElements[index].classList.add('wis-active');
        });


        scenarioChart.data.datasets[1].data =
            [...scenarioScores];

        scenarioChart.update('none');
    }


    /*
     * Keep each slider's fill gradient in sync with its value.
     */
    function paintSliderFill(slider) {
        slider.style.setProperty('--wis-fill', slider.value + '%');
    }


    /*
     * LIVE slider updates.
     */
    sliders.forEach(function (slider, index) {

        paintSliderFill(slider);

        slider.addEventListener('input', function () {

            const value = Number(this.value);

            scenarioScores[index] = value;

            dimensionElements[index]
                .querySelector('.dimension-value')
                .textContent = value.toFixed(1);

            paintSliderFill(this);

            if (Math.abs(value - currentScores[index]) < 0.5) {
                dimensionElements[index].classList.remove('wis-active');
            }

            updateSimulator();

        });

    });


    /*
     * Reset everything to actual assessment values.
     */
    function resetToBaseline() {

        sliders.forEach(function (slider, index) {

            slider.value =
    currentScores[index];

            scenarioScores[index] =
                currentScores[index];

            dimensionElements[index]
                .querySelector('.dimension-value')
                .textContent =
                    currentScores[index].toFixed(1);

            dimensionElements[index].classList.remove('wis-active');

            paintSliderFill(slider);

        });

        updateSimulator();
    }

    resetButton.addEventListener('click', resetToBaseline);


    /*
     * Quick presets: nudge every capability up or down within
     * bounds, then let the normal update path do the rest.
     */
    function applyPreset(delta) {

        sliders.forEach(function (slider, index) {

            const target = Math.max(
                0,
                Math.min(100, Math.round(currentScores[index] + delta))
            );

            slider.value = target;
            scenarioScores[index] = target;

            dimensionElements[index]
                .querySelector('.dimension-value')
                .textContent = target.toFixed(1);

            if (Math.abs(target - currentScores[index]) >= 0.5) {
                dimensionElements[index].classList.add('wis-active');
            }

            paintSliderFill(slider);

        });

        updateSimulator();
    }

    optimisticButton.addEventListener('click', function () { applyPreset(15); });
    conservativeButton.addEventListener('click', function () { applyPreset(-15); });


    updateSimulator();

});
</script>

@endsection