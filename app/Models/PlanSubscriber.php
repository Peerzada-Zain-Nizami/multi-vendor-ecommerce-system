<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanSubscriber extends Model
{
    use HasFactory;
    protected $table = "plan_subscribers";
    protected $fillable = [
        'user_id',
        'plan_id',
        'plan_type',
        'payment'
    ];
    public function plan_get()
    {
        return $this->hasOne(Plan::class,'id','plan_id');
    }
}
