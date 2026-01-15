<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasFactory;
    protected $table = 'racks';
    function count_shelf()
    {
        return $this->hasMany(Shelf::class,'rack_id','id');
    }
}
