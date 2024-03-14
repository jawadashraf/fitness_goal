<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class GoalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = DB::table('users')->where('email', 'jawadashraf78@gmail.com')->first()->id;
        $goalTypes = DB::table('goal_types')->pluck('id');


        for ($i = 0; $i < 5; $i++) {
            $goal_type_id = $goalTypes->random();
            $unit_type_id =  DB::table('goal_type_unit')
                ->where('goal_type_id', $goal_type_id)
                ->join('unit_types', 'unit_types.id', '=', 'goal_type_unit.unit_type_id')
                ->pluck('unit_types.id')
                ->random();

            DB::table('goals')->insert([
                'title' => 'Goal ' . $i,
                'user_id'=>$userId,
                'goal_type_id' => $goal_type_id,
                'unit_type_id' => $unit_type_id,
                'target_value' => rand(1, 1000),
                'start_date' => Carbon::now()->subDays(rand(0, 365)),
                'end_date' => Carbon::now()->addDays(rand(0, 365)),
                'status' => ['ACTIVE', 'COMPLETED', 'FAILED'][rand(0, 2)],
            ]);
        }
    }
}
