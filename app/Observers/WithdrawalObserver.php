<?php

namespace App\Observers;

use App\Models\Withdrawal;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalUpdateNotification;

class WithdrawalObserver
{
    private $originalAttributes = [];

    /**
     * Handle the Withdrawal "creating" event.
     */
    public function creating(Withdrawal $withdrawal): void
    {
        // Check if user is authenticated and has finance role
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $withdrawal->status = 'pending';
        }

        // Set default status if not provided
        if (empty($withdrawal->status)) {
            $withdrawal->status = 'pending';
        }
    }

    /**
     * Handle the Withdrawal "created" event.
     */
    public function created(Withdrawal $withdrawal): void
    {
        // Optional: Send notification when withdrawal is created by finance
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new WithdrawalUpdateNotification($withdrawal, 'created'));
            }
        }
    }

    /**
     * Handle the Withdrawal "updating" event.
     * Store original attributes before update to compare changes
     */
    public function updating(Withdrawal $withdrawal): void
    {
        $this->originalAttributes = $withdrawal->getOriginal();

        // If finance user is updating, set status to pending
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $withdrawal->status = 'pending';
        }

        // Only super_admin can set status to approved
        if (auth()->check() && $withdrawal->isDirty('status') && in_array($withdrawal->status, ['approved', 'completed'])) {
            if (!auth()->user()->hasAnyRole(['super_admin'])) {
                // Prevent non-super_admin from setting status to approved/completed
                $withdrawal->status = $this->originalAttributes['status'] ?? 'pending';
            }
        }

        // Finance users cannot approve or complete withdrawals
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            if (in_array($withdrawal->status, ['approved', 'completed'])) {
                $withdrawal->status = 'pending';
            }
        }
    }

    /**
     * Handle the Withdrawal "updated" event.
     */
    public function updated(Withdrawal $withdrawal): void
    {
        // Send email notification when finance user updates the withdrawal
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new WithdrawalUpdateNotification($withdrawal, 'updated', $this->originalAttributes));
            }
        }

        // Send email notification when super_admin approves/completes withdrawal
        if (auth()->check() && auth()->user()->hasAnyRole(['super_admin'])) {
            if ($withdrawal->isDirty('status') && in_array($withdrawal->status, ['approved', 'completed'])) {
                $adminEmail = config('app.admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new WithdrawalUpdateNotification($withdrawal, 'status_updated', $this->originalAttributes));
                }
            }
        }
    }

    /**
     * Handle the Withdrawal "deleted" event.
     */
    public function deleted(Withdrawal $withdrawal): void
    {
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new WithdrawalUpdateNotification($withdrawal, 'deleted'));
            }
        }
    }

    /**
     * Handle the Withdrawal "restored" event.
     */
    public function restored(Withdrawal $withdrawal): void
    {
        // Optional: Send notification when withdrawal is restored
        // if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
        //     $adminEmail = config('app.admin_email');
        //     if ($adminEmail) {
        //         Mail::to($adminEmail)->send(new WithdrawalUpdateNotification($withdrawal, 'restored'));
        //     }
        // }
    }

    /**
     * Handle the Withdrawal "force deleted" event.
     */
    public function forceDeleted(Withdrawal $withdrawal): void
    {
        // if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
        //     $adminEmail = config('app.admin_email');
        //     if ($adminEmail) {
        //         Mail::to($adminEmail)->send(new WithdrawalUpdateNotification($withdrawal, 'force deleted'));
        //     }
        // }
    }
}
