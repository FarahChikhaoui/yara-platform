<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadmapInitiative extends Model
{
    use HasFactory;

    protected $fillable = [
    'transformation_roadmap_id',

    'title',
    'description',
    'business_rationale',

    'recommended_actions',
    'expected_outcome',
    'success_metrics',
    'dependencies',

    'dimension',
    'priority',
    'phase',
    'effort',
    'impact',

    'investment',
    'standard_reference',

    'consultant_guidance',
    'sort_order',
];

    /*
     * Roadmap this initiative belongs to.
     */
    public function transformationRoadmap()
    {
        return $this->belongsTo(
            TransformationRoadmap::class,
            'transformation_roadmap_id'
        );
    }

    protected $casts = [
    'recommended_actions' => 'array',
    'success_metrics' => 'array',
    'dependencies' => 'array',
];
}