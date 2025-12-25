<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class AdminBirthdaySummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $members;
    public $date;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $members)
    {
        $this->members = $members;
        $this->date = now()->format('F j, Y');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject("Today's Birthday Celebrants 🎂")
            ->view('mail.admin-birthday-summary')
            ->with([
                'members' => $this->members,
                'date' => $this->date,
            ]);
    }
}
