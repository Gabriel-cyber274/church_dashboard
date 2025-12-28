<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Deposit;

class DepositCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    public $member;

    /**
     * Create a new message instance.
     */
    public function __construct(Deposit $deposit)
    {
        $this->deposit = $deposit;
        $this->member = $deposit->member; // get the member related to the deposit
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Deposit Has Been Completed ✅')
            ->view('mail.deposit_completed')
            ->with([
                'member' => $this->member,
                'deposit' => $this->deposit,
                'logo' => asset('images/logo.png'),
            ]);
    }
}
