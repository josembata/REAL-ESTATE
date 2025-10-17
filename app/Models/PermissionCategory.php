<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(\Spatie\Permission\Models\Permission::class, 'permission_category_id');
    }
}
