<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'experience_years',
        'commission_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function properties()
{
    return $this->hasMany(Property::class);
}

}
