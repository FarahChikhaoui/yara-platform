<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    public function assessment()
{
    return $this->belongsTo(Assessment::class);
}

public function question()
{
    return $this->belongsTo(Question::class);
}

public function answerOption()
{
    return $this->belongsTo(AnswerOption::class);
}

protected $fillable = [
    'assessment_id',
    'question_id',
    'answer_option_id',
];

}
