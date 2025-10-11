<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    protected $fillable = [
        'lease_number',
        'invoice_id',
        'user_id',
        'property_id',
        'owner_id',
        'file_path',
        'term_start',
        'term_end',
        'signed_at',
        'status',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
