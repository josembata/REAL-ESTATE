<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lease;
use Carbon\Carbon;

class UpdateExpiredLeases extends Command
{
    protected $signature = 'leases:update-expired';
    protected $description = 'Update expired leases and make property available again if not renewed';

    public function handle()
    {
        $leases = Lease::where('status', 'generated')
            ->orWhere('status', 'active')
            ->get();

        $count = 0;

        foreach ($leases as $lease) {
            // Check if lease expired
            if ($lease->hasExpired() && !$lease->isRenewedWithinGracePeriod()) {
                $lease->status = 'expired';
                $lease->save();

                // Mark property available again
                if ($lease->property) {
                    $lease->property->status = 'available';
                    $lease->property->save();
                }

                $count++;
            }
        }

        $this->info("✅ {$count} leases marked as expired and properties made available.");
    }
}
