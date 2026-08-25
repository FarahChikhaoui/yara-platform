<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\AnswerOption;
use Illuminate\Http\Request;

class AnswerOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answerOptions = AnswerOption::with('question.dimension')
            ->orderBy('question_id')
            ->orderBy('order')
            ->get();

        return view('admin.answer-options.index', compact('answerOptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questions = Question::with('dimension')
            ->orderBy('dimension_id')
            ->orderBy('order')
            ->get();

        return view('admin.answer-options.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
        'question_id' => 'required|exists:questions,id',
        'options' => 'required|array',
        'options.*.label' => 'required|string',
        'options.*.score' => 'required|integer',
        'options.*.order' => 'nullable|integer',
    ]);

    foreach ($request->options as $option) {
        AnswerOption::create([
            'question_id' => $request->question_id,
            'label' => $option['label'],
            'score' => $option['score'],
            'order' => $option['order'] ?? 1,
        ]);
    }

    return redirect('/admin/answer-options');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AnswerOption $answerOption)
    {
         $question = $answerOption->question;

    $options = AnswerOption::where('question_id', $question->id)
        ->orderBy('order')
        ->get();

    return view('admin.answer-options.edit', compact('question', 'options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnswerOption $answerOption)
    {
        $question = $answerOption->question;

    $request->validate([
        'options' => 'required|array',
        'options.*.label' => 'required|string',
        'options.*.score' => 'required|integer',
        'options.*.order' => 'nullable|integer',
    ]);

    foreach ($request->options as $optionData) {
        if (!empty($optionData['id'])) {
            AnswerOption::where('id', $optionData['id'])->update([
                'label' => $optionData['label'],
                'score' => $optionData['score'],
                'order' => $optionData['order'] ?? 1,
            ]);
        } else {
            AnswerOption::create([
                'question_id' => $question->id,
                'label' => $optionData['label'],
                'score' => $optionData['score'],
                'order' => $optionData['order'] ?? 1,
            ]);
        }
    }

    return redirect('/admin/answer-options');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $answerOption->delete();

        return redirect('/admin/answer-options');
    }
}
