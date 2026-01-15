<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\BusinessModel;
use App\Models\Category;
use App\Models\Product;
use App\Models\SupplierProduct;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class SupplierProductController extends Controller
{
    private $array;
    public function index()
    {
            $data = Product::where('status',"Active")->orderBy('id','desc')->paginate(16);
            $categories = Category::whereNull('parent_id')->with('children')->get();
            $result = BusinessModel::where('status',"Active")->get();
            return view('Supplier.company_catalog',['results'=>$data,'categories'=>$categories,'filter'=>0,'models'=>$result]);
    }
    private function chaildren($id)
    {
        $data = Category::where('parent_id',$id)->with('children')->get();
        foreach ($data as $filter)
        {
            $this->array[] = $filter->category_name;
            if ($filter->children->isNotEmpty())
            {
                self::chaildren($filter->id);
            }

        }
    }
    public function index_filter(Request $request)
    {
        $filters = $request->category;
        $models = $request->business_model;
        $models_id = array();
        $categories_id = array();
        if (!$filters && !$models)
        {
            Session::flash('danger', 'Invalid Filter.');
            return back();
        }
        if (!empty($filters))
        {
            foreach ($filters as $filter)
            {
                $chailds = Category::where('id',$filter)->first();
                $this->array[] = $chailds->category_name;
                self::chaildren($chailds->id);

            }
            $data = Product::whereIn('category',array_unique($this->array))->where('status',"Active")->orderBy('id','desc')->get();
            foreach ($data as $single)
            {
                $categories_id[] = $single->id;
            }
        }
        if (!empty($models))
        {
            foreach ($models as $model)
            {
                $models_data = Product::where('status',"Active")->get();
                foreach ($models_data as $single_data)
                {
                    $dc = json_decode($single_data->business_model);
                    if (in_array($model,$dc))
                    {
                        $models_id[] = $single_data->id;
                    }
                }

            }
        }
        $final_ids = array_unique(array_merge($models_id,$categories_id));
        $final_data = Product::whereIn('id',$final_ids)->where('status',"Active")->orderBy('id','desc')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $result = BusinessModel::where('status',"Active")->get();
        session()->flashInput($request->input());
        return view('Supplier.company_catalog',['results'=>$final_data,'categories'=>$categories,'filter'=>1,'models'=>$result]);
    }
    public function view($id)
    {
        $data = Product::find($id);
        return view('Supplier.viewproduct',['result'=>$data]);
    }
    public function add_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'=> ['required','numeric'],
            'selling_price'=> ['required','numeric'],
            'stock'=> ['required','numeric'],
            'shipping_charges'=> ['required','numeric']
        ],[],[
            'product_id' => 'Product',
            'selling_price' => 'Selling Price',
            'stock' => 'Stock',
            'shipping_charges' => 'Shipping Charges'
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $user = Auth::user()->id;
            $where = array(
                'product_id'=> $request->product_id,
                'user_id'=>$user
            );
            $check = SupplierProduct::where($where)->first();
            if (!empty($check))
            {
                return response()->json([
                    'status'=>400,
                    'errors'=>["This Product is already added in your list"],
                ]);
            }
            else{
                $user = Auth::user()->id;
                $add = new SupplierProduct();
                $add->user_id = $user;
                $add->product_id = $request->product_id;
                $add->selling_price = $request->selling_price;
                $add->stock = $request->stock;
                $add->shipping_charges = $request->shipping_charges;
                $add->status = "Available";
                $add->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Product has been successfully added in Your List.',
                ]);
            }
        }
    }
    public function view_catalog()
    {
        $user = Auth::user()->id;
        $data = SupplierProduct::where('user_id',$user)->orderBy('id','desc')->with('products')->get();
        return view('Supplier.mycatalog',['results'=>$data]);
    }
    public function show($id)
    {
        $user = Auth::user()->id;
        $data = SupplierProduct::find($id);
        if ($data->user_id == $user)
        {
            return response()->json($data);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=> ['required','numeric'],
            'selling_price'=> ['required','numeric'],
            'status'=> ['required','string'],
            'shipping_charges'=> ['required','numeric']
        ], [], [
            'id' => 'Id',
            'selling_price' => 'Selling Price,',
            'status' => 'Status',
            'shipping_charges' => 'Shipping Charges'
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $update = SupplierProduct::find($request->id);
            $update->selling_price = $request->selling_price;
            $update->status = $request->status;
            $update->shipping_charges = $request->shipping_charges;
            $update->update();
            return response()->json([
                'status'=>200,
                'message'=>'Product has been successfully Updated.',
            ]);
        }
    }
    public function delete($id)
    {
        $delete = SupplierProduct::find($id);
        if (Auth::user()->id == $delete->user_id)
        {
            $delete->delete();
            Session::flash('success', 'Product has been deleted successful.');
            return back();
        }
        else{
            return back();
        }
    }
}
