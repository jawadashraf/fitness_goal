<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    use HasFactory;

    public function goals()
    {
        return $this->hasMany(Goal::class, 'unit_type_id');
    }

    public function goal_types()
    {
        return $this->belongsToMany(GoalType::class, 'goal_type_unit');
    }
}
