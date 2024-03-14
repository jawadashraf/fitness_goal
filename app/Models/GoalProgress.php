<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalProgress extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id','goal_id', 'progress_value', 'date'];

    protected $casts = [
        'user_id'      => 'integer',
        'goal_id'      => 'integer',
        'date' => 'datetime',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
