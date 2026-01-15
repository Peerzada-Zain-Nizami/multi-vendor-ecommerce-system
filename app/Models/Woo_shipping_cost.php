<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Woo_shipping_cost extends Model
{
    use HasFactory;
    protected $table = 'woo_shipping_costs';
    public function get_method()
    {
        return $this->hasMany(Woo_shipping_method::class,'id','shipping_method_id');
    }
    public function get_class()
    {
        return $this->hasMany(woo_shipping_setups::class,'id','shipping_class_id');
    }
}
