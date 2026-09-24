<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    public function company()
{
    return $this->belongsTo(Company::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

protected $fillable = [
    'company_id',
    'user_id',
    'title',
    'status',
    'company_score',
    'country_ai_score',
    'country_ai_year',
    'combined_score',

    // Consultant review
    'consultant_notes',
    'review_status',
    'reviewed_by',
    'reviewed_at',
    'ai_executive_summary',
    'ai_roadmap',
'roadmap_inputs',
'roadmap_generated_at',
'engagement_type',
'transformation_status',
'payment_status',
'paid_at',
'framework_version',
'stripe_checkout_session_id',
'assigned_consultant_id',
'completed_at'
];

public function recommendations()
{
    return $this->hasMany(AssessmentRecommendation::class);
}
protected $casts = [
    'reviewed_at' => 'datetime',
        'completed_at' => 'datetime',

];

public function roadmapPreference()
{
    return $this->hasOne(RoadmapPreference::class);
}
public function transformationRoadmap()
{
    return $this->hasOne(TransformationRoadmap::class);
}
public function assignedConsultant()
{
    return $this->belongsTo(
        User::class,
        'assigned_consultant_id'
    );
}
}
