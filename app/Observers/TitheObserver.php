<?php

namespace App\Observers;

use App\Models\Tithe;
use Illuminate\Support\Facades\Mail;
use App\Mail\TitheUpdateNotification;

class TitheObserver
{
    private $originalAttributes = [];

    /**
     * Handle the Tithe "creating" event.
     */
    public function creating(Tithe $tithe): void
    {
        // Check if user is authenticated and has finance role
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $tithe->status = 'pending';
        }

        // Set default status if not provided
        if (empty($tithe->status)) {
            $tithe->status = 'pending';
        }
    }

    /**
     * Handle the Tithe "created" event.
     */
    public function created(Tithe $tithe): void
    {
        // Optional: Send notification when tithe is created by finance
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new TitheUpdateNotification($tithe, 'created'));
            }
        }
    }

    /**
     * Handle the Tithe "updating" event.
     * Store original attributes before update to compare changes
     */
    public function updating(Tithe $tithe): void
    {
        $this->originalAttributes = $tithe->getOriginal();

        // If finance user is updating, set status to pending
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $tithe->status = 'pending';
        }

        // Only super_admin can set status to completed
        if (auth()->check() && $tithe->isDirty('status') && $tithe->status === 'completed') {
            if (!auth()->user()->hasAnyRole(['super_admin'])) {
                // Prevent non-super_admin from setting status to completed
                $tithe->status = $this->originalAttributes['status'] ?? 'pending';
            }
        }
    }

    /**
     * Handle the Tithe "updated" event.
     */
    public function updated(Tithe $tithe): void
    {
        // Send email notification when finance user updates the tithe
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new TitheUpdateNotification($tithe, 'updated', $this->originalAttributes));
            }
        }
    }

    /**
     * Handle the Tithe "deleted" event.
     */
    public function deleted(Tithe $tithe): void
    {
        if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new TitheUpdateNotification($tithe, 'deleted'));
            }
        }
    }

    /**
     * Handle the Tithe "restored" event.
     */
    public function restored(Tithe $tithe): void
    {
        // Optional: Send notification when tithe is restored
        // if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
        //     $adminEmail = config('app.admin_email');
        //     if ($adminEmail) {
        //         Mail::to($adminEmail)->send(new TitheUpdateNotification($tithe, 'restored'));
        //     }
        // }
    }

    /**
     * Handle the Tithe "force deleted" event.
     */
    public function forceDeleted(Tithe $tithe): void
    {
        // if (auth()->check() && auth()->user()->hasAnyRole(['finance'])) {
        //     $adminEmail = config('app.admin_email');
        //     if ($adminEmail) {
        //         Mail::to($adminEmail)->send(new TitheUpdateNotification($tithe, 'force deleted'));
        //     }
        // }
    }
}
