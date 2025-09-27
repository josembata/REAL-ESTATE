<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Booking;
use App\Models\UnitPricePlan;
use Illuminate\Http\Request;

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
            'room_price_plan_id' => null, 
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'total_amount' => $total,
            'currency' => $pricePlan->currency,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Mark the unit as booked 
        $unit->update(['status' => 'booked']);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Booking created successfully! Unit reserved.');
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

        $booking->unit->update(['availability_status' => 'available']);

        return back()->with('success', 'Booking cancelled & unit available again.');
    }

    public function show($id)
    {
        $booking = Booking::with(['property', 'unit', 'customer', 'agent', 'unitPricePlan.category'])
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }
}
