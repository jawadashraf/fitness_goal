<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [ 'name','user_id','goal_type_id', 'unit_type_id', 'target_value', 'start_date',
        'end_date', 'status'];

    protected $casts = [
        'user_id'      => 'integer',
        'goal_type_id'      => 'integer',
        'unit_type_id'      => 'integer',
        'target_value' => 'decimal',
        'start_date' => 'date',
        'end_date' => 'date',

    ];

    public function goal_type()
    {
        return $this->belongsTo(GoalType::class, 'goal_type_id');
    }

    /**
     * Get the unit type associated with the goal.
     */
    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }
}
