<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPricePlan extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'name', 'price','category_id', 'currency'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
{
    return $this->belongsTo(PricePlanCategory::class, 'category_id');
}

public function bookings()
{
    return $this->hasMany(Booking::class, 'unit_price_plan_id');
}


}
