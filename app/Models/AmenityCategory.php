<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenityCategory extends Model
{
    protected $fillable = ['name'];

    public function amenities()
    {
        return $this->hasMany(Amenity::class, 'category_id');
    }
}

