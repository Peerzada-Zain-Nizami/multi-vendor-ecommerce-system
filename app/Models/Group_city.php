<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_city extends Model
{
    use HasFactory;
    protected $table = "group_cities";
    public function shipping_cities()
    {
        return $this->hasMany(shipping::class,'id','city_id');
    }
    public function shipping_price()
    {
        $shipping_company = ShippingCompany::where('name','SMSA')->first();
        return $this->hasOne(ShippingPrice::class,'group_id','group_id')->where('shipping_company',$shipping_company->id);
    }
}
