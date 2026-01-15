<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCity extends Model
{
    use HasFactory;
    protected $table = "seller_cities";
    public function get_city_name()
    {
        return $this->hasOne(shipping::class,'id','admin_city_id');
    }
}
