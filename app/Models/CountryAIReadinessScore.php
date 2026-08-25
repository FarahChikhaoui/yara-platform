<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryAIReadinessScore extends Model
{
    use HasFactory;

    protected $table = 'country_ai_readiness_scores';

    protected $fillable = [
        'country',
        'year',
        'final_rank',
        'official_rank',
        'score',
        'score_type',
        'missing_values',
    ];
}