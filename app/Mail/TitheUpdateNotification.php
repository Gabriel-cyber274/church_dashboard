<?php

namespace App\Mail;

use App\Models\Tithe;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TitheUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $tithe;
    public $action;
    public $originalAttributes;
    public $changes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Tithe $tithe, string $action = 'updated', array $originalAttributes = [])
    {
        $this->tithe = $tithe;
        $this->action = $action;
        $this->originalAttributes = $originalAttributes;

        // Calculate changes if we have original attributes
        if (!empty($originalAttributes)) {
            $this->changes = [];
            foreach ($tithe->getAttributes() as $key => $newValue) {
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
            'created' => 'New Tithe Created by Finance Team',
            'updated' => 'Tithe Updated by Finance Team',
            'deleted' => 'Tithe Deleted by Finance Team',
            'restored' => 'Tithe Restored by Finance Team',
            'force deleted' => 'Tithe Permanently Deleted by Finance Team',
            default => 'Tithe Update Notification'
        };

        return $this->subject($subject)
            ->markdown('emails.tithe-update')
            ->with([
                'tithe' => $this->tithe,
                'action' => $this->action,
                'changes' => $this->changes ?? null,
            ]);
    }
}
