<?php

namespace Database\Seeders;

use App\Models\GoalType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GoalTypesTableSeeder extends Seeder
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

        foreach ($goalTypes as $goalType) {
            GoalType::firstOrCreate(['title' => $goalType]);
        }
    }
}
