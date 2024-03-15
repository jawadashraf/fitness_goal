<?php

namespace App\Models;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;

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

    protected $with = ['workout'];

    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    public function workout_schedule_progress(): HasMany
    {
        return $this->hasMany(WorkoutScheduleProgress::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserScope);
    }
}
