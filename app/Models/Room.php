<?php
// app/Models/Room.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'room_id';
    
   protected $fillable = [
        'unit_id',
        'room_name',
        'room_type',
        'size_sqft',
        'price',
        'availability_status',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

public function bookings()
{
    return $this->hasMany(Booking::class, 'room_id', 'room_id');
}

public function images()
{
    return $this->hasMany(RoomImage::class, 'room_id', 'room_id');
}

public function pricePlans()
{
    return $this->hasMany(RoomPricePlan::class, 'room_id', 'room_id');
}


}