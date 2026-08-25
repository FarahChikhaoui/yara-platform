<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaturityLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'name',
        'min_score',
        'max_score',
        'description',
    ];
}