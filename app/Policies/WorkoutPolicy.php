<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Workout $workout)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Workout $workout)
    {
        return $user->id === $workout->user_id || ($user->hasRole('admin') && $workout->user_id == null);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Workout $workout)
    {
        return $user->id === $workout->user_id || ($user->hasRole('admin') && $workout->user_id == null);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Workout $workout)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Workout $workout)
    {
        //
    }
}
