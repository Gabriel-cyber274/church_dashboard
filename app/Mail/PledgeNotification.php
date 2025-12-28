<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PledgeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public array $mailData;

    public function __construct(array $mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope(): Envelope
    {
        $amount = number_format($this->mailData['pledge']->amount, 2);

        return new Envelope(
            subject: "New Pledge Recorded – ₦{$amount}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pledge-notification',
            with: $this->mailData,
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
