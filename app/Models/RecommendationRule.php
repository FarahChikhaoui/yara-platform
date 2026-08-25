<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimension_id',
        'question_id',
        'max_answer_score',
        'action_title',
        'action_description',
        'business_rationale',
        'category',
'expected_outcomes',
        'business_impact',
        'effort',
        'timeline_min_months',
        'timeline_max_months',
        'investment_min',
        'investment_max',
        'currency',
        'priority_weight',
        'dependency_order',
        'standard_reference',
        'is_active',
    ];

    protected $casts = [
        'max_answer_score' => 'integer',
        'timeline_min_months' => 'integer',
        'timeline_max_months' => 'integer',
        'investment_min' => 'decimal:2',
        'investment_max' => 'decimal:2',
        'priority_weight' => 'integer',
        'dependency_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function generatedRecommendations()
{
    return $this->hasMany(AssessmentRecommendation::class);
} 
}