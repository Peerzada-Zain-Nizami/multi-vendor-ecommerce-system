<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Final_Stock extends Model
{
    use HasFactory;
    protected $table = 'final__stocks';

    public function get_products()
    {
        return $this->hasMany(StockIn::class,'id','stock_ins_id');
    }
}
