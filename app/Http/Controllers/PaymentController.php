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
    $tenant = auth()->user()->tenant; // 

    return view('payments.choose', compact('invoice', 'tenant'));
    }

    // Process Mobile Money payment
    public function mobile(Request $request, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'provider' => $request->provider, // mpesa/tigopesa
            'provider_payment_id' => Str::uuid(), // simulate transaction ref
            'amount' => $invoice->amount_due,
            'currency' => $invoice->currency,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update invoice status
        $invoice->update(['status' => 'paid', 'paid_at' => now()]);

          // generate lease (synchronously)
    try {
        $leaseService = new LeaseService();
        $lease = $leaseService->generateForInvoice($invoice);
    } catch (\Throwable $e) {
        // log the error but continue - you may want to notify admin
        \Log::error('Lease generation failed: '.$e->getMessage());
        $lease = null;
    }

      
    return redirect()->route('invoices.show', $invoice->id)
        ->with('success', 'Payment successful. ' . ($lease ? 'Lease generated.' : 'Lease generation failed, contact admin.'));
    }

    // Process Card Payment
    public function card(Request $request, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'provider' => 'card',
            'provider_payment_id' => Str::uuid(), // normally from gateway
            'amount' => $invoice->amount_due,
            'currency' => $invoice->currency,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        $invoice->update(['status' => 'paid', 'paid_at' => now()]);


       // generate lease (synchronously)
    try {
        $leaseService = new LeaseService();
        $lease = $leaseService->generateForInvoice($invoice);
    } catch (\Throwable $e) {
        // log the error but continue - you may want to notify admin
        \Log::error('Lease generation failed: '.$e->getMessage());
        $lease = null;
    }

      
    return redirect()->route('invoices.show', $invoice->id)
        ->with('success', 'Payment successful. ' . ($lease ? 'Lease generated.' : 'Lease generation failed, contact admin.'));
    }
}

