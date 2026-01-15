<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room_Block extends Model
{
    use HasFactory;
    protected $table = 'room_blocks';
    function count_rack()
    {
        return $this->hasMany(Rack::class,'block_id','id');
    }
}
