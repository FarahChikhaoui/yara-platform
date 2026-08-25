@extends('layouts.yara')

@section('content')

<div class="min-h-[75vh] bg-slate-50 px-4 py-12 sm:py-16">

    <div class="mx-auto max-w-5xl">

        {{-- Page heading --}}
        <div class="mb-8">

            <p class="text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                Transformation Roadmap
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">
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
            <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">

                <div class="flex items-center justify-between border-b border-slate-100 pb-5">

                    <div>
                        <p class="text-sm font-bold text-slate-950">
                            Transformation brief
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Assessment #{{ $assessment->id }}
                        </p>
                    </div>

                    <span class="rounded-full bg-green-50 px-3 py-1.5 text-xs font-bold text-green-700">
                        Ready
                    </span>

                </div>


                <div class="mt-6 grid gap-5 sm:grid-cols-2">

                    {{-- Target maturity --}}
                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Target maturity
                        </p>

                        <p class="mt-2 font-bold text-slate-950">
                            {{ $preference->target_maturity }}
                        </p>

                    </div>


                    {{-- Timeline --}}
                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Timeline
                        </p>

                        <p class="mt-2 font-bold text-slate-950">
                            {{ $preference->timeframe }}
                        </p>

                    </div>


                    {{-- Investment capacity --}}
                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Investment capacity
                        </p>

                        <p class="mt-2 font-bold text-slate-950">
                            {{ $preference->budget_level }}
                        </p>

                    </div>


                    {{-- Assessment --}}
                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
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

                                <span class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600">
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
                            <span class="mt-0.5 text-yellow-500">✓</span>

                            <p class="text-sm leading-6 text-slate-600">
                                Expert review of your AI readiness assessment
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 text-yellow-500">✓</span>

                            <p class="text-sm leading-6 text-slate-600">
                                Prioritized transformation initiatives
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 text-yellow-500">✓</span>

                            <p class="text-sm leading-6 text-slate-600">
                                Recommended implementation timeline
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 text-yellow-500">✓</span>

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

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-6">

                    <p class="text-xs font-bold uppercase tracking-[0.15em] text-slate-400">
                        Order summary
                    </p>


                    <div class="mt-5 border-b border-slate-100 pb-5">

                        <p class="font-bold text-slate-950">
                            Transformation Roadmap
                        </p>

                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Expert-reviewed AI transformation plan
                        </p>

                    </div>


                    <div class="flex items-center justify-between border-b border-slate-100 py-5">

                        <span class="text-sm font-semibold text-slate-600">
                            Service
                        </span>

                        <span class="text-sm font-bold text-slate-950">
                            €199.00
                        </span>

                    </div>


                    <div class="flex items-end justify-between py-5">

                        <div>
                            <p class="text-sm font-semibold text-slate-500">
                                Total
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Test checkout
                            </p>
                        </div>

                        <p class="text-2xl font-bold text-slate-950">
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
                            class="group flex w-full items-center justify-between rounded-xl bg-slate-950 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-slate-800"
                        >
                            <span>Proceed to Secure Checkout</span>

                            <span class="transition-transform group-hover:translate-x-1">
                                →
                            </span>

                        </button>

                    </form>


                    <div class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-400">

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


                    {{-- Test mode notice --}}
                    <div class="mt-5 rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-3">

                        <p class="text-xs leading-5 text-yellow-800">
                            <span class="font-bold">Test mode:</span>
                            No real payment will be processed.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection