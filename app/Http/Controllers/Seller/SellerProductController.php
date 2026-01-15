<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\BusinessModel;
use App\Models\Category;
use App\Models\Language_Meta;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\PlanSubscriber;
use App\Models\Product;
use App\Models\Seller_lang_setup;
use App\Models\SellerApi;
use App\Models\SellerCategory;
use App\Models\SellerTag;
use App\Models\ShopifyProduct;
use App\Models\Stock;
use App\Models\SupplierProduct;
use App\Models\User;
use App\Models\Wooproduct;
use App\MyClasses\Helpers;
use App\MyClasses\Shopify;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notifiable;
use App\Notifications\MyNotification;
use App\Models\Drop_shipping;
use App\Models\SellerCity;
use App\Models\shipping;
use Session;

class SellerProductController extends Controller
{
    private $array;
    public function index()
    {
        $data = Stock::where('status', "Listed")->with('get_products')->orderBy('id', 'desc')->paginate(16);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $result = BusinessModel::where('status', "Active")->get();
        $user_id = Auth::user()->id;
        $drop_shipping = Drop_shipping::where('user_id', $user_id)->get();
        $plan_subscriber = PlanSubscriber::where('user_id', $user_id)->first();
        $orders = Orders::all();
        return view('Seller.company_catalog', ['results' => $data, 'categories' => $categories, 'orders' => $orders, 'filter' => 0, 'models' => $result, 'drop_shipping' => $drop_shipping, 'plan_subscriber' => $plan_subscriber]);
    }


    private function chaildren($id)
    {
        $data = Category::where('parent_id', $id)->with('children')->get();
        foreach ($data as $filter) {
            $this->array[] = $filter->category_name;
            if ($filter->children->isNotEmpty()) {
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
        if (!$filters && !$models) {
            Session::flash('danger', 'Invalid Filter.');
            return back();
        }
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $chailds = Category::where('id', $filter)->first();
                $this->array[] = $chailds->category_name;
                self::chaildren($chailds->id);
            }
            $data = Product::whereIn('category', array_unique($this->array))->where('status', "Active")->orderBy('id', 'desc')->get();
            foreach ($data as $single) {
                $categories_id[] = $single->id;
            }
        }
        if (!empty($models)) {
            foreach ($models as $model) {
                $models_data = Product::where('status', "Active")->get();
                foreach ($models_data as $single_data) {
                    $dc = json_decode($single_data->business_model);
                    if (in_array($model, $dc)) {
                        $models_id[] = $single_data->id;
                    }
                }
            }
        }
        $final_ids = array_unique(array_merge($models_id, $categories_id));

        $data2 = Stock::where('status', "Listed")->whereIn('product_id', $final_ids)->with('get_products')->orderBy('id', 'desc')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $result = BusinessModel::where('status', "Active")->get();
        $orders = Orders::all();
        $drop_shipping = Drop_shipping::where('user_id', Auth::user()->id)->get();
        $plan_subscriber = PlanSubscriber::where('user_id', Auth::user()->id)->first();
        session()->flashInput($request->input());
        return view('Seller.company_catalog', ['drop_shipping' => $drop_shipping, 'plan_subscriber' => $plan_subscriber, 'orders' => $orders, 'results' => $data2, 'categories' => $categories, 'filter' => 1, 'models' => $result]);
    }
    public function view($id)
    {
        $data = Product::find($id);
        return view('Seller.company_catalog_view', ['result' => $data]);
    }
    public function add_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $user = Auth::user()->id;
            $where = array(
                'product_id' => $request->id,
                'user_id' => $user
            );
            $check = SupplierProduct::where($where)->first();

            if (!empty($check)) {
                return response()->json([
                    'status' => 400,
                    'errors' => ["This Product is already added in your list"],
                ]);
            } else {
                $p_id = $request->id;
                $product = Product::find($p_id);
                $price = Stock::where('product_id', $p_id)->first();
                $id = Auth::user()->id;
                $data = Seller_lang_setup::where('user_id', $id)->first();
                if (empty($data)) {
                    return response()->json([
                        'status' => 402,
                    ]);
                } else {


                    $shippingCities = Shipping::distinct()->pluck('id')->toArray();

                    // Check if all city_ids from shippings exist in group_cities
                    $groupCities = SellerCity::whereIn('admin_city_id', $shippingCities)->where('seller_id', $user)->get();

                    // If all city_ids from shippings exist in group_cities, proceed with adding to the list
                    if ($groupCities->count() == count($shippingCities)) {

                        $lang = Language_Meta::where('reference_id', $p_id)
                            ->where('reference_type', "product")
                            ->where('language', $data->language)->first();

                        if (empty($lang)) {
                            $add = new Drop_shipping();
                            $add->user_id = $user;
                            $add->product_id = $p_id;
                            $add->product_name = $product->product_name;
                            $add->category = $product->category;
                            $add->short_description = $product->short_description;
                            $add->brief_description = $product->brief_description;
                            $add->selling_price = $price->suggested_price;
                            $add->status = "Deactive";
                            $add->save();

                            $notification = [
                                'type' => 'Added in List',
                                'id' => $add->id,
                            ];
                            $notification_user = User::find($user);
                            $notification_user->notify(new MyNotification($notification));
                            return response()->json([
                                'status' => 200,
                                'message' => 'Product has been successfully added in Your List.',
                            ]);
                        } else {
                            $d_product = json_decode($lang->lang_data);
                            $cat_lang = Helpers::get_lang_cat($product->category, "category", $data->language);
                            $add = new Drop_shipping();
                            $add->user_id = $user;
                            $add->product_id = $p_id;
                            $add->product_name = $d_product->product_name;
                            $add->category = $cat_lang;
                            $add->short_description = $d_product->short_description;
                            $add->brief_description = $d_product->brief_description;
                            $add->selling_price = $price->suggested_price;
                            $add->status = "Deactive";
                            $add->save();

                            $notification = [
                                'type' => 'Added in List',
                                'id' => $add->id,
                            ];
                            $notification_user = User::find($user);
                            $notification_user->notify(new MyNotification($notification));
                            return response()->json([
                                'status' => 200,
                                'message' => 'Product has been successfully added in Your List.',
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'blade_link' => route('seller.city.mapping.view')
                        ]);
                    }
                }
            }
        }
    }
    public function drop_catalog()
    {
        $user = Auth::user()->id;
        $apis = SellerApi::where('user_id', $user)->first();
        $results = Drop_shipping::where('user_id', $user)->with('get_products', 'get_stock')->get();
        return view('Seller.drop_catalog', compact('results', 'apis'));
    }
    public function drop_catalog_edit($id)
    {
        $product = Drop_shipping::where('id', $id)->with('get_products', 'get_stock')->first();
        $user = Auth::user()->id;
        $data = SellerCategory::where('user_id', $user)->orderBy('category_name', 'ASC')->get();
        $tag = SellerTag::where('user_id', $user)->where('status', "Active")->get();
        if ($product->user_id == $user) {
            return view('Seller.my_product', ['results' => $data, 'tags' => $tag, 'product' => $product, 'id' => $id]);
        } else {
            return back();
        }
    }
    public function drop_catalog_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => ['required', 'string', 'min:5', 'max:80'],
            'category' => ['required', 'string'],
            'short_description' => ['required', 'string', 'min:10', 'max:255'],
            'brief_description' => ['required', 'string', 'min:30'],
            'featured_image' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'product_images.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', 'string'],
            'tags' => ['required'],
            'selling_price' => ['required', 'numeric'],
        ], [], [
            'product_name' => 'Product Name',
            'category' => 'Category',
            'short_description' => 'Short Description',
            'brief_description' => 'Brief Description',
            'featured_image' => 'Featured Image,',
            'product_images.*' => 'Product Imae',
            'status' => 'Status',
            'tags' => 'Tags',
            'selling_price' => 'Selling Price',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $user = Auth::user()->id;
            $update = Drop_shipping::find($request->id);
            if ($update->user_id == $user) {
                $images = $request->file('product_images');
                $new_images = array();
                if (!empty($update->product_images)) {
                    $old_images = explode("|", $update->product_images);
                    foreach ($old_images as $value) {
                        $new_images[] = $value;
                    }
                }
                if ($request->hasFile('product_images')) {
                    foreach ($images as $image) {
                        $file_name = date('YmdHis') . rand(1, 10000) . "." . $image->extension();
                        $image->move(public_path('uploads/seller_products/product_images'), $file_name);
                        $new_images[] = $file_name;
                    }
                }

                $new_files = implode("|", $new_images);
                if ($request->hasFile('featured_image')) {
                    if (!empty($update->featured_image)) {
                        $path = public_path('uploads/seller_products/featured_images/' . $update->featured_image);
                        unlink($path);
                    }
                    $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('featured_image')->extension();
                    $update->featured_image = $file_name;
                    $request->file('featured_image')->move(public_path('uploads/seller_products/featured_images'), $file_name);
                }
                $update->product_name = $request->product_name;
                $update->category = $request->category;
                $update->short_description = $request->short_description;
                $update->brief_description = $request->brief_description;
                $update->tags = json_encode($request->tags);
                $update->selling_price = $request->selling_price;
                $update->discount = $request->discount;
                $update->fee = $request->fee;
                $update->product_images = $new_files;
                $update->status = $request->status;
                $update->update();

                // Session::flash('success', 'Product has been updated successful.');
                // return redirect()->route('seller.drop.catalog');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Product has been updated successful.',
                ]);
            } else {
                return back();
            }
        }
    }
    public function drop_catalog_delete($id)
    {
        $delete = Drop_shipping::find($id);
        $user = Auth::user()->id;
        if ($user == $delete->user_id) {
            $platforms = json_decode($delete->platforms);
            if (!empty($platforms)) {
                if (in_array("WooCommerce", $platforms)) {
                    /*woo config*/
                    $api = SellerApi::where('user_id', $user)->first();
                    $api_details = json_decode($api->woo_details);
                    $woo_store = new Client(
                        $api_details->domain_url,
                        decrypt($api_details->consumer_key),
                        decrypt($api_details->consumer_secret),
                        [
                            'wp_api' => true,
                            'version' => 'wc/v3',
                        ]
                    );
                    $woo_del = Wooproduct::where('product_id', $delete->product_id)->first();
                    $woo_store->delete('products/' . $woo_del->woo_id, ['force' => true]);
                    $woo_del->delete();
                }
                if (in_array("Shopify", $platforms)) {
                    /*Shopify config*/
                    $api = SellerApi::where('user_id', $user)->first();
                    $api_details = json_decode($api->shopify_details);
                    $api = decrypt($api_details->api_key);
                    $password = decrypt($api_details->password);
                    $hostname = decrypt($api_details->hostname);
                    /*$access_token = decrypt($api_details->access_token);*/

                    $client = new Shopify($api, $password, $hostname);

                    $shopify_del = ShopifyProduct::where('product_id', $delete->product_id)->first();
                    $client->delete('products/' . $shopify_del->shopify_id . '.json');
                    $shopify_del->delete();
                }
            }
            $delete->delete();
            // Session::flash('success', 'Product has been deleted successful.');
            // return back();
            return response()->json([
                'status' => 'success',
                'message' => 'Product has been deleted successful.',
            ]);
        } else {
            return back();
        }
    }
    public function drop_catalog_delete_img(Request $request)
    {
        $user = Auth::user()->id;
        $product = Drop_shipping::find($request->id);
        if ($product->user_id == $user) {
            $image = $request->image;
            $images = explode("|", $product->product_images);
            if (($key = array_search($image, $images)) !== false) {
                $path = public_path('uploads/seller_products/product_images/' . $image);
                unlink($path);
                unset($images[$key]);
                $new_imgs = implode("|", $images);
                $product->product_images = $new_imgs;
                $product->update();
            }
        }
    }
}
