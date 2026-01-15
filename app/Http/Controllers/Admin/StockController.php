<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group_city;
use App\Models\Stockins_list;
use App\Models\Product;
use App\Models\shipping;
use App\Models\Stock;
use App\Models\Tax;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\MyNotification;
use Session;

class StockController extends Controller
{
    public function view()
    {

        $data =  Stock::with('get_products')->orderBy('id', 'desc')->get();
        return view('Admin.stock_catalog',['results' => $data]);
    }
    public function edit(Request $request)
    {
        $data = Stock::find($request->id);
        return response()->json($data);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=> ['required','numeric'],
            'selling_price'=> ['required','numeric'],
            'status'=> ['required','string'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $update = Stock::find($request->id);
            $update->selling_price = $request->selling_price;
            $update->discount = $request->discount;
            $update->discount = $request->discount;
            $update->retail_price = $request->retail_price;
            $update->suggested_price = $request->suggested_price;
            $update->status = $request->status;
            $update->update();
            $notification = [
                'type'=> 'stock',
                'invoice'=> $request->status,
                'id'=> $request->id,
            ];
            $sellers = User::where('role','Seller')->get();
            foreach ($sellers as $seller)
            {
                $seller->notify(new MyNotification($notification));
            }
            return response()->json([
                'status'=>200,
                'message'=>"Product is Updated.",
            ]);
        }
    }

    public function checkAllCitiesSelected(Request $request)
    {
         // Get all unique city_ids from the shippings table
         $shippingCities = Shipping::distinct()->pluck('id')->toArray();

         // Check if all city_ids from shippings exist in group_cities
         $groupCities = Group_city::whereIn('city_id', $shippingCities)->get();

         // If all city_ids from shippings exist in group_cities, return a success response
         if ($groupCities->count() == count($shippingCities)) {
             return response()->json(['status' => 'success']);
         } else {
             return response()->json([
             'status' => 'error',
             'message'=>"Please select all cities before Listed The Product",
             'blade_link' => route('admin.cities.prices')
            ]);
         }
    }
    public function stock_details()
    {
        $results = Warehouse::where('status','Active')->get();
        $warehouses = Warehouse::where('status','Active')->get();
        $suppliers = User::where('role','Supplier')->get();
        $products = Product::all();
        $filter = 0;
        return view('Admin.stock_details',compact('results','warehouses','filter','suppliers','products'));
    }
    public function stock_details_filter(Request $request)
    {
        $where = array();
        $warehouse = $request->warehouse;
        $supplier = $request->supplier;
        $product = $request->product;
        if (!empty($supplier))
        {
            $where[] = ['supplier_id', '=', $supplier];
        }
        if (!empty($warehouse))
        {
            $where[] = ['warehouse_id', '=',$warehouse];
        }
        if (!empty($product))
        {
            $where[] = ['product_id', '=',$product];
        }
        $results = Stockins_list::where($where)->with('get_supplier','get_products')->get()->groupBy(['supplier_id','product_id']);
        $warehouses = Warehouse::where('status','Active')->get();
        $suppliers = User::where('role','Supplier')->get();
        $products = Product::all();
        $filter = 1;
        session()->flashInput($request->input());
        return view('Admin.stock_details',compact('results','warehouses','filter','suppliers','products'));
    }
}
