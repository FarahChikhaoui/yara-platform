<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadmapPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'target_maturity',
        'timeframe',
        'budget_level',
        'strategic_priorities',
        'constraints',
    ];

    protected $casts = [
        'strategic_priorities' => 'array',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}