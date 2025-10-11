<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Room;
use App\Models\RoomPricePlan;
use App\Models\Booking;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RoomBookingController extends Controller
{
    /**
     * Show booking form for a specific room.
     */
    public function create(Unit $unit, Room $room)
    {
        $pricePlans = $room->pricePlans;
        return view('rooms.book', compact('unit', 'room', 'pricePlans'));
    }

    /**
     * Store a room booking.
     */
    public function store(Request $request, Unit $unit, Room $room)
    {
        $validated = $request->validate([
            'room_price_plan_id' => 'required|exists:room_price_plans,id',
            'check_in'           => 'required|date',
            'check_out'          => 'required|date|after:check_in',
        ]);

        $pricePlan = RoomPricePlan::findOrFail($validated['room_price_plan_id']);

        $checkIn  = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $diffDays = $checkIn->diffInDays($checkOut);

        // Calculate total
        switch (strtolower($pricePlan->category->name)) {
            case 'daily':      $total = $diffDays * $pricePlan->price; break;
            case 'monthly':    $total = ceil($diffDays / 30) * $pricePlan->price; break;
            case 'quarterly':  $total = ceil($diffDays / 90) * $pricePlan->price; break;
            case 'semi-annual':$total = ceil($diffDays / 180) * $pricePlan->price; break;
            case 'yearly':     $total = ceil($diffDays / 365) * $pricePlan->price; break;
            default:           $total = $diffDays * $pricePlan->price;
        }

        $booking = Booking::create([
            'uuid'                => Str::uuid(),
            'unit_id'             => $unit->id,
            'room_id'             => $room->room_id,
            'property_id'         => $unit->property_id,
            'customer_id'         => auth()->id(),
            'agent_id'            => $unit->property->agent_id ?? null,
            'unit_price_plan_id'  => null,
            'room_price_plan_id'  => $pricePlan->id,
            'check_in'            => $validated['check_in'],
            'check_out'           => $validated['check_out'],
            'total_amount'        => $total,
            'currency'            => $pricePlan->currency,
            'status'              => 'pending',
            'payment_status'      => 'unpaid',
        ]);

        // Mark room reserved
        $room->update(['availability_status' => 'reserved']);

        // Attach booking to invoice
        (new InvoiceController())->addBookingToInvoice($booking);

        return redirect()->route('bookings.user', $booking->id)
                         ->with('success', 'Room booking created and added to invoice.');
    }

    /**
     * Cancel a booking (remove from invoice + free room).
     */
    public function cancel(Booking $booking)
    {
        $booking->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Free the room
        if ($booking->room) {
            $booking->room->update(['availability_status' => 'available']);
        }

        // Remove from invoice
        (new InvoiceController())->removeBookingFromInvoice($booking);

        return back()->with('success', 'Room booking cancelled & removed from invoice.');
    }

    /**
     * Restore a cancelled booking (add back to invoice + reserve room).
     */
    public function restore(Booking $booking)
    {
        $booking->update([
            'status'       => 'pending',
            'cancelled_at' => null,
        ]);

        // Mark room reserved again
        if ($booking->room) {
            $booking->room->update(['availability_status' => 'reserved']);
        }

        // Add back to invoice
        (new InvoiceController())->addBookingToInvoice($booking);

        return back()->with('success', 'Booking restored & amount added to invoice.');
    }
}
