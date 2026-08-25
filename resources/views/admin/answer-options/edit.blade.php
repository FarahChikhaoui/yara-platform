@extends('layouts.admin')

@section('content')

<div class="max-w-5xl">

    <div class="mb-8">
        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
            Admin Management
        </p>

        <h1 class="text-4xl font-bold text-slate-950 mt-1">
            Edit Answer Options
        </h1>

        <p class="mt-2 text-slate-500">
            Update all answer options for this question.
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

        <div class="mb-6 rounded-2xl bg-slate-50 border border-slate-200 p-5">
            <p class="text-xs font-bold uppercase text-slate-500 mb-2">
                Question
            </p>

            <p class="font-semibold text-slate-950">
                {{ $question->question_text }}
            </p>
        </div>

        <form action="{{ route('answer-options.update', $options->first()) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="border border-slate-200 rounded-2xl overflow-hidden">

                <div class="grid grid-cols-12 gap-4 bg-slate-50 px-4 py-3 text-xs font-bold uppercase text-slate-500">
                    <div class="col-span-7">Answer Label</div>
                    <div class="col-span-2">Score</div>
                    <div class="col-span-2">Order</div>
                    <div class="col-span-1"></div>
                </div>

                <div id="options-container" class="divide-y divide-slate-100">

                    @foreach($options as $index => $option)
                        <div class="option-row grid grid-cols-12 gap-4 px-4 py-4 items-center">

                            <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">

                            <div class="col-span-7">
                                <input type="text"
                                       name="options[{{ $index }}][label]"
                                       value="{{ $option->label }}"
                                       required
                                       class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                            </div>

                            <div class="col-span-2">
                                <input type="number"
                                       name="options[{{ $index }}][score]"
                                       value="{{ $option->score }}"
                                       required
                                       class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                            </div>

                            <div class="col-span-2">
                                <input type="number"
                                       name="options[{{ $index }}][order]"
                                       value="{{ $option->order }}"
                                       class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                            </div>

                            <div class="col-span-1 flex justify-end">
                                <button type="button"
                                        class="remove-option w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-red-600 hover:bg-red-50">
                                    ×
                                </button>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

            <button type="button"
                    id="add-option"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-300 font-semibold hover:bg-slate-50">
                + Add another option
            </button>

            <div class="flex gap-4 pt-2">
                <button type="submit"
                        class="bg-slate-950 text-white px-6 py-3 rounded-xl font-semibold hover:bg-slate-800 transition">
                    Update Answer Options
                </button>

                <a href="{{ route('answer-options.index') }}"
                   class="px-6 py-3 rounded-xl border border-slate-300 font-semibold hover:bg-slate-50">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<script>
    let optionIndex = {{ $options->count() }};

    document.getElementById('add-option').addEventListener('click', function () {
        const container = document.getElementById('options-container');

        const row = document.createElement('div');
        row.className = 'option-row grid grid-cols-12 gap-4 px-4 py-4 items-center';

        row.innerHTML = `
            <input type="hidden" name="options[${optionIndex}][id]" value="">

            <div class="col-span-7">
                <input type="text"
                       name="options[${optionIndex}][label]"
                       required
                       class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
                       placeholder="Answer option label">
            </div>

            <div class="col-span-2">
                <input type="number"
                       name="options[${optionIndex}][score]"
                       required
                       value="${optionIndex}"
                       class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
            </div>

            <div class="col-span-2">
                <input type="number"
                       name="options[${optionIndex}][order]"
                       value="${optionIndex + 1}"
                       class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
            </div>

            <div class="col-span-1 flex justify-end">
                <button type="button"
                        class="remove-option w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-red-600 hover:bg-red-50">
                    ×
                </button>
            </div>
        `;

        container.appendChild(row);
        optionIndex++;
    });

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-option')) {
            const rows = document.querySelectorAll('.option-row');

            if (rows.length > 1) {
                event.target.closest('.option-row').remove();
            }
        }
    });
</script>

@endsection