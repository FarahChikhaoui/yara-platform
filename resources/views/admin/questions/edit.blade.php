@extends('layouts.admin')

@section('content')

<div class="max-w-4xl">

    <div class="mb-8">
        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
            Admin Management
        </p>

        <h1 class="text-4xl font-bold text-slate-950 mt-1">
            Edit Question
        </h1>

        <p class="mt-2 text-slate-500">
            Update this YARA assessment question and its answer options.
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

        <form action="{{ route('questions.update', $question) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Dimension
                </label>

                <select name="dimension_id"
                        required
                        class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                    @foreach($dimensions as $dimension)
                        <option value="{{ $dimension->id }}"
                            {{ $question->dimension_id == $dimension->id ? 'selected' : '' }}>
                            {{ $dimension->code }} — {{ $dimension->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Question Text
                </label>

                <textarea name="question_text"
                          rows="4"
                          required
                          class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">{{ $question->question_text }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Order
                    </label>

                    <input type="number"
                           name="order"
                           value="{{ $question->order }}"
                           class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Weight
                    </label>

                    <input type="number"
                           step="0.01"
                           name="weight"
                           value="{{ $question->weight }}"
                           class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Help Text
                </label>

                <textarea name="help_text"
                          rows="3"
                          class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">{{ $question->help_text }}</textarea>
            </div>

            <label class="flex items-center gap-3">
                <input type="checkbox"
                       name="is_active"
                       {{ $question->is_active ? 'checked' : '' }}
                       class="rounded border-slate-300 text-yellow-500 focus:ring-yellow-400">

                <span class="text-sm font-medium text-slate-700">
                    Active question
                </span>
            </label>

            <div class="border-t border-slate-200 pt-6">
                <h2 class="text-xl font-bold text-slate-950 mb-4">
                    Answer Options
                </h2>

                <div class="space-y-5">
                    @foreach([1, 2, 3, 4] as $i)
                        @php
                            $option = $question->answerOptions->where('order', $i)->first()
                                ?? $question->answerOptions->get($i - 1);
                        @endphp

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Option {{ chr(64 + $i) }} — Score {{ $i }}
                            </label>

                            <textarea name="options[]"
                                      rows="3"
                                      required
                                      class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">{{ old('options.'.($i - 1), $option?->label) }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit"
                        class="bg-slate-950 text-white px-6 py-3 rounded-xl font-semibold hover:bg-slate-800 transition">
                    Update Question
                </button>

                <a href="{{ route('questions.index') }}"
                   class="px-6 py-3 rounded-xl border border-slate-300 font-semibold hover:bg-slate-50">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection