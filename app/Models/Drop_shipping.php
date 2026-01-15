<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drop_shipping extends Model
{
    use HasFactory;
    public function get_products()
    {
        return $this->hasMany(Product::class,'id','product_id');
    }
    public function get_stock()
    {
        return $this->hasMany(Stock::class,'product_id','product_id');
    }
}
