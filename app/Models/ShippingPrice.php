<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingPrice extends Model
{
    use HasFactory;
    protected $table = "shipping_prices";
    public function cities_name()
    {
        return $this->hasMany(shipping::class,'id','city_id');
    }
    public function group_cities()
    {
        return $this->hasMany(shipping::class,'id','city_id');
    }
    public function group_price()
    {
        return $this->hasOne(ShippingGroup::class,'id','group_id');
    }
}
