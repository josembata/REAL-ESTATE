<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Invoice;
use App\Models\Booking;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LeaseController extends Controller
{

public function index()
{
    $leases = Lease::with([
        'property.ownerships.owner.user', 
        'tenant'
    ])
    ->where('user_id', auth()->id())
    ->get();

    return view('leases.index', compact('leases'));
}


    public function show(Lease $lease)
    {
        // ensure only tenant or owner can view
        $user = auth()->user();
        if ($lease->user_id !== $user->id && $lease->owner_id !== $user->id) {
            abort(403);
        }

        return view('leases.show', compact('lease'));
    }

    public function download(Lease $lease)
    {
        $user = auth()->user();
        if ($lease->user_id !== $user->id && $lease->owner_id !== $user->id) {
            abort(403);
        }

        if (!$lease->file_path || !Storage::disk('public')->exists($lease->file_path)) {
            abort(404, 'Lease file not found.');
        }

        return response()->file(storage_path('app/public/'.$lease->file_path));
    }


public function renewLease(Request $request, $leaseId)
{
    $lease = Lease::with(['unit', 'unit.pricePlans', 'booking'])->findOrFail($leaseId);

    $validated = $request->validate([
        'new_term_end' => 'required|date|after:' . $lease->term_end,
    ]);

    $renewStart = new \DateTime($lease->term_end);
    $renewEnd = new \DateTime($validated['new_term_end']);
    $diffDays = $renewStart->diff($renewEnd)->days;

    if ($diffDays <= 0) {
        return back()->with('error', 'Invalid renewal period.');
    }

    // Get price plan used in the original booking
    $pricePlan = $lease->booking->unitPricePlan;
    if (!$pricePlan) {
        return back()->with('error', 'No price plan found for this lease.');
    }

    // Calculate renewal amount dynamically
    $total = 0;
    switch (strtolower($pricePlan->category->name)) {
        case 'daily':
            $total = $diffDays * $pricePlan->price;
            break;
        case 'weekly':
            $diffWeeks = ceil($diffDays / 7);
            $total = $diffWeeks * $pricePlan->price;
            break;
        case 'monthly':
            $diffMonths = ceil($diffDays / 30);
            $total = $diffMonths * $pricePlan->price;
            break;
        case 'yearly':
            $diffYears = ceil($diffDays / 365);
            $total = $diffYears * $pricePlan->price;
            break;
        default:
            $total = $diffDays * $pricePlan->price;
            break;
    }

    // Create renewal record (or update lease)
    $oldTermEnd = $lease->term_end; // store previous term_end

    $lease->update([
        'previous_term_end' => $oldTermEnd,
        'term_end' => $validated['new_term_end'],
        'status' => 'draft',
        'renewal_amount' => $total,
    ]);

    // Create booking-like record for payment
    $renewalBooking = Booking::create([
        'unit_id' => $lease->unit_id,
        'property_id' => $lease->unit->property_id,
        'customer_id' => auth()->id(),
        'unit_price_plan_id' => $pricePlan->id,
        'check_in' => $oldTermEnd,
        'check_out' => $validated['new_term_end'],
        'total_amount' => $total,
        'currency' => $pricePlan->currency,
        'status' => 'pending',
        'payment_status' => 'unpaid',
    ]);

    // Add to invoice
    $invoiceController = new InvoiceController();
    $invoiceController->addBookingToInvoice($renewalBooking);

    return redirect()->route('bookings.user')
        ->with('success', 'Lease renewal created. Please complete payment to confirm.');
}



public function showRenewForm($id)
{
    $lease = Lease::with('property', 'user')->findOrFail($id);

    if ($lease->user_id !== auth()->id()) {
        return back()->with('error', 'Unauthorized access.');
    }

    return view('leases.renew', compact('lease'));
}


}
