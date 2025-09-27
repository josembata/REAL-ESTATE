<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- import trait

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles; // <-- add HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'gender',
        'bio',
        'status',
        // remove 'role' once migration is complete
        'profile_complete',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'profile_complete' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Auto-generate uuid
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = \Illuminate\Support\Str::uuid()->toString();
            }
        });
    }

    /**
     * Relationship: one-to-one with Agent (still valid)
     */
   public function agent()
{
    return $this->hasOne(Agent::class);
}

public function landlord()
{
    return $this->hasOne(Landlord::class);
}

public function staff()
{
    return $this->hasOne(Staff::class);
}
public function tenant()
{
    return $this->hasOne(Tenant::class);
}

}
