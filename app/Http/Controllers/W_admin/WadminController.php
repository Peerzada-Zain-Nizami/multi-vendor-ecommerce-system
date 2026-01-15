<?php

namespace App\Http\Controllers\W_admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyOrder;
use App\Models\Orders;
use App\Models\ShopifyProduct;
use App\Models\Stockins_list;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WadminController extends Controller
{
    public function index()
    {
    //     $users = User::where('role', 'Seller')->get();
    //     foreach ($users as $user) {
    //         $products = ShopifyProduct::where('user_id',$user->id)->get();
    //         foreach ($products as  $product) {
    //         $total_products = Stockins_list::where('product_id',  $product['product_id'])->sum('stock');
    //         $sold_products = Stockins_list::where('product_id',  $product['product_id'])->sum('sold');
    //         $reserved_products = Stockins_list::where('product_id',  $product['product_id'])->sum('reserved');
    //         $available_stock_in_warehouse = $reserved_products + $sold_products;
    //         $available_stock_for_customer =  $total_products - $available_stock_in_warehouse;



    //     }

    // }


        $user = Auth::user()->id;
        $lists = array();
        $warehouses0 = Warehouse::all();
        foreach ($warehouses0 as $warehouse)
        {
            if (in_array($user,json_decode($warehouse->responsible)))
            {
                $lists[] = $warehouse->id;
            }
        }
        $new_orders = Orders::where('receiver_wadmin',$user)->orwhere('status','Processing')->whereIn('payment',array('Return Received','Paid'))->whereIn('order_warehouse_id',$lists)->orwhere('picked_status','!=','DELIVERED')->count();
        $completed = Orders::where(['receiver_wadmin'=>$user,'order_status'=>'Complete'])->count();
        $refund_completed = Orders::where(['receiver_wadmin'=>$user,'refund_status'=>'Complete'])->count();
        $cancel_reject = Orders::where(['receiver_wadmin'=>$user,'order_status'=>['Cancel','Cancelled','Reject']])->orwhere('refund_status','Complete')->count();
        $process = Orders::where('receiver_wadmin',$user)->where('order_status',['Packing','Shipping Process'])->count();
        $results = Orders::where('status' , 'Processing')->whereIn('payment',array('Return Received','Paid'))->whereIn('order_warehouse_id',$lists)->orderBy('id','desc')->limit(10)->get();
        // $results = Orders::where('receiver_wadmin',$user)->orwhere('status' , 'Processing')->whereIn('payment',array('Return Received','Paid'))->whereIn('order_warehouse_id',$lists)->orderBy('id','desc')->limit(10)->get();
//        $companyorder = CompanyOrder::where('receiver_wadmin',$user)->orderBy('id','desc')->limit(10)->get();

        return view('W_admin.index',compact('refund_completed','results','new_orders','completed','cancel_reject','process'));
    }

}
