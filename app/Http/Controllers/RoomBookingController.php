<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Room;
use App\Models\RoomPricePlan;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RoomBookingController extends Controller
{
  public function create(Unit $unit, Room $room)
{
    // Fetch only room-specific price plans
    $pricePlans = $room->pricePlans; 
    return view('rooms.book', compact('unit', 'room', 'pricePlans'));
}

public function store(Request $request, Unit $unit, Room $room)
{
    $validated = $request->validate([
        'room_price_plan_id' => 'required|exists:room_price_plans,id',
        'check_in'      => 'required|date',
        'check_out'     => 'required|date|after:check_in',
    ]);

    $pricePlan = RoomPricePlan::findOrFail($validated['room_price_plan_id']);

    // Calculate total based on category
    $checkIn = \Carbon\Carbon::parse($validated['check_in']);
    $checkOut = \Carbon\Carbon::parse($validated['check_out']);
    $diffDays = $checkIn->diffInDays($checkOut);

    switch (strtolower($pricePlan->category->name)) {
        case 'daily': $total = $diffDays * $pricePlan->price; break;
        case 'monthly': $total = ceil($diffDays / 30) * $pricePlan->price; break;
        case 'quataly': $total = ceil($diffDays / 3*30) * $pricePlan->price; break;
        case 'semi-annual': $total = ceil($diffDays / 180) * $pricePlan->price; break;
        case 'yearly': $total = ceil($diffDays / 365) * $pricePlan->price; break;
        default: $total = $diffDays * $pricePlan->price;
    }

    $booking = Booking::create([
        'uuid'          => \Str::uuid(),
        'unit_id'       => $unit->id,
        'room_id'       => $room->room_id,
        'property_id'   => $unit->property_id,
        'customer_id'   => auth()->id(),
        'agent_id'      => $unit->property->agent_id ?? null,
        'unit_price_plan_id' => null,
        'room_price_plan_id' => $pricePlan->id,
        'check_in'      => $validated['check_in'],
        'check_out'     => $validated['check_out'],
        'total_amount'  => $total,
        'currency'      => $pricePlan->currency,
        'status'        => 'pending',
        'payment_status'=> 'unpaid',
    ]);

    // Mark room reserved
    $room->update(['availability_status' => 'reserved']);

    return redirect()->route('bookings.show', $booking->id)
                     ->with('success', 'Booking created! Room reserved.');
}


}
