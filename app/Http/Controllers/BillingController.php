<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    // Show billing form
   public function create($unitId)
{
    return view('billing.create', ['unitId' => $unitId]);
}


    // Store or update billing info
   public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'billing_address' => 'required|string|max:255',
        'tax_id' => 'nullable|string|max:100',
        'payment_method' => 'required|string|max:50',
        'contact_name' => 'required|string|max:150',
        'contact_email' => 'required|email|max:150',
        'contact_phone' => 'required|string|max:50',
        'meta' => 'nullable|array',
        'unit_id' => 'required|exists:units,id', 
    ]);

    $billing = \App\Models\Billing::updateOrCreate(
        ['user_id' => $validated['user_id']],
        $validated
    );

    // Redirect to booking form with unit id
    return redirect()->route('bookings.create', ['unit' => $validated['unit_id']])
        ->with('success', 'Billing info saved successfully! Please continue with booking.'); 
}

}
