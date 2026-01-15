<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundOrder extends Model
{
    use HasFactory;
    protected $table = "refund_orders";
    public function order()
    {
        return $this->hasMany(Orders::class,'id','order_id');
    }
}
