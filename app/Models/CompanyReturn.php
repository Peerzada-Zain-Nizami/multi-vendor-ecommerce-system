<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyReturn extends Model
{
    use HasFactory;
    public function suppliers_name()
    {
        return $this->hasMany(User::class,'id','supplier_id');
    }
}
