<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = ['name', 'category_id', 'icon'];

    public function category()
    {
        return $this->belongsTo(AmenityCategory::class, 'category_id');
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_amenities');
    }
}

