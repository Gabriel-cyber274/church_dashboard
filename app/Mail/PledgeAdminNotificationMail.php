<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pledge;

class PledgeAdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pledge;
    public $daysLeft;

    public function __construct(Pledge $pledge, int $daysLeft)
    {
        $this->pledge = $pledge;
        $this->daysLeft = $daysLeft;
    }

    public function build()
    {
        $subject = $this->daysLeft === 0
            ? "Pledge Due Today: {$this->pledge->member->full_name}"
            : "Upcoming Pledge Reminder: {$this->pledge->member->full_name} ({$this->daysLeft} day" . ($this->daysLeft > 1 ? 's' : '') . ")";

        return $this->subject($subject)
            ->view('mail.pledge-admin-notification')
            ->with([
                'pledge' => $this->pledge,
                'daysLeft' => $this->daysLeft,
                'member' => $this->pledge->member,
            ]);
    }
}
