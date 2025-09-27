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

     // Relation: Unit has many images
// App\Models\Unit.php

public function unitImages()
{
    return $this->hasMany(UnitImage::class, 'unit_id'); 
}

public function rooms()
{
    return $this->hasMany(Room::class, 'unit_id', 'id');
}


public function pricePlans()
{
    return $this->hasMany(UnitPricePlan::class);
}





}
