<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RoomPricePlan extends Model
{
    protected $fillable = ['room_id', 'category_id', 'price', 'currency'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function category()
    {
        return $this->belongsTo(PricePlanCategory::class, 'category_id');
    }

     public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_price_plan_id');
    }
}

