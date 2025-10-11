<?php
namespace App\Services;

use App\Models\Lease;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LeaseService
{
    /**
     * Generate a lease for an invoice, produce PDF and store path.
     *
     * @param Invoice $invoice
     * @return Lease
     */
    public function generateForInvoice(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            throw new \Exception('Invoice must be paid before generating lease.');
        }

        $tenant = $invoice->user;

        // Fetch first booking with unit & property & ownerships
        $firstBooking = $invoice->bookings()->with('unit.property.ownerships.owner.user')->first();
        if (!$firstBooking || !$firstBooking->unit || !$firstBooking->unit->property) {
            throw new \Exception('No property or booking found for this invoice.');
        }

        $property = $firstBooking->unit->property;

        // Fetch the first landlord (owner) and their linked user
        $ownership = $property->ownerships()->with('owner.user')->first();
        $owner = $ownership?->owner;      // Landlord
        $ownerUser = $owner?->user;       // Linked User

        // Determine lease term
        $termStart = $invoice->bookings()->min('check_in')
            ? Carbon::parse($invoice->bookings()->min('check_in'))
            : Carbon::now();

        $termEnd = $invoice->bookings()->max('check_out')
            ? Carbon::parse($invoice->bookings()->max('check_out'))
            : $termStart->copy()->addMonth();

        // Create lease
        $lease = Lease::create([
            'lease_number' => strtoupper('LZ-' . Str::random(8)),
            'property_id'  => $property->id,
            'invoice_id'   => $invoice->id,
            'user_id'      => $tenant->id,
            'owner_id'     => $ownerUser?->id, // Linked User ID
            'term_start'   => $termStart,
            'term_end'     => $termEnd,
            'status'       => 'generated',
        ]);

        // Generate and store PDF
        $pdfData = [
            'lease'      => $lease,
            'tenant'     => $tenant,
            'owner'      => $owner,
            'term_start' => $termStart,
            'term_end'   => $termEnd,
        ];

        $pdf = Pdf::loadView('leases.pdf', $pdfData)->setPaper('a4', 'portrait');
        $fileName = 'leases/lease-' . $lease->lease_number . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        $lease->file_path = $fileName;
        $lease->save();

        return $lease;
    }
}
