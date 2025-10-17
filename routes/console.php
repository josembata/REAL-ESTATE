<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::command('leases:send-reminders')->dailyAt('15:42')->timezone('Africa/Dar_es_Salaam');
// Schedule::command('leases:send-reminders')->everyMinute();

// Schedule::command('bookings:release-expired')->everyTenMinutes();

// Schedule::command('bookings:cancel-expired')->everyMinute();

  Schedule::command('leases:update-expired')->dailyAt('00:10');




