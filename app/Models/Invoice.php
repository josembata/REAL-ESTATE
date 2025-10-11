<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id', 'invoice_number', 'amount_due', 'currency', 
        'status', 'issued_at', 'due_date', 'paid_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function bookings()
{
    return $this->belongsToMany(Booking::class, 'invoice_bookings')
                ->withPivot('amount')
                ->withTimestamps();
}

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

}

