<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pledge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PledgeReminderMail;
use App\Mail\PledgeAdminNotificationMail;
use Illuminate\Support\Facades\Log;

class SendPledgeReminders extends Command
{
    protected $signature = 'pledges:send-reminders';
    protected $description = 'Send pledge reminders before and on pledge date';

    public function handle()
    {
        $today = Carbon::now()->startOfDay();
        Log::info('Pledge reminder command started', ['today' => $today->toDateString()]);

        $adminEmail = config('app.admin_email');

        Pledge::with('member')
            ->where('status', 'pending')
            ->whereDate('pledge_date', '>=', $today)
            ->get()
            ->each(function ($pledge) use ($today, $adminEmail) {

                $pledgeDate = Carbon::parse($pledge->pledge_date)->startOfDay();
                $daysLeft = (int) $today->diffInDays($pledgeDate, false);

                Log::info('Checking pledge', [
                    'pledge_id' => $pledge->id,
                    'pledge_date' => $pledgeDate->toDateString(),
                    'days_left' => $daysLeft,
                    'member_id' => $pledge->member?->id ?? null,
                    'member_email' => $pledge->member?->email ?? null
                ]);

                // Send reminder 3 days before OR on the pledge day
                if ($daysLeft == 3 || $daysLeft == 0) {
                    $member = $pledge->member;

                    if ($member && $member->email) {
                        Log::info('Sending pledge reminder email', [
                            'pledge_id' => $pledge->id,
                            'email' => $member->email,
                            'days_left' => $daysLeft
                        ]);

                        // Send to member
                        Mail::to($member->email)
                            ->queue(new PledgeReminderMail($pledge, $daysLeft));

                        // Send admin notification
                        if ($adminEmail) {
                            Mail::to($adminEmail)
                                ->queue(new PledgeAdminNotificationMail($pledge, $daysLeft));
                        }
                    } else {
                        Log::warning('Skipping pledge reminder - member or email missing', [
                            'pledge_id' => $pledge->id,
                            'member_id' => $pledge->member?->id ?? null
                        ]);
                    }
                } else {
                    Log::info('No email sent - daysLeft condition not met', [
                        'pledge_id' => $pledge->id,
                        'days_left' => $daysLeft
                    ]);
                }
            });

        Log::info('Pledge reminder command finished.');
        $this->info('Pledge reminders processed. Check logs for details.');
    }
}
