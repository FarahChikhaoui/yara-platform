@extends('layouts.admin')
@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
            Admin / Framework Management
        </p>

        <h1 class="text-4xl font-bold text-slate-950 mt-2">
            Create Dimension
        </h1>

        <p class="mt-2 text-slate-500">
            Add a new assessment dimension to the YARA framework.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

        <form action="{{ route('dimensions.store') }}" method="POST">

            @csrf
<div class="grid md:grid-cols-2 gap-6">

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Dimension Code
        </label>

        <input type="text"
               name="code"
               class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
               placeholder="Example: D1">
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Weight (%)
        </label>

        <input type="number"
               step="0.01"
               name="weight"
               class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
               placeholder="Example: 13">
    </div>

</div>
            <div class="space-y-6">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Dimension Name
                    </label>

                    <input type="text"
                           name="name"
                           class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
                           placeholder="Example: AI Governance">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Description
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
                              placeholder="Describe this dimension..."></textarea>
                </div>

                <div class="flex gap-4">

                    <button type="submit"
                            class="bg-slate-950 text-white px-6 py-3 rounded-xl font-semibold hover:bg-slate-800">
                        Save Dimension
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