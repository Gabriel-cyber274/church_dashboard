<?php

namespace App\Observers;

use App\Models\Deposit;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepositUpdateNotification;

class DepositObserver
{
    private $originalAttributes = [];

    /**
     * Handle the Deposit "creating" event.
     */
    public function creating(Deposit $deposit): void
    {
        // Check if user is authenticated and has finance role
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $deposit->status = 'pending';
        }
    }

    /**
     * Handle the Deposit "created" event.
     */
    public function created(Deposit $deposit): void
    {
        // Optional: Send notification when deposit is created by finance
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new DepositUpdateNotification($deposit, 'created'));
            }
        }
    }

    /**
     * Handle the Deposit "updating" event.
     * Store original attributes before update to compare changes
     */
    public function updating(Deposit $deposit): void
    {
        $this->originalAttributes = $deposit->getOriginal();

        // If finance user is updating, set status to pending
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $deposit->status = 'pending';
        }

        // Only super_admin can set status to completed
        if (auth()->check() && $deposit->isDirty('status') && $deposit->status === 'completed') {
            if (!auth()->user()->hasAnyRole(['super_admin'])) {
                // Prevent non-super_admin from setting status to completed
                $deposit->status = $this->originalAttributes['status'] ?? 'pending';
            }
        }
    }

    /**
     * Handle the Deposit "updated" event.
     */
    public function updated(Deposit $deposit): void
    {
        // Send email notification when finance user updates the deposit
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new DepositUpdateNotification($deposit, 'updated', $this->originalAttributes));
            }
        }

        // Send email to member if super_admin sets deposit as completed
        if (auth()->check() && auth()->user()->hasAnyRole(['super_admin'])) {
            if ($deposit->isDirty('status') && $deposit->status === 'completed') {
                if ($deposit->member && $deposit->member->email) {
                    Mail::to($deposit->member->email)->queue(new \App\Mail\DepositCompletedMail($deposit));
                }
            }
        }
    }

    /**
     * Handle the Deposit "deleted" event.
     */
    public function deleted(Deposit $deposit): void
    {
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new DepositUpdateNotification($deposit, 'deleted'));
            }
        }
    }

    /**
     * Handle the Deposit "restored" event.
     */
    public function restored(Deposit $deposit): void
    {
        //
    }

    /**
     * Handle the Deposit "force deleted" event.
     */
    public function forceDeleted(Deposit $deposit): void
    {
        //
    }
}
