<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'exercise_name',
        'sets',
        'reps',
        'weight'
    ];

    public function schedule()
    {
        return $this->belongsTo(WorkoutSchedule::class, 'schedule_id');
    }
}
