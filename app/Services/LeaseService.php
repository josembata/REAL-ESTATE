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

        // Fetch the first booking linked to this invoice
        $firstBooking = $invoice->bookings()->with(['unit.property.ownerships.owner.user'])->first();

        if (!$firstBooking || !$firstBooking->unit || !$firstBooking->unit->property) {
            throw new \Exception('No property or booking found for this invoice.');
        }

        $property = $firstBooking->unit->property;
        $unit = $firstBooking->unit;

        // Get the first landlord (owner) and their linked user
        $ownership = $property->ownerships()->with('owner.user')->first();
        $owner = $ownership?->owner;
        $ownerUser = $owner?->user;

        // Determine lease term
        $termStart = $invoice->bookings()->min('check_in')
            ? Carbon::parse($invoice->bookings()->min('check_in'))
            : Carbon::now();

        $termEnd = $invoice->bookings()->max('check_out')
            ? Carbon::parse($invoice->bookings()->max('check_out'))
            : $termStart->copy()->addMonth();

        // Calculate total rent (from bookings)
        $totalAmount = $invoice->bookings()->sum('total_amount');

        // Create lease with full details
        $lease = Lease::create([
            'lease_number'       => strtoupper('LZ-' . Str::random(8)),
            'property_id'        => $property->id,
            'unit_id'            => $unit->id,
            'booking_id'         => $firstBooking->id,
            'invoice_id'         => $invoice->id,
            'user_id'            => $tenant->id,
            'owner_id'           => $ownerUser?->id,
            'term_start'         => $termStart,
            'term_end'           => $termEnd,
            'renewal_amount'     => null, // will be filled when renewed
            'previous_term_end'  => null, // only used during renewals
            'status'             => 'generated',
            'total_amount'       => $totalAmount,
        ]);

        // Generate and store lease PDF
        $pdfData = [
            'lease'      => $lease,
            'tenant'     => $tenant,
            'owner'      => $owner,
            'unit'       => $unit,
            'term_start' => $termStart,
            'term_end'   => $termEnd,
            'total'      => $totalAmount,
        ];

        $pdf = Pdf::loadView('leases.pdf', $pdfData)->setPaper('a4', 'portrait');
        $fileName = 'leases/lease-' . $lease->lease_number . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        $lease->update(['file_path' => $fileName]);

        return $lease;
    }
}
