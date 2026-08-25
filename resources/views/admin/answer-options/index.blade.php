@extends('layouts.admin')

@section('content')

<div class="flex flex-col" style="height: 100%;">

    <div class="flex items-center justify-between mb-6 flex-shrink-0">
        <div>
            <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
                Admin Management
            </p>

            <div class="flex items-center gap-4 mt-1">
                <h1 class="text-4xl font-bold text-slate-950">
                    Answer Options
                </h1>

                <a href="{{ route('answer-options.create') }}"
                   class="w-10 h-10 rounded-full bg-yellow-400 text-slate-950 flex items-center justify-center text-2xl font-medium hover:scale-105 transition">
                    +
                </a>
            </div>

            <p class="mt-2 text-slate-500">
                Manage answer choices and scores for each assessment question.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex-1">

        <div class="overflow-y-auto" style="max-height: calc(100vh - 280px);">

            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Dimension</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Question</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Option</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Score</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Order</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach($answerOptions as $option)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-5">
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $option->question->dimension->code ?? 'N/A' }}
                                </span>
                            </td>

                            <td class="px-6 py-5 text-slate-700 max-w-md">
                                {{ $option->question->question_text ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-5 font-bold text-slate-950">
                                {{ $option->label }}
                            </td>

                            <td class="px-6 py-5 font-semibold text-slate-800">
                                {{ $option->score }}
                            </td>

                            <td class="px-6 py-5 text-slate-600">
                                {{ $option->order }}
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('answer-options.edit', $option) }}"
                                       class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-yellow-600 hover:bg-yellow-50 transition">
                                        ✎
                                    </a>

                                    <form action="{{ route('answer-options.destroy', $option) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this answer option?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-red-600 hover:bg-red-50 transition">
                                            ⌫
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection