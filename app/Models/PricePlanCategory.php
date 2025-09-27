<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricePlanCategory extends Model
{
    protected $fillable = ['name'];

    public function unitPricePlans()
    {
        return $this->hasMany(UnitPricePlan::class, 'category_id');
    }
}
