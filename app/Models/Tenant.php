<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'gender',
        'bio',
         'id_type',
        'id_number',
        'id_picture',
        'home_address',
        'professional',
        'work_address',
        'emergency_person_name',
        'emergency_person_contact',
        'bank_name',
        'account_number',
        'account_holder',
        'avatar',
        'profile_complete',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leases()
{
    return $this->hasMany(Lease::class, 'tenant_id');
}

}
