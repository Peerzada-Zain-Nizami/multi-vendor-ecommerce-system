<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table = 'orders';
    public function shipping_company()
    {
        return $this->hasMany(shipping::class,'id','shipping_id');
    }
}
