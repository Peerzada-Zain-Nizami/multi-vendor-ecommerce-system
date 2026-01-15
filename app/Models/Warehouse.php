<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    public function cityRelation()
    {
        return $this->hasOne(shipping::class,'id','city');
    }
    public function blocks()
    {
        return $this->hasMany(Room_Block::class,'warehouse_id','id');
    }
    public function racks()
    {
        return $this->hasMany(Rack::class,'warehouse_id','id');
    }
    public function shelfs()
    {
        return $this->hasMany(Shelf::class,'warehouse_id','id');
    }
}
