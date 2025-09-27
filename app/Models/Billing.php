<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'billing_address',
        'tax_id',
        'payment_method',
        'contact_name',
        'contact_email',
        'contact_phone',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // Relationship: billing belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

