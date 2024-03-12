<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GoalType;
use App\Models\UnitType;
use Illuminate\Support\Facades\DB;

class GoalTypeUnitTableSeeder extends Seeder
{
    public function run()
    {
        $relations = [
            'CARDIO' => ['kilometers', 'miles', 'minutes', 'calories'],
            'STRENGTH' => ['reps', 'sets', 'kilograms', 'pounds'],
            'HIIT' => ['minutes', 'calories', 'intervals'],
            'FLEXIBILITY' => ['minutes', 'reps'],
            'BALANCE' => ['minutes', 'reps'],
            'CIRCUIT' => ['minutes', 'calories', 'circuits'],
            'PLYOMETRICS' => ['reps', 'sets', 'minutes'],
            'CROSSFIT' => ['reps', 'sets', 'kilograms', 'pounds', 'minutes'],
            'DANCE' => ['minutes', 'calories'],
            'MARTIAL_ARTS' => ['minutes', 'calories', 'levels'],
            'MIND_BODY' => ['minutes', 'sessions'],
            'AQUATIC' => ['laps', 'minutes', 'calories'],
            'ENDURANCE' => ['kilometers', 'miles', 'hours', 'calories'],
            'CALISTHENICS' => ['reps', 'sets', 'minutes'],
            'FUNCTIONAL' => ['reps', 'sets', 'minutes', 'calories'],
        ];

        foreach ($relations as $goalTypeName => $unitNames) {
            $goalType = GoalType::firstWhere('name', $goalTypeName);

            if (!$goalType) {
                continue; // Skip if the goal type doesn't exist
            }

            foreach ($unitNames as $unitName) {
                $unitType = UnitType::firstWhere('name', $unitName);

                if (!$unitType) {
                    continue; // Skip if the unit type doesn't exist
                }

                // Check if the relation already exists to prevent duplicates
                $exists = DB::table('goal_type_unit')->where('goal_type_id', $goalType->id)->where('unit_type_id', $unitType->id)->exists();

                if (!$exists) {
                    DB::table('goal_type_unit')->insert([
                        'goal_type_id' => $goalType->id,
                        'unit_type_id' => $unitType->id
                    ]);
                }
            }
        }
    }
}
