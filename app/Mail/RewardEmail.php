<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RewardEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $rewardTitle;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $rewardTitle)
    {
        $this->user = $user;
        $this->rewardTitle = $rewardTitle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Congratulations on Achieving A Reward!')
            ->view('emails.reward');

    }
}
