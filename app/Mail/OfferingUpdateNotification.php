<?php

namespace App\Mail;

use App\Models\Offering;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferingUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $offering;
    public $action;
    public $originalAttributes;
    public $changes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Offering $offering, string $action = 'updated', array $originalAttributes = [])
    {
        $this->offering = $offering;
        $this->action = $action;
        $this->originalAttributes = $originalAttributes;

        // Calculate changes if we have original attributes
        if (!empty($originalAttributes)) {
            $this->changes = [];
            foreach ($offering->getAttributes() as $key => $newValue) {
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
            'created' => 'New Offering Created by Finance Team',
            'updated' => 'Offering Updated by Finance Team',
            'deleted' => 'Offering Deleted by Finance Team',
            'force deleted' => 'Offering Permanently Deleted by Finance Team',
            default => 'Offering Update Notification'
        };

        return $this->subject($subject)
            ->markdown('emails.offering-update')
            ->with([
                'offering' => $this->offering,
                'action' => $this->action,
                'changes' => $this->changes ?? null,
            ]);
    }
}
