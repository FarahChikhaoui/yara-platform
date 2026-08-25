@extends('layouts.yara')

@section('content')

<div class="min-h-[75vh] bg-slate-50 px-4 py-16">

    <div class="mx-auto max-w-2xl">

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            <div class="h-1.5 bg-yellow-400"></div>

            <div class="px-6 py-12 text-center sm:px-10">

                {{-- Success icon --}}
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-yellow-400 text-xl font-bold text-slate-950">
                        ✓
                    </div>
                </div>

                <p class="mt-6 text-xs font-bold uppercase tracking-[0.18em] text-yellow-600">
                    Transformation Roadmap
                </p>

                <h1 class="mt-3 text-3xl font-bold text-slate-950">
                    Submitted for expert review
                </h1>

                <p class="mx-auto mt-5 max-w-xl leading-7 text-slate-600">
                    Your transformation request has been successfully
                    submitted to Yellomind.
                </p>

                <p class="mx-auto mt-3 max-w-xl leading-7 text-slate-600">
                    A consultant will review your AI readiness results,
                    target maturity, timeline, investment capacity and
                    strategic priorities before preparing your
                    transformation roadmap.
                </p>

                {{-- Status --}}
                <div class="mx-auto mt-8 max-w-lg rounded-2xl border border-yellow-200 bg-yellow-50 p-5">

                    <p class="text-sm font-bold text-slate-900">
                        What happens next?
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Your request is currently awaiting consultant review.
                        You can track its progress from your dashboard.
                    </p>

                </div>

                {{-- Dashboard --}}
                <a
                    href="{{ url('/dashboard') }}"
                    class="mt-8 inline-flex items-center justify-center gap-2 rounded-xl bg-slate-950 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-slate-800"
                >
                    Back to Dashboard

                    <span>→</span>
                </a>

            </div>

        </div>

    </div>

</div>

@endsection