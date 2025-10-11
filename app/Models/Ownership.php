<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ownership extends Model
{
    protected $fillable = [
        'property_id', 'owner_id', 'ownership_type', 
        'share_percentage', 'purchase_date', 'status'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }


public function owner()
{
    return $this->belongsTo(Landlord::class, 'owner_id');
}


     // Relationship to Ownership Documents
    public function documents()
    {
        return $this->hasMany(OwnershipDocument::class);
    }


    public function leases()
{
    return $this->hasMany(Lease::class);
}

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

}

