<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::command('leases:send-reminders')->dailyAt('15:10')->timezone('Africa/Dar_es_Salaam');
// Schedule::command('leases:send-reminders')->everyMinute();




