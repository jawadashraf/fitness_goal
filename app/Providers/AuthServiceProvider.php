<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\BodyPart' => 'App\Policies\BodyPartPolicy',
        'App\Models\Exercise' => 'App\Policies\ExercisePolicy',
        'App\Models\Equipment' => 'App\Policies\EquipmentPolicy',
        'App\Models\Workout' => 'App\Policies\WorkoutPolicy',
        'App\Models\WorkoutType' => 'App\Policies\WorkoutTypePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
