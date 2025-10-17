<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Booking;
use App\Models\UnitPricePlan;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    public function index()
    {
        // Load bookings with related unit + price plan
        $bookings = Booking::with(['customer', 'property', 'unit', 'unitPricePlan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    // Show booking form for a unit
    public function create(Unit $unit)
    {
        $pricePlans = $unit->pricePlans()->with('category')->get();

        return view('bookings.create', compact('unit', 'pricePlans'));
    }

    // Store booking (entire unit)
    public function store(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'unit_price_plan_id' => 'required|exists:unit_price_plans,id',
        ]);

        // Fetch selected price plan
        $pricePlan = UnitPricePlan::with('category')->findOrFail($validated['unit_price_plan_id']);

        $checkIn = new \DateTime($validated['check_in']);
        $checkOut = new \DateTime($validated['check_out']);
        $diffDays = $checkIn->diff($checkOut)->days;

        if ($diffDays <= 0) {
            $diffDays = 1; // charge at least 1 day
        }

        // Compute total based on category
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

     // Create booking for entire unit
$booking = Booking::create([
    'unit_id' => $unit->id,
    'property_id' => $unit->property_id,
    'customer_id' => auth()->id(),
    'agent_id' => $unit->property->agent_id ?? null,
    'unit_price_plan_id' => $pricePlan->id,
    'check_in' => $validated['check_in'],
    'check_out' => $validated['check_out'],
    'total_amount' => $total,
    'currency' => $pricePlan->currency,
    'status' => 'pending',
    'payment_status' => 'unpaid',
    // 'expires_at' => now()->addHour(), //  1 hour hold
     'expires_at' => now()->addMinutes(2),
]);

// Mark the unit as unavailable
$unit->update(['status' => 'unavailable']);

// Attach to invoice automatically
$invoiceController = new InvoiceController();
$invoiceController->addBookingToInvoice($booking);


        return redirect()->route('bookings.user')
            ->with('success', 'Booking created successfully!.');
    }

    public function confirm(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        $booking->unit->update(['availability_status' => 'occupied']);

        return back()->with('success', 'Booking confirmed & unit occupied.');
    }



    public function cancel(Booking $booking)
    {
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        $booking->unit->update(['status' => 'available']);

        //remove from invoice
    $invoiceController = new InvoiceController();
    $invoiceController->removeBookingFromInvoice($booking);

        return back()->with('success', 'Booking cancelled & unit available again.');
    }


    public function show($id)
    {
        $booking = Booking::with(['property', 'unit', 'customer', 'agent', 'unitPricePlan.category'])
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }



    public function payment(Booking $booking)
{
    return view('bookings.payment', compact('booking'));
}






public function userBookings()
{
    $user = auth()->user();

    // Fetch all active bookings
    $bookings = $user->bookings()->with(['unit', 'property', 'unitPricePlan'])->get();

    // Fetch the user’s active invoice
    $invoice = $user->invoices()->whereIn('status', ['unpaid','partially_paid'])->first();

    return view('bookings.user-bookings', compact('bookings', 'invoice'));
}

public function restore(Booking $booking)
{
    // Only restore if previously cancelled
    if ($booking->status !== 'cancelled') {
        return back()->with('error', 'Only cancelled bookings can be restored.');
    }

    $booking->update([
        'status' => 'pending',
        'cancelled_at' => null,
    ]);

    // Mark unit unavailable again
    $booking->unit->update(['status' => 'available']);

    // Add booking amount back into invoice
    $invoiceController = new InvoiceController();
    $invoiceController->addBookingToInvoice($booking);

    return back()->with('success', 'Booking restored & added back to invoice.');
}




// Auto-cancel booking when countdown expires
public function autoCancel(Booking $booking)
{
    if ($booking->status === 'pending' && $booking->payment_status === 'unpaid') {
        // Cancel the booking
        $booking->update(['status' => 'cancelled']);

        // Free the unit
        $booking->unit->update(['status' => 'available']);

           //remove from invoice
    $invoiceController = new InvoiceController();
    $invoiceController->removeBookingFromInvoice($booking);

        return response()->json(['success' => true, 'message' => 'Booking cancelled automatically']);
    }

    return response()->json(['success' => false]);
}




}
