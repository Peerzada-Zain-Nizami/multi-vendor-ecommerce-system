<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Woo_Tax_Setup extends Model
{
    use HasFactory;
    protected $table = 'woo__tax__setups';
    public function tax_name()
    {
        return $this->hasMany(Tax::class,'id','tax_id');
    }
}
