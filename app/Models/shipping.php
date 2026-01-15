<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class shipping extends Model
{
    use HasFactory;
    protected $table = 'shippings';
    protected $fillable = ['our_system_cities'];

    public function get_seller_cities()
    {
        $id = Auth::user()->id;
        return $this->hasMany(SellerCity::class,'admin_city_id','id')->where('seller_id',$id);
    }
    public function get_seller_cities_for_admin()
    {
        return $this->hasMany(SellerCity::class,'admin_city_id','id');
    }
}
