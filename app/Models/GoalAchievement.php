<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalAchievement extends Model
{
    use HasFactory;

    protected $fillable = ['goal_id', 'achieved_at', 'user_id'];

    protected $with = ['goal'];

    public function goal() {
        return $this->belongsTo(Goal::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


}
