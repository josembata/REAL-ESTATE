<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Owner extends Model
{
    use Notifiable;
    protected $fillable = ['name', 'type', 'phone', 'email', 'address', 'national_id'];

   public function properties()
{
    return $this->belongsToMany(Property::class, 'ownerships')
        ->withPivot('ownership_type', 'share_percentage', 'purchase_date', 'status')
        ->withTimestamps();
}


    public function leases()
{
    return $this->hasMany(Lease::class);
}

}

