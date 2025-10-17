<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'permission_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(PermissionCategory::class, 'permission_category_id');
    }
}
