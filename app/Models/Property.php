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


}

