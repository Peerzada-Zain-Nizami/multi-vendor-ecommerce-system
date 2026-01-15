<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCategory extends Model
{
    use HasFactory;
    public function children()
    {
        return $this->hasMany(SellerCategory::class,'parent_id')->with('children');
    }
}
