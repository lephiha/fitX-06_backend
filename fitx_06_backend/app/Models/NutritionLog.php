<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_type',
        'food_name',
        'calories',
        'protein',
        'carbs',
        'fat',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
