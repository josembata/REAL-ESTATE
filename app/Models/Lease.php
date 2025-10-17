<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lease extends Model
{
    protected $fillable = [
        'lease_number',
        'invoice_id',
        'user_id',
        'property_id',
        'unit_id',
        'booking_id',
        'owner_id',
        'file_path',
        'term_start',
        'term_end',
        'signed_at',
        'status',
        'renewal_amount',
        'previous_term_end',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

   public function owner()
{
    return $this->belongsTo(Owner::class);
}

public function tenant()
{
    return $this->belongsTo(User::class, 'tenant_id');
}

public function property()
{
    return $this->belongsTo(Property::class);
}

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

        public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function hasExpired()
    {
        return Carbon::now()->greaterThanOrEqualTo(Carbon::parse($this->term_end));
    }

    public function isRenewedWithinGracePeriod()
    {
        return $this->status === 'renewed' &&
               Carbon::parse($this->term_end)->diffInDays(Carbon::now()) <= 7;
    }

    // app/Models/Lease.php

public function canBeRenewed()
{
    $today = now();
    $termEnd = Carbon::parse($this->term_end);

    // Can renew if it's not expired more than 7 days ago
    return $today->lessThanOrEqualTo($termEnd->copy()->addDays(7));
}

}
