<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenchmarkDataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'source',
        'year',
        'etl_method',
        'is_active',
        'imported_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'imported_at' => 'datetime',
    ];

    public static function active()
    {
        return static::where('is_active', true)->first();
    }
}