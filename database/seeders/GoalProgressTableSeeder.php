<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoalProgressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = DB::table('users')->where('email', 'jawadashraf78@gmail.com')
            ->pluck('id');
        $goalIds = DB::table('goals')->where('user_id', $userIds[0])->pluck('id');

        $dates = [Carbon::today()->subDays(30), Carbon::today()]; // Example dates including today

        //THis code can be used for multiple users, I have modified it for one user at the moment
        foreach ($goalIds as $goalId) {
            foreach ($userIds as $userId) {
//                foreach ($dates as $date) {
//
//
//
//                    DB::table('goal_progress')->insert([
//                        'goal_id' => $goalId,
//                        'user_id' => $userId,
//                        'progress_value' => rand(1, 100), // Example random progress
//                        'date' => $date,
//                        'created_at' => $date,
//                        'updated_at' => $date,
//                    ]);
//                }

                $entriesCount = rand(3, 5); // Random number of entries between 3 and 5

                for ($i = 0; $i < $entriesCount; $i++) {
                    DB::table('goal_progress')->insert([
                        'goal_id' => $goalId,
                        'user_id' => $userId,
                        'progress_value' => rand(1, 100), // Example random progress value
                        'date' => Carbon::today()->subDays(rand(0, 30)), // Random date within the last 30 days
                    ]);
                }
            }
        }
    }
}
