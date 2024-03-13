<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class WorkoutSchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = DB::table('users')->where('email', 'jawadashraf78@gmail.com')->first()->id;
        $workoutIds = DB::table('workouts')->where('user_id', $userId)->pluck('id');

        if ($workoutIds->isEmpty()) {
            // No users or workouts to associate, return or throw exception
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            DB::table('workout_schedules')->insert([
                'title' => 'Workout Schedule ' . $i,
                'user_id' => $userId, // Assuming there are users in the users table
                'workout_id' => $workoutIds->random(), // Assuming there are workouts in the workouts table
                'start' => Carbon::now()->addDays($i),
                'end' => Carbon::now()->addDays($i)->addHours(1), // For example, 1 hour duration
                'date_completed' => null, // Or Carbon::now()->addDays($i) if completed
                'percent_completed' => rand(0, 100), // Random completion percentage
                'notes' => 'This is a seeded schedule.',
                'color' => '#'.substr(md5(rand()), 0, 6), // Random color
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
