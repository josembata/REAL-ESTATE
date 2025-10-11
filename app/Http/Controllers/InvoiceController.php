<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Booking;
use App\Models\InvoiceBooking;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function index()
{
    $invoices = auth()->user()
        ->invoices() // relationship in User model
        ->whereIn('status', ['unpaid', 'partially_paid', 'paid'])
        ->latest()
        ->get();

    return view('invoices.index', compact('invoices'));
}

    /**
     * Attach booking to user’s invoice.
     */
    public function addBookingToInvoice(Booking $booking)
    {
        $user = $booking->customer;

        // Find active invoice (unpaid or partially_paid)
        $invoice = Invoice::where('user_id', $user->id)
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->first();

        if (!$invoice) {
            // Create new invoice if none exists
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'invoice_number' => strtoupper(Str::random(10)),
                'amount_due' => 0,
                'currency' => $booking->currency,
                'status' => 'unpaid',
            ]);
        }

        // Add booking to invoice_bookings
        InvoiceBooking::create([
            'invoice_id' => $invoice->id,
            'booking_id' => $booking->id,
            'amount' => $booking->total_amount,
        ]);

        // Update invoice total
        $invoice->amount_due += $booking->total_amount;
        $invoice->save();

        return $invoice;
    }

    /**
     * Remove booking from invoice (on cancel).
     */
    public function removeBookingFromInvoice(Booking $booking)
    {
        $invoiceBooking = InvoiceBooking::where('booking_id', $booking->id)->first();

        if ($invoiceBooking) {
            $invoice = $invoiceBooking->invoice;

            // Subtract from invoice total
            $invoice->amount_due -= $invoiceBooking->amount;
            $invoice->amount_due = max(0, $invoice->amount_due);

            $invoice->save();

            // Delete the pivot record
            $invoiceBooking->delete();

            // If invoice is now empty, mark it as cancelled
            if ($invoice->bookings()->count() == 0) {
                $invoice->status = 'cancelled';
                $invoice->save();
            }

            return $invoice;
        }

        return null;
    }

    public function show($id)
{
    $invoice = Invoice::with('bookings.unit', 'bookings.room', 'user', 'payments')->findOrFail($id);

    return view('invoices.show', compact('invoice'));
}

public function print(Invoice $invoice)
{
    // Only allow invoice owner
    if ($invoice->user_id !== auth()->id()) {
        abort(403);
    }

    return view('invoices.print', compact('invoice'));
}


public function download(Invoice $invoice)
{
    // Ensure invoice belongs to current user
    if ($invoice->user_id !== auth()->id()) {
        abort(403);
    }

    // Load a  view for PDF
    $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

    $fileName = "Invoice-{$invoice->invoice_number}.pdf";

    return $pdf->download($fileName);
}


}
