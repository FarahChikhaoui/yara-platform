@extends('layouts.admin')

@section('content')

<div class="max-w-5xl">

    <div class="mb-8">
        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
            Admin Management
        </p>

        <h1 class="text-4xl font-bold text-slate-950 mt-1">
            Create Answer Options
        </h1>

        <p class="mt-2 text-slate-500">
            Add multiple answer options and scores for one YARA question.
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

        <form action="{{ route('answer-options.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Question
                </label>

                <select name="question_id"
                        required
                        class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                    <option value="">Select question</option>

                    @foreach($questions as $question)
                        <option value="{{ $question->id }}">
                            {{ $question->dimension->code ?? 'N/A' }} — {{ $question->question_text }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="border border-slate-200 rounded-2xl overflow-hidden">

                <div class="grid grid-cols-12 gap-4 bg-slate-50 px-4 py-3 text-xs font-bold uppercase text-slate-500">
                    <div class="col-span-7">Answer Label</div>
                    <div class="col-span-2">Score</div>
                    <div class="col-span-2">Order</div>
                    <div class="col-span-1"></div>
                </div>

                <div id="options-container" class="divide-y divide-slate-100">

                    <div class="option-row grid grid-cols-12 gap-4 px-4 py-4 items-center">
                        <div class="col-span-7">
                            <input type="text"
                                   name="options[0][label]"
                                   required
                                   class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400"
                                   placeholder="Example: Not implemented">
                        </div>

                        <div class="col-span-2">
                            <input type="number"
                                   name="options[0][score]"
                                   required
                                   value="0"
                                   class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                        </div>

                        <div class="col-span-2">
                            <input type="number"
                                   name="options[0][order]"
                                   value="1"
                                   class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                        </div>

                        <div class="col-span-1 flex justify-end">
                            <button type="button"
                                    class="remove-option w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-red-600 hover:bg-red-50">
                                ×
                            </button>
                        </div>
                    </div>

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
                    Save Answer Options
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
    let optionIndex = 1;

    document.getElementById('add-option').addEventListener('click', function () {
        const container = document.getElementById('options-container');

        const row = document.createElement('div');
        row.className = 'option-row grid grid-cols-12 gap-4 px-4 py-4 items-center';

        row.innerHTML = `
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