<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;
    protected $table = 'shelf';
    function count_product()
    {
        return $this->hasMany(Placement::class,'shelf_id','id');
    }
    function warehouse_get()
    {
        return $this->hasMany(Warehouse::class,'id','warehouse_id');
    }
    function block_get()
    {
        return $this->hasMany(Room_Block::class,'id','block_id');
    }
    function rack_get()
    {
        return $this->hasMany(Rack::class,'id','rack_id');
    }
}
