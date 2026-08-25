<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerOption extends Model
{
    protected $fillable = [
        'question_id',
        'label',
        'score',
        'order',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}