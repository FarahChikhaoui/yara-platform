<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulseCheckResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'pulse_check_id',
        'question_id',
        'answer_option_id',
    ];

    public function pulseCheck()
    {
        return $this->belongsTo(PulseCheck::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answerOption()
    {
        return $this->belongsTo(AnswerOption::class);
    }
}