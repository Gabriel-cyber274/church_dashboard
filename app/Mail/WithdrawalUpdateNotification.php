<?php

namespace App\Mail;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;
    public $action;
    public $originalAttributes;
    public $changes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Withdrawal $withdrawal, string $action = 'updated', array $originalAttributes = [])
    {
        $this->withdrawal = $withdrawal;
        $this->action = $action;
        $this->originalAttributes = $originalAttributes;

        // Calculate changes if we have original attributes
        if (!empty($originalAttributes)) {
            $this->changes = [];
            foreach ($withdrawal->getAttributes() as $key => $newValue) {
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
        $subject = match ($this->action) {
            'created' => 'New Withdrawal Request Created by Finance Team',
            'updated' => 'Withdrawal Updated by Finance Team',
            'status_updated' => 'Withdrawal Status Updated by Admin',
            'deleted' => 'Withdrawal Deleted by Finance Team',
            'restored' => 'Withdrawal Restored by Finance Team',
            'force deleted' => 'Withdrawal Permanently Deleted by Finance Team',
            default => 'Withdrawal Update Notification'
        };

        return $this->subject($subject)
            ->markdown('emails.withdrawal-update')
            ->with([
                'withdrawal' => $this->withdrawal,
                'action' => $this->action,
                'changes' => $this->changes ?? null,
            ]);
    }
}
