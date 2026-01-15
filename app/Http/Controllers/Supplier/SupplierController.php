<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\CompanyOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $new_orders = CompanyOrder::where(['supplier_id'=>$user,'status'=>'New Order'])->count();
        $completed = CompanyOrder::where(['supplier_id'=>$user,'status'=>'Complete'])->count();
        $cancel_reject = CompanyOrder::where(['supplier_id'=>$user,'status'=>['Cancel','Reject']])->count();
        $process = CompanyOrder::where('supplier_id',$user)->whereNotIn('status',['New Order','Complete','Cancel','Reject'])->count();
        $results = CompanyOrder::where('supplier_id',$user)->orderBy('id','desc')->limit(10)->get();

        return view('Supplier.index',compact('results','new_orders','completed','cancel_reject','process'));
    }

}
