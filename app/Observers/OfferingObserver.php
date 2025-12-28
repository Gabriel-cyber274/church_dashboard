<?php

namespace App\Observers;

use App\Models\Offering;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferingUpdateNotification;

class OfferingObserver
{
    private $originalAttributes = [];

    /**
     * Handle the Offering "creating" event.
     */
    public function creating(Offering $offering): void
    {
        // Check if user is authenticated and has finance role
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $offering->status = 'pending';
        }

        // Set default status if not provided
        if (empty($offering->status)) {
            $offering->status = 'pending';
        }
    }

    /**
     * Handle the Offering "created" event.
     */
    public function created(Offering $offering): void
    {
        // Optional: Send notification when offering is created by finance
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new OfferingUpdateNotification($offering, 'created'));
            }
        }
    }

    /**
     * Handle the Offering "updating" event.
     * Store original attributes before update to compare changes
     */
    public function updating(Offering $offering): void
    {
        $this->originalAttributes = $offering->getOriginal();

        // If finance user is updating, set status to pending
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $offering->status = 'pending';
        }

        // Only super_admin can set status to completed
        if (auth()->check() && $offering->isDirty('status') && $offering->status === 'completed') {
            if (!auth()->user()->hasAnyRole(['super_admin'])) {
                // Prevent non-super_admin from setting status to completed
                $offering->status = $this->originalAttributes['status'] ?? 'pending';
            }
        }
    }

    /**
     * Handle the Offering "updated" event.
     */
    public function updated(Offering $offering): void
    {
        // Send email notification when finance user updates the offering
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new OfferingUpdateNotification($offering, 'updated', $this->originalAttributes));
            }
        }
    }

    /**
     * Handle the Offering "deleted" event.
     */
    public function deleted(Offering $offering): void
    {
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new OfferingUpdateNotification($offering, 'deleted'));
            }
        }
    }

    /**
     * Handle the Offering "restored" event.
     */
    public function restored(Offering $offering): void
    {
        //
    }

    /**
     * Handle the Offering "force deleted" event.
     */
    public function forceDeleted(Offering $offering): void
    {
        // if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
        //     $adminEmail = config('app.admin_email');
        //     if ($adminEmail) {
        //         Mail::to($adminEmail)->send(new OfferingUpdateNotification($offering, 'force deleted'));
        //     }
        // }
    }
}
