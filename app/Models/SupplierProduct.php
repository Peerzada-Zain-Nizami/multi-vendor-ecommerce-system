<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    use HasFactory;

    protected $table = 'supplier_products';
    public function products()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function suppliers_name()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
}
