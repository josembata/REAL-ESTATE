<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class ReleaseExpiredBookings extends Command
{
    protected $signature = 'bookings:release-expired';
    protected $description = 'Automatically release units from unpaid bookings after 1 hour';

    public function handle()
    {
        $now = Carbon::now();

        $expiredBookings = Booking::where('status', 'pending')
            ->where('payment_status', 'unpaid')
            ->where('expires_at', '<=', $now)
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => $now,
            ]);

            $booking->unit->update(['status' => 'available']);

            $this->info("Booking #{$booking->id} expired — unit released.");
        }

        $this->info(" Expired booking cleanup complete.");
    }
}
