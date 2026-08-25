<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Dimension;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
             $questions = Question::with([
    'dimension',
    'answerOptions' => function ($q) {
        $q->orderBy('score');
    }
])
->orderBy('dimension_id')
->orderBy('id')
->get();

        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $dimensions = Dimension::orderBy('code')->get();

        return view('admin.questions.create', compact('dimensions'));
    }

    public function store(Request $request)
{
    $request->validate([
        'dimension_id' => 'required|exists:dimensions,id',
        'question_text' => 'required',
        'order' => 'nullable|integer',
        'weight' => 'nullable|numeric',
        'help_text' => 'nullable',
        'is_active' => 'nullable',
        'options' => 'required|array|size:4',
        'options.*' => 'required|string',
    ]);

    $question = Question::create([
        'dimension_id' => $request->dimension_id,
        'question_text' => $request->question_text,
        'order' => $request->order ?? 1,
        'weight' => $request->weight ?? 1,
        'help_text' => $request->help_text,
        'is_active' => $request->has('is_active'),
    ]);

    foreach ($request->options as $index => $optionText) {
        $question->answerOptions()->create([
            'label' => $optionText,
            'score' => $index + 1,
            'order' => $index + 1,
        ]);
    }

    return redirect('/admin/questions');
}
   public function edit(Question $question)
{
    $question->load('answerOptions');

    $dimensions = Dimension::orderBy('code')->get();

    return view('admin.questions.edit', compact('question', 'dimensions'));
}
    public function update(Request $request, Question $question)
{
    $request->validate([
        'dimension_id' => 'required|exists:dimensions,id',
        'question_text' => 'required',
        'order' => 'nullable|integer',
        'weight' => 'nullable|numeric',
        'help_text' => 'nullable',
        'is_active' => 'nullable',
        'options' => 'required|array|size:4',
        'options.*' => 'required|string',
    ]);
   // dd($request->all());
    $question->update([
        'dimension_id' => $request->dimension_id,
        'question_text' => $request->question_text,
        'order' => $request->order ?? 1,
        'weight' => $request->weight ?? 1,
        'help_text' => $request->help_text,
        'is_active' => $request->has('is_active'),
    ]);
foreach ($request->options as $index => $optionText) {
    $question->answerOptions()->updateOrCreate(
        ['order' => $index + 1],
        [
            'label' => $optionText,
            'score' => $index + 1,
        ]
    );

}

    return redirect('/admin/questions');
}

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect('/admin/questions');
    }

    public function togglePulse(Question $question)
{
    $question->update([
        'include_in_pulse' => !$question->include_in_pulse,
    ]);

    return response()->json([
        'success' => true,
        'include_in_pulse' => $question->include_in_pulse,
    ]);
}
}