<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plan;
use App\Models\PlanPayment;
use App\Models\PlanSubscriber;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;
use Session;

class PlanController extends Controller
{
    // plan
    public function index()
    {
        $category = Category::all();
        return view('Admin.add_plane',['category'=>$category]);
    }
    public function add_plan(Request $request)
    {
        if ($request->plan_name == "Free")
        {
            $validator = Validator::make($request->all(), [
                'plan_name' => ['required', 'string', 'unique:plans,name'],
                'no_P_list' => ['required'],
                'push_product_by_hour' => ['required'],
                'push_product_by_day' => ['required'],
                'plateform_sync' => ['required'],
                'shipping_price_disc' => ['required'],
                'cancel_price_discount' => ['required'],
                'status' => ['required'],
                'currency' => ['required'],
                'category' => ['required'],
                'price' => ['required'],
                'plan_yearly_price' => ['required'],
                'plan_monthly_price' => ['required'],
            ], [], [
                'plan_name' => 'Plan Name',
                'no_P_list' => 'Number of Product in List',
                'push_product_by_hour' => 'Push Product by Hour',
                'push_product_by_day' => 'Push Product by Day',
                'plateform_sync' => 'Platform Sync',
                'shipping_price_disc' => 'Shipping Price Discount',
                'shipping_price_disc_method' => 'Shipping Price Discount Method',
                'cancel_price_discount' => 'Cancel Price Discount',
                'cancel_price_discount_method' => 'Cancel Price Discount Method',
                'status' => 'Status',
                'currency' => 'Currency',
                'category' => 'Category',
                'price' => 'Price',
                'plan_yearly_price' => 'Yearly Price',
                'plan_monthly_price' => 'Monthly Price',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => "fail",
                    'errors' => $validator->errors(),
                ]);
            } else {
            $product_data = array();
            $count = count($request->category);
            for ($i=0;$i<$count;$i++)
            {
                $category = $request->category[$i];
                $price = $request->price[$i];
                $method = 'fixed_price';
                $product_data[] =json_encode([
                    'category'=>$category,
                    'price'=>$price,
                    'method'=>$method,
                ]);
            }
            $shipping_price = [
                'discount'=>$request->shipping_price_disc,
                'method'=> 'fixed_price'
            ];
            $cancel_price = [
                'discount'=>$request->cancel_price_discount,
                'method'=> 'fixed_price'
            ];
            $push_product = [
                'push_product_by_hour'=>$request->push_product_by_hour,
                'push_product_by_day'=>$request->push_product_by_day,
            ];
            $add = new Plan();
            $add->name = $request->plan_name;
            $add->listing_product = $request->no_P_list;
            $add->push_product = json_encode($push_product);
            $add->plateform_sync = $request->plateform_sync;
            $add->product_price = json_encode($product_data);
            $add->shipping_price = json_encode($shipping_price);
            $add->order_cancellation = json_encode($cancel_price);
            $add->currency = $request->currency;
            $add->status = $request->status;
            $add->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Plan added Successful.'
                ]);
        }
        }
        else{
            $validator = Validator::make($request->all(), [
                'plan_name' => ['required', 'string', 'unique:plans,name'],
                'no_P_list' => ['required'],
                'push_product_by_hour' => ['required'],
                'push_product_by_day' => ['required'],
                'plateform_sync' => ['required'],
                'shipping_price_disc' => ['required'],
                // 'shipping_price_disc_method' => ['required'],
                'cancel_price_discount' => ['required'],
                // 'cancel_price_discount_method' => ['required'],
                'status' => ['required'],
                'currency' => ['required'],
                'category' => ['required'],
                'price' => ['required'],
                'plan_yearly_price' => ['required'],
                'plan_monthly_price' => ['required'],
            ], [], [
                'plan_name' => 'Plan Name',
                'no_P_list' => 'Number of Product in List',
                'push_product_by_hour' => 'Push Product by Hour',
                'push_product_by_day' => 'Push Product by Day',
                'plateform_sync' => 'Platform Sync',
                'shipping_price_disc' => 'Shipping Price Discount',
                'shipping_price_disc_method' => 'Shipping Price Discount Method',
                'cancel_price_discount' => 'Cancel Price Discount',
                'cancel_price_discount_method' => 'Cancel Price Discount Method',
                'status' => 'Status',
                'currency' => 'Currency',
                'category' => 'Category',
                'price' => 'Price',
                'plan_yearly_price' => 'Yearly Price',
                'plan_monthly_price' => 'Monthly Price',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => "fail",
                    'errors' => $validator->errors(),
                ]);
            } else {
                $product_data = array();
                $count = count($request->category);
                for ($i = 0; $i < $count; $i++) {
                    $category = $request->category[$i];
                    $price = $request->price[$i];
                    $method = 'fixed_price';
                    $product_data[] = json_encode([
                        'category' => $category,
                        'price' => $price,
                        'method' => $method,
                    ]);
                }
                $shipping_price = [
                    'discount' => $request->shipping_price_disc,
                    'method' => 'fixed_price'
                ];
                $cancel_price = [
                    'discount' => $request->cancel_price_discount,
                    'method' => 'fixed_price'
                ];
                $push_product = [
                    'push_product_by_hour' => $request->push_product_by_hour,
                    'push_product_by_day' => $request->push_product_by_day,
                ];
                $plan_price = [
                    'Monthly' => $request->plan_monthly_price,
                    'Yearly' => $request->plan_yearly_price,
                ];
                $add = new Plan();
                $add->name = $request->plan_name;
                $add->plan_price = json_encode($plan_price);
                $add->listing_product = $request->no_P_list;
                $add->push_product = json_encode($push_product);
                $add->plateform_sync = $request->plateform_sync;
                $add->product_price = json_encode($product_data);
                $add->shipping_price = json_encode($shipping_price);
                $add->order_cancellation = json_encode($cancel_price);
                $add->currency = $request->currency;
                $add->status = $request->status;
                $add->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Plan added Successful.'
                ]);
                // Session::flash('success', 'Plan added Successful.');
                // return redirect()->route('admin.seller.plan.manage');
            }
        }
    }
    public function manage_plan()
    {
        $datas = Plan::all();
        return view('Admin.plan_management',['datas'=>$datas]);
    }
    public function view_plan($id)
    {
        $datas = Plan::find($id);
        return view('Admin.plan_view',['datas'=>$datas]);
    }
    public function plan_edit($id)
    {
        $data = Plan::find($id);
        $category = Category::all();
        $plan = PlanSubscriber::where('plan_id',$id)->first();
        return view('Admin.update_plane',['data'=>$data,'plan'=>$plan,'category'=>$category]);
    }
    public function plan_update(Request $request)
    {
        if($request->plan_name == "Free")
        {
            $validator = Validator::make($request->all(), [
                'plan_name' => ['required', 'string'],
                'no_P_list' => ['required'],
                'push_product_by_hour' => ['required'],
                'push_product_by_day' => ['required'],
                'plateform_sync' => ['required'],
                'shipping_price_disc' => ['required'],
                'cancel_price_discount' => ['required'],
                // 'status' => ['required'],
                'currency' => ['required'],
                'category' => ['required'],
                'price' => ['required'],
                // 'plan_yearly_price' => ['required'],
                // 'plan_monthly_price' => ['required'],
            ], [], [
                'plan_name' => 'Plan Name',
                'no_P_list' => 'Number of Product in List',
                'push_product_by_hour' => 'Push Product by Hour',
                'push_product_by_day' => 'Push Product by Day',
                'plateform_sync' => 'Platform Sync',
                'shipping_price_disc' => 'Shipping Price Discount',
                'shipping_price_disc_method' => 'Shipping Price Discount Method',
                'cancel_price_discount' => 'Cancel Price Discount',
                'cancel_price_discount_method' => 'Cancel Price Discount Method',
                'status' => 'Status',
                'currency' => 'Currency',
                'category' => 'Category',
                'price' => 'Price',
                'plan_yearly_price' => 'Yearly Price',
                'plan_monthly_price' => 'Monthly Price',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => "fail",
                    'errors' => $validator->errors(),
                ]);
            } else {
                $product_data = array();
                $count = count($request->category);
                for ($i = 0; $i < $count; $i++) {
                    $category = $request->category[$i];
                    $price = $request->price[$i];
                    $method = 'fixed_price';
                    $product_data[] = json_encode([
                        'category' => $category,
                        'price' => $price,
                        'method' => $method,
                    ]);
                }
                $shipping_price = [
                    'discount' => $request->shipping_price_disc,
                    'method' => 'fixed_price'
                ];
                $cancel_price = [
                    'discount' => $request->cancel_price_discount,
                    'method' => 'fixed_price'
                ];
                $push_product = [
                    'push_product_by_hour' => $request->push_product_by_hour,
                    'push_product_by_day' => $request->push_product_by_day,
                ];
                $add = Plan::find($request->id);
                $add->name = $request->plan_name;
                $add->listing_product = $request->no_P_list;
                $add->push_product = json_encode($push_product);
                $add->plateform_sync = $request->plateform_sync;
                $add->product_price = json_encode($product_data);
                $add->shipping_price = json_encode($shipping_price);
                $add->order_cancellation = json_encode($cancel_price);
                $add->currency = $request->currency;
                $request->status ? $add->status = $request->status : null;
                $add->update();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Plan updated Successful.'
                ]);
            }
        }
        else{
            $validator = Validator::make($request->all(), [
                'plan_name' => ['required', 'string'],
                'no_P_list' => ['required'],
                'push_product_by_hour' => ['required'],
                'push_product_by_day' => ['required'],
                'plateform_sync' => ['required'],
                'shipping_price_disc' => ['required'],
                'cancel_price_discount' => ['required'],
                'status' => ['required'],
                'currency' => ['required'],
                'category' => ['required'],
                'price' => ['required'],
                'plan_yearly_price' => ['required'],
                'plan_monthly_price' => ['required'],
            ], [], [
                'plan_name' => 'Plan Name',
                'no_P_list' => 'Number of Product in List',
                'push_product_by_hour' => 'Push Product by Hour',
                'push_product_by_day' => 'Push Product by Day',
                'plateform_sync' => 'Platform Sync',
                'shipping_price_disc' => 'Shipping Price Discount',
                'cancel_price_discount' => 'Cancel Price Discount',
                'status' => 'Status',
                'currency' => 'Currency',
                'category' => 'Category',
                'price' => 'Price',
                'plan_yearly_price' => 'Yearly Price',
                'plan_monthly_price' => 'Monthly Price',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => "fail",
                    'errors' => $validator->errors(),
                ]);
            } else {
                $product_data = array();
                $count = count($request->category);
                for ($i = 0; $i < $count; $i++) {
                    $category = $request->category[$i];
                    $price = $request->price[$i];
                    $method = 'fixed_price';
                    $product_data[] = json_encode([
                        'category' => $category,
                        'price' => $price,
                        'method' => $method,
                    ]);
                }
                $shipping_price = [
                    'discount' => $request->shipping_price_disc,
                    'method' => 'fixed_price'
                ];
                $cancel_price = [
                    'discount' => $request->cancel_price_discount,
                    'method' => 'fixed_price'
                ];
                $push_product = [
                    'push_product_by_hour' => $request->push_product_by_hour,
                    'push_product_by_day' => $request->push_product_by_day,
                ];
                $plan_price = [
                    'Monthly' => $request->plan_monthly_price,
                    'Yearly' => $request->plan_yearly_price,
                ];
                $add = Plan::find($request->id);
                $add->name = $request->plan_name;
                $add->plan_price = json_encode($plan_price);
                $add->listing_product = $request->no_P_list;
                $add->push_product = json_encode($push_product);
                $add->plateform_sync = $request->plateform_sync;
                $add->product_price = json_encode($product_data);
                $add->shipping_price = json_encode($shipping_price);
                $add->order_cancellation = json_encode($cancel_price);
                $add->currency = $request->currency;
                $request->status ? $add->status = $request->status : null;
                $add->update();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Plan updated Successful.'
                ]);
            }
        }
    }
    public function delete($id)
    {
        $delete = Plan::find($id);
        $delete->delete();
        $delete_plans = PlanSubscriber::where('plan_id',$id)->get();
        foreach ($delete_plans as $delete_plan)
        {
            if ($delete_plan)
            {
                $delete_plan->delete();
            }
        }
        Session::flash('success', 'Plan Deleted Successful.');
        return back();
    }

    //Plan price
    public function plan_price_index()
    {
        $plans = PlanPayment::where('user','admin')->orderBy('id', 'DESC')->get();
        return view('Admin.plan_price',['plans'=>$plans]);
    }
}
