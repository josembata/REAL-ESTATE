<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'city',
        'region',
        'address',
        'latitude',
        'longitude',
        'cover_image',
        'title_deed_number',
        'agent_id',
    ];


    //property has many units relation
    public function units()
{
    return $this->hasMany(Unit::class);
}


    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities');
    }

public function owners()
{
    return $this->belongsToMany(Owner::class, 'ownerships')
        ->withPivot('ownership_type', 'share_percentage', 'purchase_date', 'status')
        ->withTimestamps();
}
public function ownerships()
{
    return $this->hasMany(Ownership::class);
}



    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function bookings()
{
    return $this->hasMany(Booking::class);
}

public function agent()
{
    return $this->belongsTo(User::class, 'agent_id');
}

public function landlords()
{
    return $this->hasManyThrough(
        Landlord::class,
        Ownership::class,
        'property_id', // Foreign key on ownerships table
        'id',           // Foreign key on landlords table
        'id',           // Local key on properties table
        'owner_id'      // Local key on ownerships table
    );
}

}

