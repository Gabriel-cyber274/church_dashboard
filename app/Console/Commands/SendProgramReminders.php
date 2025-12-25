<?php

namespace App\Console\Commands;

use App\Mail\ProgramReminderMail;
use Illuminate\Console\Command;
use App\Models\Program;
use App\Notifications\ProgramReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendProgramReminders extends Command
{
    protected $signature = 'programs:send-reminders';
    protected $description = 'Send program reminders to members';

    public function handle()
    {
        $today = Carbon::today();

        Program::whereDate('start_date', '>=', $today)
            ->with('members')
            ->get()
            ->each(function ($program) use ($today) {

                $daysLeft = $today->diffInDays($program->start_date, false);

                // 2 weeks reminder OR daily from 5 days
                if ($daysLeft === 14 || ($daysLeft <= 5 && $daysLeft >= 0)) {
                    foreach ($program->members as $member) {
                        if ($member->email) {
                            Mail::to($member->email)
                                ->queue(new ProgramReminderMail($program, $daysLeft, $member));
                        }
                    }
                }
            });

        $this->info('Program reminders sent.');
    }
}
