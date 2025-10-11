<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'uuid',
        'unit_id',
        'room_id',
        'property_id',
        'customer_id',
        'agent_id',
        'unit_price_plan_id',
        'room_price_plan_id',
        'check_in',
        'check_out',
        'total_amount',
        'currency',
        'status',
        'payment_status',
        'cancelled_at',
    ];

    protected static function booted()
    {
        static::creating(function ($booking) {
            $booking->uuid = Str::uuid();
        });
    }

public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }


    // Relationships
   public function unit()
{
    return $this->belongsTo(Unit::class);
}
      public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function agent() { return $this->belongsTo(User::class, 'agent_id'); }

// For unit-level booking
public function unitPricePlan()
{
    return $this->belongsTo(UnitPricePlan::class, 'unit_price_plan_id'); 
}

// For room-level booking
public function roomPricePlan()
{
    return $this->belongsTo(RoomPricePlan::class, 'room_price_plan_id'); 
}

// Return whichever plan applies (unit or room)
public function pricePlan()
{
    if ($this->unit_price_plan_id) {
        return $this->unitPricePlan();
    } elseif ($this->room_price_plan_id) {
        return $this->roomPricePlan();
    }
    return null;
}

public function invoiceBookings()
{
    return $this->hasMany(InvoiceBooking::class);
}

// public function invoices()
// {
//     return $this->belongsToMany(Invoice::class, 'invoice_bookings')
//                 ->withPivot('amount')
//                 ->withTimestamps();
// }

public function invoices()
{
    return $this->belongsToMany(Invoice::class, 'invoice_booking')
                ->withPivot('amount')
                ->withTimestamps();
}

public function transaction()
{
    return $this->hasOne(Transaction::class); 
}






}
