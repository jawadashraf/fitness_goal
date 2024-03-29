<?php

namespace App\Models;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [ 'title','user_id','goal_type_id', 'unit_type_id', 'target_value', 'start_date',
        'end_date', 'status'];

    protected $casts = [
        'user_id'      => 'integer',
        'goal_type_id'      => 'integer',
        'unit_type_id'      => 'integer',
        'target_value' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',

    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserScope);
    }

    public function goal_type()
    {
        return $this->belongsTo(GoalType::class, 'goal_type_id', 'id');
    }

    /**
     * Get the unit type associated with the goal.
     */
    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id', 'id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(GoalProgress::class);
    }

    public function getDescription()
    {
        return "<span class='text-success'>{$this->title}</span>  | {$this->goal_type->title} | {$this->unit_type->title} | {$this->target_value}";
    }


}
