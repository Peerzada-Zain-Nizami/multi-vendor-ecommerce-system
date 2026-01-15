<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessModel;
use App\Models\Category;
use App\Models\CompanyOrder;
use App\Models\CompanyReturn;
use App\Models\Language_Meta;
use App\Models\Placement;
use App\Models\Placement_list;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockIn;
use App\Models\SupplierProduct;
use App\Models\Tax;
use App\Models\User;
use App\Models\woo_shipping_setups;
use App\Models\Woo_Tax_Setup;
use App\MyClasses\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class ProdcutController extends Controller
{
    public function index()
    {
        $data = Category::orderBy('category_name','ASC')->get();
        $model = BusinessModel::where('status', "Active")->get();
        $taxes = Tax::where('status', "Active")->get();
        return view('Admin.addproduct',['results'=>$data,'models'=>$model,'taxes'=>$taxes]);
    }
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
           'product_name' => ['required','string','min:5','max:80'],
           'product_sku' => ['nullable','string','unique:products'],
           'category' => ['required','string'],
           'short_description' => ['required','string','min:10','max:255'],
           'brief_description' => ['required','string','min:30'],
           'featured_image' => ['required','image','mimes:jpg,jpeg,png','max:2048'],
           'product_images.*' => ['required','image','mimes:jpg,jpeg,png','max:2048'],
           'status' => ['required','string'],
           'business_model' => ['required'],
           'taxes' => ['nullable'] ,
        ], [], [
            'product_name' => 'Product Name',
            'product_sku' => 'Product SKU',
            'category' => 'Category',
            'short_description' => 'Short Description',
            'brief_description' => 'Brief Description',
            'featured_image' => 'Featured Image',
            'product_images.*' => 'Product Images',
            'status' => 'Status',
            'business_model' => 'Business Model',
            'taxes' => 'Taxes',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
        $images = $request->file('product_images');
        $images_name=array();
        if ($request->hasFile('product_images'))
        {
            foreach ($images as $image)
            {
                $file_name = date('YmdHis').rand(1,10000).".".$image->extension();
                $image->move(public_path('uploads/product_images'),$file_name);
                $images_name[] = $file_name;
            }
        }
        $new_files = implode("|",$images_name);
        $file_name = date('YmdHis').rand(1,10000).".".$request->file('featured_image')->extension();
        $add = new Product();

        $add->product_name = $request->product_name;
        $add->product_sku = $request->product_sku;
        $add->category = $request->category;
        $add->short_description = $request->short_description;
        $add->brief_description = $request->brief_description;
        $add->featured_image = $file_name;
        $add->product_images = $new_files;
        $add->status = $request->status;
        $add->business_model = json_encode($request->business_model);
        $add->taxes = ($request->taxes != null)?json_encode($request->taxes):null;
        $add->save();
        $add_new = new Stock();
        $add_new->product_id = $add->id;
        $add_new->status = "Unlisted";
        $add_new->save();
        $request->file('featured_image')->move(public_path('uploads/featured_images'),$file_name);
            return response()->json([
                'status' => 'success',
                'message' => 'Product added Successful.'
            ]);
    }
    }
    public function show()
    {
        $data = Product::orderBy('id', 'desc')->get();
        return view('Admin.showproduct',['results'=>$data]);
    }
    public function view($id)
    {
        $data = Product::find($id);
        return view('Admin.viewproduct',['result'=>$data]);
    }
    public function edit($id)
    {
        $category = Category::orderBy('category_name','ASC')->get();
        $data = Product::find($id);
        $model = BusinessModel::where('status', "Active")->get();
        $taxes = Tax::where('status', "Active")->get();
        return view('Admin.editproduct',['results'=>$category,'result'=>$data,'models'=>$model,'taxes'=>$taxes]);
    }
    public function image_delete(Request $request)
    {
        $product = Product::find($request->id);
        $image = $request->image;
        $images = explode("|",$product->product_images);
        if (($key = array_search($image, $images)) !== false) {
            $path = public_path('uploads/product_images/'.$image);
            unlink($path);
            unset($images[$key]);
            $new_imgs = implode("|",$images);
            $product->product_images = $new_imgs;
            $product->update();
        }

    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => ['required', 'string', 'min:5', 'max:80'],
            'product_sku' => ['nullable', 'string', 'unique:products'],
            'category' => ['required', 'string'],
            'short_description' => ['required', 'string', 'min:10', 'max:255'],
            'brief_description' => ['required', 'string', 'min:30'],
            'featured_image' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'product_images.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', 'string'],
            'business_model' => ['required'],
            'taxes' => ['nullable'],
        ], [], [
            'product_name' => 'Product Name',
            'product_sku' => 'Product SKU',
            'category' => 'Category',
            'short_description' => 'Short Description',
            'brief_description' => 'Brief Description',
            'featured_image' => 'Featured Image',
            'product_images.*' => 'Product Images',
            'status' => 'Status',
            'business_model' => 'Business Model',
            'taxes' => 'Taxes',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
        $images = $request->file('product_images');
        $update = Product::find($request->id);
        $new_images = array();
        if (!empty($update->product_images))
        {
            $old_images = explode("|",$update->product_images);
            foreach ($old_images as $value)
            {
                $new_images[] = $value;
            }
        }
        if ($request->hasFile('product_images'))
        {
            foreach ($images as $image)
            {
                $file_name = date('YmdHis').rand(1,10000).".".$image->extension();
                $image->move(public_path('uploads/product_images'),$file_name);
                $new_images[] = $file_name;
            }
        }
        $new_files = implode("|",$new_images);
        if ($request->hasFile('featured_image'))
        {
            $path = public_path('uploads/featured_images/'.$update->featured_image);
            unlink($path);
            $file_name = date('YmdHis').rand(1,10000).".".$request->file('featured_image')->extension();
            $update->featured_image = $file_name;
            $request->file('featured_image')->move(public_path('uploads/featured_images'),$file_name);
        }
        $update->product_name = $request->product_name;
        $update->category = $request->category;
        $update->short_description = $request->short_description;
        $update->brief_description = $request->brief_description;
        $update->product_images = $new_files;
        $update->status = $request->status;
        $update->business_model = json_encode($request->business_model);
        $update->taxes = json_encode($request->taxes);
        $update->update();
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated Successful.'
            ]);
    }
}
    public function catalog()
    {
        $data = Product::with('get_suppliers')->orderBy('id', 'desc')->get();
        return view('Admin.catalog',['results' => $data]);
    }
    public function catalog_view($id)
    {
        $product0 = Product::where('id',$id)->first();
        $supplier = SupplierProduct::with('suppliers_name')->where('product_id',$id)->get();
        $bar_codes = StockIn::with('suppliers_name')->where('product_id',$id)->get();
        $stock_ins_id = array();
        if (!empty($bar_codes))
        {
            foreach ($bar_codes as $bar_code)
            {
                $stock_ins_id[] = $bar_code->id;
            }
        }
        $placements = Placement::with('shelf_get','stock_in_get')->whereIn('stock_in_id',$stock_ins_id)->get();
        $placement_history = Placement_list::with('shelf_get','stock_in_get')->where('stock_in_id',$stock_ins_id)->get();
        $company_orders = CompanyOrder::all();
        $array = array();
        foreach ($company_orders as $company_order)
        {
            $products = json_decode($company_order->original_order);
            foreach ($products as $product)
            {
                if ($product->product_id == $id)
                {
                    $array[] =  $company_order->id;
                }
            }
        }
        $company_orders = CompanyOrder::whereIn('id',$array)->with('suppliers_name')->get();
        $company_returns = CompanyReturn::all();
        $array1 = array();
        foreach ($company_returns as $company_return)
        {
            $products = json_decode($company_return->products);
            foreach ($products as $product)
            {
                if ($product->product_id == $id)
                {
                    $array1[] =  $company_return->id;
                }
            }
        }
        $company_returns = CompanyReturn::whereIn('id',$array1)->with('suppliers_name')->get();
        return view('Admin.catalog_view',['product' => $product0,'suppliers'=>$supplier,'barcodes'=>$bar_codes,'placements'=>$placements,'placement_histories'=>$placement_history,'company_orders'=>$company_orders,'company_returns'=>$company_returns]);
    }

    public function product_lang_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => ['required','string','min:5','max:80'],
            'short_description' => ['required','string','min:10','max:255'],
            'brief_description' => ['required','string','min:30'],
            'lang_type'=>['required'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $data = [
                'product_name'=>$request->product_name,
                'short_description'=>$request->short_description,
                'brief_description'=>$request->brief_description,
            ];
            $add = new Language_Meta();
            $add->reference_id = $request->ref_id;
            $add->reference_type = "product";
            $add->language = $request->lang_type;
            $add->lang_data = json_encode($data);
            $add->save();
            return response()->json([
                'status'=>"pass",
                'message'=>"Language Added Successful.",
            ]);
        }
    }
    public function product_lang_edit($id)
    {
        $data = Language_Meta::find($id);
        $decode_data = json_decode($data->lang_data);
        return view('Admin.edit_product_lang',['data'=>$data,'decode_data'=>$decode_data]);
    }
    public function product_lang_update(Request $request,$id)
    {
        $request->validate([
            'product_name' => ['required','string','min:5','max:80'],
            'short_description' => ['required','string','min:10','max:255'],
            'brief_description' => ['required','string','min:30'],
        ]);
            $data = [
                'product_name'=>$request->product_name,
                'short_description'=>$request->short_description,
                'brief_description'=>$request->brief_description,
            ];
            $update = Language_Meta::find($id);
            $update->lang_data = json_encode($data);
            $update->update();
            Session::flash('success', 'Product updated Successful.');
            return redirect()->route('admin.product.manage');
    }
    public function product_lang_del($id)
    {
        $del = Language_Meta::find($id);
        $del->delete();
        return back();
    }
    public function category_lang_list(Request $request)
    {
        $langs = Helpers::act_lang($request->ref_id,$request->ref_type);
        $view = view('Admin.temp.langs',compact('langs'))->render();
        return response()->json($view);
    }
}
