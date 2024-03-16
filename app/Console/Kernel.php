<?php

namespace App\Console;

use App\Models\WorkoutSchedule;
use App\Notifications\CommonNotification;
use App\Scopes\UserScope;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckSubscription;
use App\Console\Commands\SendQuotes;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CheckSubscription::class,
        SendQuotes::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('check:subscription')->daily();
        $time = SettingData ('QUOTE', 'QUOTE_TIME') ?? '05:00';
        $schedule->command('send:quotes')->daily()->at($time);

        $schedule->call(function () {
            $later = now()->addHour(1);
            $later->second(0);
            $oneMinuteLater = $later->copy()->addMinute();
            Log::info('LaterHour' . $later . ' - '. $oneMinuteLater);

            $workoutSchedules = WorkoutSchedule::withoutGlobalScope(UserScope::class)
                ->where('start', '>=', $later)
                ->where('start', '<', $oneMinuteLater)
                ->get();

            Log::info('Scheduled Task: '. $workoutSchedules);

            foreach ($workoutSchedules as $workoutSchedule) {
                // Assuming there's a relation or method to get the user
                $user = $workoutSchedule->user;
//                $user->notify(new WorkoutReminderNotification($workoutSchedule));
                $notification_data = [
                    'id' => -1,
                    'push_notification_id' => -1,
                    'type' => 'push_notification',
                    'subject' => 'Approaching:' . $workoutSchedule->title,
                    'message' => 'Message for Approaching:' . $workoutSchedule->title,
                    'image' => null
                ];
                $user->notify(new CommonNotification($notification_data['type'], $notification_data));
            }
        })->everyMinute()->then(function () {
            Log::info('Scheduled task ran successfully.');
        })->onFailure(function () {
            // This will be executed if the task throws an exception
            Log::error('Scheduled task failed to run.');
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
