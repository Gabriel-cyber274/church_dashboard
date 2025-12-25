<?php

namespace App\Mail;

use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    public $action;
    public $originalAttributes;
    public $changes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Deposit $deposit, string $action = 'updated', array $originalAttributes = [])
    {
        $this->deposit = $deposit;
        $this->action = $action;
        $this->originalAttributes = $originalAttributes;

        // Calculate changes if we have original attributes
        if (!empty($originalAttributes)) {
            $this->changes = [];
            foreach ($deposit->getAttributes() as $key => $newValue) {
                $oldValue = $originalAttributes[$key] ?? null;
                if ($oldValue != $newValue && !in_array($key, ['updated_at', 'created_at'])) {
                    $this->changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->action === 'created'
            ? 'New Deposit Created by Finance Team'
            : 'Deposit Updated by Finance Team';

        return $this->subject($subject)
            ->markdown('emails.deposit-update')
            ->with([
                'deposit' => $this->deposit,
                'action' => $this->action,
                'changes' => $this->changes ?? null,
            ]);
    }
}
