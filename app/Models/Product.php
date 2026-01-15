<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    public function get_suppliers()
    {
        return $this->hasMany(SupplierProduct::class,'product_id','id')->where('supplier_products.status',"Available");
    }
    public function category_relation()
    {
        return $this->hasOne(Category::class,'category_name','category');
    }
}
