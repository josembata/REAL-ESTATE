<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'property_id','unit_id','tenant_id','agent_id','subject','status','closed_at'
    ];

    public function property() {
        return $this->belongsTo(Property::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    public function tenant() {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function agent() {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
