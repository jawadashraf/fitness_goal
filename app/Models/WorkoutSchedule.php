<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutSchedule extends Model
{
    use HasFactory;

    protected $fillable = [ 'title', 'notes', 'status', 'user_id', 'workout_id',
        'date_completed', 'percent_completed', 'start', 'end', 'color' ];

    protected $casts = [
        'workout_id'   => 'integer',
        'percent_completed'          => 'integer',
        'start'        => 'datetime',
        'end'        => 'datetime',
    ];
}
