<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Drop_shipping;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\PlanSubscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $new_orders = Orders::where(['user_id'=>$user,'status'=>'New Order'])->orwhere('status','Pending')->count();
        $completed = Orders::where(['user_id'=>$user,'order_status'=>'Complete'])->count();
        $refund_completed = Orders::where(['user_id'=>$user,'refund_status'=>'Complete'])->count();
        $cancel_reject = Orders::where(['user_id'=>$user,'order_status'=>['Cancel','Cancelled','Reject']])->orwhere('refund_status','Complete')->count();
        $process = Orders::where('user_id',$user)->whereNotIn('status',['New Order','Complete','Cancel','Cancelled','Reject'])->count();
        $results = Orders::where('user_id',$user)->where('status','New Order')->orwhere('status','Pending')->orderBy('id','desc')->limit(10)->get();
        $plan_subscriber = PlanSubscriber::where('user_id',$user)->first();
        $plan = Plan::find($plan_subscriber->plan_id);
        $plan_subscriber = PlanSubscriber::where('user_id',$user)->first();
        $drop_shipping = Drop_shipping::where('user_id',$user)->with('get_products','get_stock')->limit(10)->get();
        return view('Seller.index',compact('refund_completed','plan_subscriber','drop_shipping','plan','results','new_orders','completed','cancel_reject','process'));
    }

}
