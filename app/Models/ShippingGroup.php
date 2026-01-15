<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingGroup extends Model
{
    use HasFactory;
    protected $table = "shipping_groups";
    public function shipping_cities()
    {
        return $this->hasMany(ShippingPrice::class,'group_id','id');
    }
    public function group_cities()
    {
        return $this->hasMany(Group_city::class,'group_id','id')->with('shipping_cities');
    }
}
