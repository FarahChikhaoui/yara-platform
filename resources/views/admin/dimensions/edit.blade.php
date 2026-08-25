@extends('layouts.admin')
@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
            Admin / Framework Management
        </p>

        <h1 class="text-4xl font-bold text-slate-950 mt-2">
            Edit Dimension
        </h1>

        <p class="mt-2 text-slate-500">
            Update the dimension details used in the YARA assessment framework.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

        <form action="{{ route('dimensions.update', $dimension) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Dimension Code
                        </label>

                        <input type="text"
                               name="code"
                               value="{{ $dimension->code }}"
                               class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Weight (%)
                        </label>

                        <input type="number"
                               step="0.01"
                               name="weight"
                               value="{{ $dimension->weight }}"
                               class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Dimension Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ $dimension->name }}"
                           class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Description
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">{{ $dimension->description }}</textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                            class="bg-slate-950 text-white px-6 py-3 rounded-xl font-semibold hover:bg-slate-800">
                        Update Dimension
                    </button>

                    <a href="{{ route('dimensions.index') }}"
                       class="px-6 py-3 rounded-xl border border-slate-300 font-semibold">
                        Cancel
                    </a>
                </div>

            </div>

        </form>

    </div>

</div>

@endsection