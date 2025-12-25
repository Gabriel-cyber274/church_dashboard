<?php

namespace App\Console\Commands;

use App\Mail\AdminBirthdaySummaryMail;
use App\Mail\BirthdayMail;
use Illuminate\Console\Command;
use App\Models\Member;
use App\Notifications\BirthdayNotification;
use App\Notifications\AdminBirthdaySummaryNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBirthdayGreetings extends Command
{
    protected $signature = 'members:send-birthday-greetings';
    protected $description = 'Send birthday greetings to members and notify admin';

    public function handle()
    {
        $today = Carbon::today();

        // Query members with birthdays today
        $members = Member::whereNotNull('date_of_birth')
            ->whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->whereNotNull('email')
            ->where('email', '!=', '') // Also exclude empty strings
            ->get();

        if ($members->isEmpty()) {
            $this->info("No members have birthdays today. No notifications sent.");
            return;
        }

        // Debug: Show found members
        $this->info("Found {$members->count()} members with birthdays today:");

        foreach ($members as $member) {
            $this->info("- {$member->full_name} (DOB: {$member->date_of_birth})");
        }

        // Send birthday emails to members
        $sentCount = 0;
        foreach ($members as $member) {
            try {
                Mail::to($member->email)
                    ->queue(new BirthdayMail($member));
                $sentCount++;
                $this->info("Sent birthday notification to: {$member->full_name} ({$member->email})");
            } catch (\Exception $e) {
                $this->error("Failed to send to {$member->email}: " . $e->getMessage());
                Log::error("Birthday email failed for member {$member->id}: " . $e->getMessage());
            }
        }

        // Only notify admin if there are birthdays (moved inside the if statement)
        $adminEmail = config('app.admin_email');

        if ($adminEmail && $sentCount > 0) {
            try {

                Mail::to($adminEmail)
                    ->queue(new AdminBirthdaySummaryMail($members));

                $this->info("Admin notification sent to: {$adminEmail}");
            } catch (\Exception $e) {
                $this->error("Failed to send admin notification: " . $e->getMessage());
                Log::error("Admin birthday summary email failed: " . $e->getMessage());
            }
        } else if ($adminEmail) {
            $this->warn("No birthday emails were successfully sent, so admin notification was skipped.");
        } else {
            $this->warn("Admin email not configured (app.admin_email)");
        }

        $this->info("Birthday greetings completed. Sent to {$sentCount} members.");
    }
}
