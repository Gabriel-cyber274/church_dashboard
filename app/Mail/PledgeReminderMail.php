<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pledge;

class PledgeReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pledge;
    public $daysLeft;
    public $member;
    public $itemName;
    public $logoPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Pledge $pledge, int $daysLeft)
    {
        $this->pledge = $pledge;
        $this->daysLeft = $daysLeft;
        $this->member = $pledge->member;

        // Determine item name
        if ($pledge->program) {
            $this->itemName = $pledge->program->name;
        } elseif ($pledge->project) {
            $this->itemName = $pledge->project->name;
        } else {
            $this->itemName = 'Pledge';
        }

        // Set logo path
        $this->logoPath = public_path('images/logo.png');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->daysLeft === 0
            ? "Pledge Due Today: {$this->itemName}"
            : "Pledge Reminder: {$this->itemName} (in {$this->daysLeft} day" . ($this->daysLeft > 1 ? 's' : '') . ")";

        return $this->subject($subject)
            ->view('emails.pledge-reminder')
            ->with([
                'pledge' => $this->pledge,
                'member' => $this->member,
                'daysLeft' => $this->daysLeft,
                'itemName' => $this->itemName,
                'logoPath' => $this->logoPath,
            ]);
    }
}
