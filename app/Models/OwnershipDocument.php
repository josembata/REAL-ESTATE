<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnershipDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'ownership_id',
        'document_name',
        'document_type',
        'file_path',
        'verified_by',
        'verification_date',
        'status',
        'remarks'
    ];

    // Relationship to Ownership
    public function ownership()
    {
        return $this->belongsTo(Ownership::class);
    }

    // Relationship to Admin/User who verified
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
