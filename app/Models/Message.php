<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['inquiry_id','sender_id','body','attachments','read_at'];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function inquiry() {
        return $this->belongsTo(Inquiry::class);
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

