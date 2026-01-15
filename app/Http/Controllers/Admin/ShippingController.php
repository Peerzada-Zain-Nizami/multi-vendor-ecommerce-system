<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\shipping;
use App\Models\ShippingCompany;
use App\Models\Tax;
use App\Models\Woo_Continent;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;
use function PHPUnit\Framework\isEmpty;
use Session;

class ShippingController extends Controller
{
    public function get_city_data(Request $request)
    {
        if ($request->shipping_company == "SMSA")
        {
            $cities_datas = shipping::where('SMSA_status','Deactive')->get();
            $data = array();
            foreach ($cities_datas as $cities_data)
            {
                $data[] = [
                    'id'=>$cities_data->id,
                    'city'=>$cities_data->SMSA_city
                ];
            }
            return response()->json([
                'data'=>$data,
            ]);
        }
    }
    public function add_shipping(Request $request)
    {
        $request->validate([
            'shipping_company' => ['required'],
            'shipping_city_id' => ['required'],
            'shipping_price' => ['required'],
            'status' => ['required'],
        ]);
        if ($request->shipping_company == "SMSA")
        {
            $add = shipping::find($request->shipping_city_id);
            $add->SMSA_city_shipping_price = $request->shipping_price;
            $add->SMSA_status = $request->status;
            $add->update();
            Session::flash('success', 'Shipping Price added Successful.');
            return back();
        }
    }
    public function edit($id)
    {
        $data = shipping::find($id);
        $woo_contients = Woo_Continent::with('get_country')->get();
        return view('Admin.shipping_edit',['data'=>$data,'woo_contients'=>$woo_contients]);
    }
    public function update(Request $request,$id)
    {
        $request->validate([
            'company_name' => ['required','string'],
            'locations' => ['required'],
            'price' => ['required'],
            'status' => ['required'],
        ]);
        $add = shipping::find($id);
        $add->company_name = $request->company_name;
        $add->locations = json_encode($request->locations);
        $add->price = $request->price;
        $add->status = $request->status;
        $add->update();
        Session::flash('success', 'Shipping updated Successful.');
        return redirect()->route('admin.shipping.index');
    }
    public function delete($id)
    {
        $find = shipping::find($id);
        $find->delete();
        Session::flash('success', 'Shipping Deleted Successful.');
        return back();
    }

    public function cancel_index()
    {
        $shipping_companies = ShippingCompany::where('cancellation_price',Null)->get();
        $shipping_companies_show = ShippingCompany::all();
        return view('Admin.cancellation_price',['shipping_companies'=>$shipping_companies,'shipping_companies_show'=>$shipping_companies_show]);
    }
    public function add_cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_company'=>['required'],
            'cancellation_price'=>['required'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $add = ShippingCompany::find($request->shipping_company);
            $add->cancellation_price = $request->cancellation_price;
            $add->update();
            return response()->json([
                'status'=>"pass",
                'message'=>'Cancellation Price successfully Added.',
            ]);
        }
    }
    public function edit_cancel($id)
    {
        $data = shipping::find($id);
        $woo_contients = Woo_Continent::with('get_country')->get();
        return view('Admin.shipping_edit',['data'=>$data,'woo_contients'=>$woo_contients]);
    }
    public function update_cancel(Request $request,$id)
    {
        $request->validate([
            'company_name' => ['required','string'],
            'locations' => ['required'],
            'price' => ['required'],
            'status' => ['required'],
        ]);
        $add = shipping::find($id);
        $add->company_name = $request->company_name;
        $add->locations = json_encode($request->locations);
        $add->price = $request->price;
        $add->status = $request->status;
        $add->update();
        Session::flash('success', 'Shipping updated Successful.');
        return redirect()->route('admin.shipping.index');
    }
    public function delete_cancel($id)
    {
        $find = ShippingCompany::find($id);
        $find->cancellation_price = Null;
        $find->update();
        Session::flash('Success', 'Shipping Cancellation Price Deleted Successful.');
        return back();
    }

}

