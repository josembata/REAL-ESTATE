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

        // Calculate total amount
        switch (strtolower($pricePlan->category->name)) {
            case 'daily':       $total = $diffDays * $pricePlan->price; break;
            case 'monthly':     $total = ceil($diffDays / 30) * $pricePlan->price; break;
            case 'quarterly':   $total = ceil($diffDays / 90) * $pricePlan->price; break;
            case 'semi-annual': $total = ceil($diffDays / 180) * $pricePlan->price; break;
            case 'yearly':      $total = ceil($diffDays / 365) * $pricePlan->price; break;
            default:            $total = $diffDays * $pricePlan->price;
        }

        // Create booking with expiry countdown
        $booking = Booking::create([
            'uuid'               => Str::uuid(),
            'unit_id'            => $unit->id,
            'room_id'            => $room->room_id,
            'property_id'        => $unit->property_id,
            'customer_id'        => auth()->id(),
            'agent_id'           => $unit->property->agent_id ?? null,
            'unit_price_plan_id' => null,
            'room_price_plan_id' => $pricePlan->id,
            'check_in'           => $validated['check_in'],
            'check_out'          => $validated['check_out'],
            'total_amount'       => $total,
            'currency'           => $pricePlan->currency,
            'status'             => 'pending',
            'payment_status'     => 'unpaid',
            'expires_at'         => now()->addMinutes(2), // 2-minute countdown
        ]);

        // Mark room as reserved
        $room->update(['availability_status' => 'reserved']);

        // Attach booking to invoice
        (new InvoiceController())->addBookingToInvoice($booking);

        return redirect()->route('bookings.user', $booking->id)
                         ->with('success', 'Room booking created. Complete payment within 5 minutes.');
    }

    /**
     * Show user bookings.
     */
    public function userBookings()
    {
        $user = auth()->user();

        $bookings = $user->bookings()->with(['unit', 'property', 'room', 'roomPricePlan'])->get();
        $invoice = $user->invoices()->whereIn('status', ['unpaid','partially_paid'])->first();

        return view('bookings.user-bookings', compact('bookings', 'invoice'));
    }

    /**
     * Restore a cancelled booking.
     */
    public function restore(Booking $booking)
    {
        if ($booking->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled bookings can be restored.');
        }

        $booking->update([
            'status' => 'pending',
            'cancelled_at' => null,
            'expires_at' => now()->addMinutes(5),
        ]);

        if ($booking->room) {
            $booking->room->update(['availability_status' => 'reserved']);
        }

        $invoiceController = new InvoiceController();
        $invoiceController->addBookingToInvoice($booking);

        return back()->with('success', 'Room booking restored and added back to invoice.');
    }

     public function cancel(Booking $booking)
    {
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        $booking->room->update(['availability_status' => 'available']);

        //remove from invoice
    $invoiceController = new InvoiceController();
    $invoiceController->removeBookingFromInvoice($booking);

        return back()->with('success', 'Booking cancelled & unit available again.');
    }

    /**
     * Auto-cancel booking when countdown expires.
     */
    public function autoCancel(Booking $booking)
    {
        if ($booking->status === 'pending' && $booking->payment_status === 'unpaid' && $booking->expires_at && $booking->expires_at->isPast()) {
            // Cancel the booking
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Free the room
            if ($booking->room) {
                $booking->room->update(['availability_status' => 'available']);
            }

            // Remove from invoice
            $invoiceController = new InvoiceController();
            $invoiceController->removeBookingFromInvoice($booking);

            return response()->json(['success' => true, 'message' => 'Booking cancelled automatically']);
        }

        return response()->json(['success' => false, 'message' => 'Booking still valid or already paid']);
    }
}
