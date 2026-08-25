@extends('layouts.admin')

@section('content')

<div class="max-w-5xl">

    <div class="mb-8">
        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
            Consulting Knowledge Base
        </p>

        <h1 class="mt-1 text-4xl font-bold text-slate-950">
            Edit Recommendation Rule
        </h1>

        <p class="mt-2 text-slate-500">
            Update the trigger, consulting action and delivery information.
        </p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <form
            action="{{ route('recommendation-rules.update', $recommendationRule) }}"
            method="POST"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            @include('admin.recommendation-rules.partials.form', [
                'recommendationRule' => $recommendationRule
            ])

            <div class="flex gap-4 border-t border-slate-200 pt-6">

                <button
                    type="submit"
                    class="rounded-xl bg-slate-950 px-6 py-3 font-semibold text-white transition hover:bg-slate-800"
                >
                    Update Rule
                </button>

                <a
                    href="{{ route('recommendation-rules.index') }}"
                    class="rounded-xl border border-slate-300 px-6 py-3 font-semibold transition hover:bg-slate-50"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection