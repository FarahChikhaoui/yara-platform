<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulseCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'email',
        'status',
        'overall_score',
        'readiness_signal',
        'strongest_dimension_id',
        'weakest_dimension_id',
        'converted_user_id',
        'completed_at',
    ];

    protected $casts = [
        'overall_score' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function responses()
    {
        return $this->hasMany(PulseCheckResponse::class);
    }

    public function strongestDimension()
    {
        return $this->belongsTo(Dimension::class, 'strongest_dimension_id');
    }

    public function weakestDimension()
    {
        return $this->belongsTo(Dimension::class, 'weakest_dimension_id');
    }

    public function convertedUser()
    {
        return $this->belongsTo(User::class, 'converted_user_id');
    }
}