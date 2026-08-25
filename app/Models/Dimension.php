<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    use HasFactory;

    public function questions()
{
    return $this->hasMany(Question::class);
}

protected $fillable = [
    'name',
    'description',
    'code',
        'weight',

];
public function recommendationRules()
{
    return $this->hasMany(RecommendationRule::class);
}

}
