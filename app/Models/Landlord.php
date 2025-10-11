<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Landlord extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'company_name',
        'address',
        'tax_id',
        'bank_account',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function properties()
{
    return $this->hasMany(Property::class);
}

public function ownerships()
{
    return $this->hasMany(Ownership::class, 'owner_id');
}

}

