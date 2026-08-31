@extends('layouts.yara')

@section('content')

{{-- Floating back to assessment results --}}
<a
    href="{{ route('assessment.results', $assessment) }}"
    aria-label="Back to assessment results"
    title="Back to assessment results"
    class="fixed left-5 top-1/2 z-50 flex h-12 w-12 -translate-y-1/2
           items-center justify-center rounded-full border border-slate-200
           bg-white text-slate-500 shadow-lg transition-all duration-200
           hover:-translate-x-0.5 hover:text-slate-950 hover:shadow-xl"
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

<div class="min-h-[75vh] bg-slate-50 px-4 py-12 sm:py-16">

    {{-- Ambient background accents --}}
    <div class="pointer-events-none absolute -top-24 left-1/2 h-72 w-[36rem] -translate-x-1/2 rounded-full bg-yellow-300/10 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-0 opacity-[0.4]" style="background-image: radial-gradient(circle at 1px 1px, rgba(15,23,42,0.05) 1px, transparent 0); background-size: 26px 26px;"></div>

    <div class="relative mx-auto max-w-5xl">

        {{-- Page heading --}}
        <div class="mb-8">

            <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                Transformation Roadmap
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
                Complete your request
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Your transformation brief has been saved.
                Complete checkout to submit your request for expert review.
            </p>

        </div>


        <div class="grid gap-6 lg:grid-cols-[1fr_380px]">

            {{-- =====================================================
                 TRANSFORMATION BRIEF
            ====================================================== --}}
            <div class="rounded-3xl border border-slate-200/70 bg-white p-7 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_40px_-28px_rgba(15,23,42,0.25)]">

                <div class="flex items-center justify-between border-b border-slate-100 pb-5">

                    <div>
                        <p class="text-sm font-bold text-slate-950">
                            Transformation brief
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Assessment #{{ $assessment->id }}
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-bold text-green-700 ring-1 ring-inset ring-green-200/70">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        Ready
                    </span>

                </div>


                <div class="mt-6 grid gap-5 sm:grid-cols-2">

                    {{-- Target maturity --}}
                    <div class="group rounded-2xl border border-transparent bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-yellow-200/70 hover:bg-yellow-50/40 hover:shadow-sm">

                        <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
<svg
    class="h-3.5 w-3.5 text-slate-400"
    fill="none"
    stroke="currentColor"
    viewBox="0 0 24 24"
    stroke-width="2"
>
    <circle cx="12" cy="12" r="9"/>
    <circle cx="12" cy="12" r="5"/>
    <circle cx="12" cy="12" r="1"/>
</svg>                            Target maturity
                        </p>

                        <p class="mt-2 font-bold text-slate-950">
                            {{ $preference->target_maturity }}
                        </p>

                    </div>


                    {{-- Timeline --}}
                    <div class="group rounded-2xl border border-transparent bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-yellow-200/70 hover:bg-yellow-50/40 hover:shadow-sm">

                        <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Timeline
                        </p>

                        <p class="mt-2 font-bold text-slate-950">
                            {{ $preference->timeframe }}
                        </p>

                    </div>


                    {{-- Investment capacity --}}
                    <div class="group rounded-2xl border border-transparent bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-yellow-200/70 hover:bg-yellow-50/40 hover:shadow-sm">

                        <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5-1.343 1.5-3 1.5m0-6c1.11 0 2.08.402 2.599 1M12 8V6.5M12 15v1.5m0-1.5c-1.11 0-2.08-.402-2.599-1M12 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                            Investment capacity
                        </p>

                        <p class="mt-2 font-bold text-slate-950">
                            {{ $preference->budget_level }}
                        </p>

                    </div>


                    {{-- Assessment --}}
                    <div class="group rounded-2xl border border-transparent bg-slate-50 p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-green-200/70 hover:bg-green-50/40 hover:shadow-sm">

                        <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Assessment
                        </p>

                        <p class="mt-2 font-bold text-slate-950">
                            Completed
                        </p>

                    </div>

                </div>


                {{-- Strategic priorities --}}
                @if(!empty($preference->strategic_priorities))

                    <div class="mt-6">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Strategic priorities
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">

                            @foreach($preference->strategic_priorities as $priority)

                                <span class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-yellow-300 hover:text-yellow-700">
                                    {{ $priority }}
                                </span>

                            @endforeach

                        </div>

                    </div>

                @endif


                {{-- Included --}}
                <div class="mt-8 border-t border-slate-100 pt-6">

                    <p class="text-sm font-bold text-slate-950">
                        What's included
                    </p>

                    <div class="mt-4 space-y-3">

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-[11px] font-bold text-yellow-700">✓</span>

                            <p class="text-sm leading-6 text-slate-600">
                                Expert review of your AI readiness assessment
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-[11px] font-bold text-yellow-700">✓</span>

                            <p class="text-sm leading-6 text-slate-600">
                                Prioritized transformation initiatives
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-[11px] font-bold text-yellow-700">✓</span>

                            <p class="text-sm leading-6 text-slate-600">
                                Recommended implementation timeline
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-[11px] font-bold text-yellow-700">✓</span>

                            <p class="text-sm leading-6 text-slate-600">
                                AI-assisted roadmap validated by a Yellomind consultant
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 ORDER SUMMARY
            ====================================================== --}}
            <div>

                <div class="relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white p-6 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_24px_48px_-28px_rgba(15,23,42,0.3)] lg:sticky lg:top-6">

                    <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-yellow-300/10 blur-3xl"></div>

                    <p class="relative text-xs font-bold uppercase tracking-[0.15em] text-slate-400">
                        Order summary
                    </p>


                    <div class="relative mt-5 border-b border-slate-100 pb-5">

                        <p class="font-bold text-slate-950">
                            Transformation Roadmap
                        </p>

                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Expert-reviewed AI transformation plan
                        </p>

                    </div>


                    <div class="relative flex items-center justify-between border-b border-slate-100 py-5">

                        <span class="text-sm font-semibold text-slate-600">
                            Service
                        </span>

                        <span class="text-sm font-bold text-slate-950">
                            €199.00
                        </span>

                    </div>


                    <div class="relative flex items-end justify-between py-5">

                        <div>
                            <p class="text-sm font-semibold text-slate-500">
                                Total
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Test checkout
                            </p>
                        </div>

                        <p class="text-2xl font-bold tracking-tight text-slate-950">
                            €199.00
                        </p>

                    </div>


                    {{-- STRIPE --}}
                    <form
                        method="POST"
                        action="{{ route('transformation.payment.checkout', $assessment) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="group relative flex w-full items-center justify-between overflow-hidden rounded-xl bg-gradient-to-r from-slate-950 to-slate-800 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-900/25 active:translate-y-0"
                        >
                            <span>Proceed to Secure Checkout</span>

                            <span class="transition-transform duration-200 group-hover:translate-x-1">
                                →
                            </span>

                        </button>

                    </form>


                    <div class="relative mt-4 flex items-center justify-center gap-2 text-xs text-slate-400">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>

                        <span>
                            Secure checkout powered by Stripe
                        </span>

                    </div>


                    

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
