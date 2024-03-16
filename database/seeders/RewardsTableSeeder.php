<?php

namespace Database\Seeders;

use App\Models\GoalType;
use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $goalTypes = [
            'CARDIO',
            'STRENGTH',
            'HIIT',
            'FLEXIBILITY',
            'BALANCE',
            'CIRCUIT',
            'PLYOMETRICS',
            'CROSSFIT',
            'DANCE',
            'MARTIAL_ARTS',
            'MIND_BODY',
            'AQUATIC',
            'ENDURANCE',
            'CALISTHENICS',
            'FUNCTIONAL',
        ];

        foreach ($goalTypes as $type) {
            // First, we need to ensure that the goal type exists in the goal_types table
            $goalType = GoalType::firstOrCreate(['title' => $type]);

            // Now, we create a reward for this goal type
            Reward::create([
                'goal_type_id' => $goalType->id,
                'threshold' => 1,
                'description' => "Reward for completing one {$type} goal",
                'icon' => "/images/badges/{$type}.svg", // Placeholder, replace with actual icon path
            ]);
        }
    }
}
