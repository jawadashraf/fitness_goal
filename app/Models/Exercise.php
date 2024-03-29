<?php

namespace App\Models;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Exercise extends Model implements HasMedia
{
    use HasFactory ,InteractsWithMedia;

    protected $fillable = [ 'title', 'instruction', 'tips', 'video_type', 'video_url', 'bodypart_ids', 'duration',
        'sets', 'equipment_id', 'level_id', 'status','is_premium', 'based', 'type', 'user_id', 'goal_type_id' ];

    protected $casts = [
        'equipment_id'      => 'integer',
        'level_id'          => 'integer',
        'is_premium'        => 'integer',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }

    public function goal_type(): BelongsTo
    {
        return $this->belongsTo(GoalType::class);
    }
    public function getBodypartIdsAttribute($value)
    {
        return isset($value) ? json_decode($value, true) : null;
    }

    public function setBodypartIdsAttribute($value)
    {
        $this->attributes['bodypart_ids'] = isset($value) ? json_encode($value) : null;
    }

    public function getSetsAttribute($value)
    {
        return isset($value) ? json_decode($value, true) : null;
    }

    public function setSetsAttribute($value)
    {
        $this->attributes['sets'] = isset($value) ? json_encode($value) : null;
    }

    public function workoutDayExercise(){
        return $this->hasMany(WorkoutDayExercise::class, 'exercise_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserScope);

        static::deleted(function ($row) {
            $row->workoutDayExercise()->delete();
        });
    }

}
