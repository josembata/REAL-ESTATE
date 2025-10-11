<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceBooking extends Model
{
    protected $fillable = ['invoice_id', 'booking_id', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

