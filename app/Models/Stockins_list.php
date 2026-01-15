<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockins_list extends Model
{
    use HasFactory;
    protected $table = 'stock_ins_list';

    public function warehouse_relation()
    {
        return $this->hasOne(Warehouse::class,'id','warehouse_id');
    }
    public function get_supplier()
    {
        return $this->hasMany(User::class,'id','supplier_id');
    }
    public function get_products()
    {
        return $this->hasMany(Product::class,'id','product_id');
    }
    public function final_stock()
    {
        return $this->hasMany(Final_Stock::class,'stock_ins_id','stock_ins_id');
    }
}
