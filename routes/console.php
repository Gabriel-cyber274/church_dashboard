<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('pledges:send-reminders')->dailyAt('07:00');
Schedule::command('members:send-birthday-greetings')->dailyAt('08:00');
Schedule::command('programs:send-reminders')->dailyAt('09:00');



// Schedule::command('pledges:send-reminders');
// Schedule::command('members:send-birthday-greetings');
// Schedule::command('programs:send-reminders');
