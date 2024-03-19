<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AchievementEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $goalTitle;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $goalTitle)
    {
        $this->user = $user;
        $this->goalTitle = $goalTitle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Congratulations on Achieving Your Goal!')
            ->view('emails.achievement');

//        return $this->markdown('emails.achievement')
//            ->subject('Congratulations on Achieving Your Goal!');
    }
}
