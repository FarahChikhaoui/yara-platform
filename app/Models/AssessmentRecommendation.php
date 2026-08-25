<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'recommendation_rule_id',
        'dimension_id',
        'question_id',
        'triggered_answer_score',
        'calculated_priority',
        'priority_rank',
        'status',
        'action_title',
        'action_description',
        'business_rationale',
        'business_impact',
        'effort',
        'timeline_min_months',
        'timeline_max_months',
        'investment_min',
        'investment_max',
        'currency',
        'dependency_order',
        'standard_reference',
    ];

    protected $casts = [
        'triggered_answer_score' => 'integer',
        'calculated_priority' => 'decimal:2',
        'priority_rank' => 'integer',
        'timeline_min_months' => 'integer',
        'timeline_max_months' => 'integer',
        'investment_min' => 'decimal:2',
        'investment_max' => 'decimal:2',
        'dependency_order' => 'integer',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function recommendationRule()
    {
        return $this->belongsTo(RecommendationRule::class);
    }

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}