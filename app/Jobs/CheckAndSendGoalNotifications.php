<?php

namespace App\Jobs;

use App\Notifications\CommonNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User; // Make sure to use your actual User model namespace

class CheckAndSendGoalNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $yesterday = now()->subDay()->startOfDay();

        foreach ($this->user->goals as $goal) {
            $wasAchievedYesterday = $goal->achievements()
                ->whereDate('achieved_at', '=', $yesterday)
                ->exists();

            if ($wasAchievedYesterday) {
                // Goal was achieved yesterday, send appreciation notification
                $notification_data = [
                    'id' => -1,
                    'push_notification_id' => -1,
                    'type' => 'push_notification',
                    'subject' => 'Get Ready Again',
                    'message' => 'Get ready for achieving the goal again.',
                    'image' => null
                ];
                $this->user->notify(new CommonNotification($notification_data['type'], $notification_data));
            } else {
                // Goal was not achieved yesterday, send reminder notification

                $notification_data = [
                    'id' => -1,
                    'push_notification_id' => -1,
                    'type' => 'push_notification',
                    'subject' => 'Chin up!',
                    'message' => 'Keep trying until you achieve the goals.',
                    'image' => null
                ];
                $this->user->notify(new CommonNotification($notification_data['type'], $notification_data));
            }
        }
    }
}
