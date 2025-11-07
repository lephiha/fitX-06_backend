<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressTracking extends Model
{
    use HasFactory;

    protected $table = 'progress_tracking';

    protected $fillable = [
        'user_id',
        'date',
        'weight',
        'wast',
        'bmi',
        'streak_days',
        'workout_count',
        'weight_diff',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
