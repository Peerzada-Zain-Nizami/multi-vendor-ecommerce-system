<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCheckout extends Model
{
    use HasFactory;

    public function suppliers_name()
    {
        return $this->hasMany(User::class,'id','supplier_id');
    }
    public function invoicer_name()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
    public function supplier_product()
    {
        return $this->hasMany(SupplierProduct::class,'id','s_product_id');
    }
    public function company_product()
    {
        return $this->hasMany(Product::class,'id','product_id');
    }
}
