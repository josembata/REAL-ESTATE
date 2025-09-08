<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_name',
        'description',
        'price',
        'currency',
        'unit_type',
        'furnishing',
        'status',
        'size_sqft',
        'furnished',
    ];

    //each unit depend on one property
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
