<?php

namespace App\Http\Controllers;

use App\Services\LeaseService;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // Show payment form 
    public function chooseMethod($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $tenant = auth()->user()->tenant;

        return view('payments.choose', compact('invoice', 'tenant'));
    }

    // Handle Mobile Money payment
    public function mobile(Request $request, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'provider' => $request->provider, // mpesa/tigopesa
            'provider_payment_id' => Str::uuid(),
            'amount' => $invoice->amount_due,
            'currency' => $invoice->currency,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update invoice
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        //  Update all bookings linked to this invoice
    foreach ($invoice->bookings as $booking) {
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed', 
        ]);
            $booking->unit->update(['status' => 'booked']);
            $booking->room?->update(['availability_status' => 'occupied']);
        }

        // Generate lease
        try {
            $leaseService = new LeaseService();
            $lease = $leaseService->generateForInvoice($invoice);
        } catch (\Throwable $e) {
            \Log::error('Lease generation failed: ' . $e->getMessage());
            $lease = null;
        }

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Payment successful. ' . ($lease ? 'Lease generated.' : 'Lease generation failed, contact admin.'));
    }

    // Handle Card payment
    public function card(Request $request, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'provider' => 'card',
            'provider_payment_id' => Str::uuid(),
            'amount' => $invoice->amount_due,
            'currency' => $invoice->currency,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update invoice
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

       //  Update all bookings linked to this invoice
    foreach ($invoice->bookings as $booking) {
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed', 
        ]);
        $booking->unit->update(['status' => 'booked']);
        $booking->room?->update(['availability_status' => 'occupied']);
        }

        // Generate lease
        try {
            $leaseService = new LeaseService();
            $lease = $leaseService->generateForInvoice($invoice);
        } catch (\Throwable $e) {
            \Log::error('Lease generation failed: ' . $e->getMessage());
            $lease = null;
        }

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Payment successful. ' . ($lease ? 'Lease generated.' : 'Lease generation failed, contact admin.'));
    }
}
