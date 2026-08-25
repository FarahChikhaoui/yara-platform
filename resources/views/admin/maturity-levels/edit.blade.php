@extends('layouts.admin')

@section('content')

<div class="max-w-4xl">

    <div class="mb-8">
        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
            Admin Management
        </p>

        <h1 class="text-4xl font-bold text-slate-950 mt-1">
            Edit Maturity Level
        </h1>

        <p class="mt-2 text-slate-500">
            Update this maturity level and score range.
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

        <form action="{{ route('maturity-levels.update', $maturityLevel) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Level Number
                    </label>

                    <input type="number" 
                           name="level"
                           value="{{ $maturityLevel->level }}"
                           required
                           class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ $maturityLevel->name }}"
                           required
                           class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                </div>

            </div>

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Minimum Score
                    </label>

                    <input type="number" step="0.01"
                           name="min_score"
                           value="{{ $maturityLevel->min_score }}"
                           required
                           class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Maximum Score
                    </label>

                    <input type="number" step="0.01"
                           name="max_score"
                           value="{{ $maturityLevel->max_score }}"
                           required
                           class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                </div>

            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Description
                </label>

                <textarea name="description"
                          rows="4"
                          class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">{{ $maturityLevel->description }}</textarea>
            </div>

            <div class="flex gap-4 pt-2">

                <button type="submit"
                        class="bg-slate-950 text-white px-6 py-3 rounded-xl font-semibold hover:bg-slate-800 transition">
                    Update Maturity Level
                </button>

                <a href="{{ route('maturity-levels.index') }}"
                   class="px-6 py-3 rounded-xl border border-slate-300 font-semibold hover:bg-slate-50">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection