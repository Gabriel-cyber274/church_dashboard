<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Program;

class ProgramReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $program;
    public $daysLeft;
    public $member;

    /**
     * Create a new message instance.
     */
    public function __construct(Program $program, int $daysLeft, $member)
    {
        $this->program = $program;
        $this->daysLeft = $daysLeft;
        $this->member = $member;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->daysLeft === 0
            ? "Today: {$this->program->name}"
            : "Upcoming Program: {$this->program->name} (in {$this->daysLeft} day" . ($this->daysLeft > 1 ? 's' : '') . ")";

        return $this->subject($subject)
            ->view('mail.program-reminder')
            ->with([
                'member' => $this->member,
                'program' => $this->program,
                'daysLeft' => $this->daysLeft,
                'logo' => asset('images/logo.png'),
            ]);
    }
}
