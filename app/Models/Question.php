<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dimension;
use App\Models\AnswerOption;


class Question extends Model
{
    use HasFactory;

    public function dimension()
{
    return $this->belongsTo(Dimension::class);
}

// public function answerOptions()
//{
   // return $this->hasMany(AnswerOption::class)->orderBy('order');
//}

public function answerOptions()
{
    return $this->hasMany(\App\Models\AnswerOption::class)->orderBy('order');
}

protected $fillable = [
    'dimension_id',
    'question_text',
    'order',
    'weight',
    'help_text',
    'is_active',
    'include_in_pulse',
'pulse_order',
];

public function recommendationRules()
{
    return $this->hasMany(RecommendationRule::class);
}

protected $casts = [
    'is_active' => 'boolean',
    'include_in_pulse' => 'boolean',
    'pulse_order' => 'integer',
];

}
