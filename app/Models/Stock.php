<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    public function get_products()
    {
        return $this->hasMany(Product::class,'id','product_id');
    }
    public function product_relation()
    {
        return $this->hasOne(Product::class,'id','product_id')->with('category_relation');
    }
}
