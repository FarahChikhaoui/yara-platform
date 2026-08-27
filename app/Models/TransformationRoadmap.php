<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransformationRoadmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'consultant_id',
        'status',
        'consultant_notes',
        'risks_dependencies',
        'generated_at',
        'finalized_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'finalized_at' => 'datetime',
    ];

    /*
     * The assessment this roadmap belongs to.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /*
     * Consultant responsible for the roadmap.
     */
    public function consultant()
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    /*
     * Individual roadmap initiatives.
     */
    public function initiatives()
    {
        return $this->hasMany(RoadmapInitiative::class)
            ->orderBy('sort_order');
    }
}