<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutScheduleProgress extends Model
{
    use HasFactory;
    protected $fillable = ['workout_schedule_id', 'exercise_id', 'progress'];

    public function workout_schedule(): BelongsTo
    {
        return $this->belongsTo(WorkoutSchedule::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
