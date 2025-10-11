<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lease;
use App\Notifications\LeaseExpiringSoon;
use Carbon\Carbon;

class SendLeaseExpiryReminders extends Command
{
    protected $signature = 'leases:send-reminders';
    protected $description = 'Send reminders for leases expiring within 7 days';

    public function handle()
    {
        // $targetDate = Carbon::now()->addDays(7);
        // $leases = Lease::whereDate('term_end', Carbon::now()->addDay())->get();


      $leases = Lease::whereDate('term_end', '<=', now()->addWeek())
               ->where('status', 'generated')
               ->get();

foreach ($leases as $lease) {
    if ($lease->user) { // make sure user exists
        $lease->user->notifyNow(new \App\Notifications\LeaseExpiringSoon($lease));
    }
}

        $this->info('Reminders sent for ' . $leases->count() . ' leases.');
    }
}
