<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placement_list extends Model
{
    use HasFactory;
    protected $table = 'placement_lists';
    public function user_get()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
    public function shelf_get()
    {
        return $this->hasMany(Shelf::class,'id','shelf_id');
    }
    public function stock_in_get()
    {
        return $this->hasMany(StockIn::class,'id','stock_in_id');
    }
}
