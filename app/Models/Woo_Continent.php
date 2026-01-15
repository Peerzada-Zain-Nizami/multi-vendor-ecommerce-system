<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Woo_Continent extends Model
{
    use HasFactory;
    protected $table = "woo__continents";
    public function get_country()
    {
        return $this->hasMany(Woo_Country::class,'continent_id','id');
    }
}
