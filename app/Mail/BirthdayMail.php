<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BirthdayMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;

    /**
     * Create a new message instance.
     */
    public function __construct($member)
    {
        $this->member = $member;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Happy Birthday 🎉')
            ->view('mail.birthday')
            ->with([
                'member' => $this->member,
                'logo'   => asset('images/logo.png'),
            ]);
    }
}
