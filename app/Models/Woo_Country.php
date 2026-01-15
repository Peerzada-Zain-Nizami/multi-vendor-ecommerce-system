<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Woo_Country extends Model
{
    use HasFactory;
    protected $table = "woo__countries";
    public function get_states()
    {
        return $this->hasMany(Woo_State::class,'country_id','id');
    }
}
