<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CronJob;
use App\Models\Drop_shipping;
use App\Models\Final_Stock;
use App\Models\Group_city;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\PlanSubscriber;
use App\Models\Product;
use App\Models\SellerApi;
use App\Models\SellerCity;
use App\Models\shipping;
use App\Models\ShopifyProduct;
use App\Models\ShopifyProduct as Product_shopify;
use App\Models\SMSAorder;
use App\Models\SMSACredential;
use App\Models\Stock;
use App\Models\StockIn;
use App\Models\Stockins_list;
use App\Models\Tax;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Woo_Tax_Setup;
use App\Models\Wooproduct;
use App\Woo_State;
use Automattic\WooCommerce\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\MyNotification;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;
use Session;
use App\MyClasses\Shopify;
use App\MyClasses\woocommerce;
use App\MyClasses\Helpers;
use App\MyClasses\WarehouseClass;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\DatabaseNotification;
use SmsaSDK\Smsa;


class EcomProductController extends Controller
{
    public function woo_add(Request $request)
    {
        $user = Auth::user()->id;
        $ids = $request->ids;
        $final_ids = array();
        foreach ($ids as $id) {
            $old_woo = Wooproduct::where('user_id', $user)
                ->where('type', "Drop")->where('product_id', $id)->first();
            if (empty($old_woo)) {
                $final_ids[] = $id;
            }
        }
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
        if (!empty($final_ids)) {
            foreach ($final_ids as $final_id) {
                $product_stock = Helpers::get_product_stock($final_id);
                $product_image = Product::where('id', $final_id)->first();
                $product = Drop_shipping::where('user_id', $user)
                    ->where('product_id', $final_id)
                    ->where('status', "Active")
                    ->first();
                /*Images*/
                // env('APP_URL')
                $array = array();
                if (empty($product->featured_image) && empty($product->product_images)) {
                    $images = explode("|", $product_image->product_images);
                    $array[]['src'] = env('APP_URL') . "uploads/featured_images/" . $product_image->featured_image;
                    foreach ($images as $img) {
                        $array[]['src'] = env('APP_URL') . "uploads/product_images/" . $img;
                    }
                } elseif (empty($product->featured_image)) {
                    $array[]['src'] = env('APP_URL') . "uploads/featured_images/" . $product_image->featured_image;
                } elseif (empty($product->product_images)) {
                    $images = explode("|", $product_image->product_images);
                    foreach ($images as $img) {
                        $array[]['src'] = env('APP_URL') . "uploads/product_images/" . $img;
                    }
                } else {
                    $images = explode("|", $product->product_images);
                    $array[]['src'] = env('APP_URL') . "uploads/seller_products/featured_images/" . $product->featured_image;
                    foreach ($images as $img) {
                        $array[]['src'] = env('APP_URL') . "uploads/seller_products/product_images/" . $img;
                    }
                    return response()->json([
                        'images' => $images,
                        'message' => 'this is images'
                    ]);
                }/*categories*/
                $cat_id = null;
                $category = $product->category;
                $woo_categories = $woo_store->get('products/categories');
                foreach ($woo_categories as $woo_category) {
                    if (htmlspecialchars_decode(strtolower($woo_category->name)) == strtolower($category)) {
                        $cat_id = $woo_category->id;
                    }
                }
                if (empty($cat_id)) {
                    $cat_data = [
                        'name' => $category,
                    ];
                    $new_category = $woo_store->post('products/categories', $cat_data);
                    $cat_id = $new_category->id;
                }
                /*Tags*/
                $tag_array = array();
                $tags = json_decode($product->tags);
                foreach ($tags as  $tag) {
                    $woo_tags = $woo_store->get('products/tags', ['search' => $tag]);
                    if (!empty($woo_tags)) {
                        $tag_id = $woo_tags[0]->id;
                    }
                    if (empty($tag_id)) {
                        $tag_data = [
                            'name' => $tag,
                        ];
                        $new_tags = $woo_store->post('products/tags', $tag_data);
                        $tag_id = $new_tags->id;
                    }
                    $tag_array[]['id'] = $tag_id;
                }
                /*Tax Class*/
                $woo_tax_class_name = null;
                $woo_tax_class = $product->get_products[0]->woo_tax_class;
                $wp_woo_tax_classes = $woo_store->get('taxes/classes');
                foreach ($wp_woo_tax_classes as $wp_woo_tax_class) {
                    if (strtolower($wp_woo_tax_class->name) == strtolower($woo_tax_class)) {
                        $woo_tax_class_name = $wp_woo_tax_class->name;
                    }
                }
                if (empty($woo_tax_class_name)) {
                    $tax_lists = Woo_Tax_Setup::where('tax_class_name', $woo_tax_class)->with('tax_name')->get();
                    foreach ($tax_lists as $list) {
                        $class_data = [
                            'name' => $list->tax_class_name
                        ];
                        $tax_class = $woo_store->post('taxes/classes', $class_data);
                        $class_rate = [
                            'rate' => $list->tax_name[0]->percent,
                            'name' => $list->tax_name[0]->name,
                            'class' => $tax_class->slug,
                            'shipping' => false
                        ];
                        $tax_rate = $woo_store->post('taxes', $class_rate);
                        $woo_tax_class_name = $tax_class->name;
                    }
                }

                if (!empty($product)) {
                    $posts = Wooproduct::where('user_id', $user)->where('type', 'Drop')->whereDate('created_at', Carbon::today())->get();
                    if (!$posts->isEmpty()) {
                        $plan_subscriber = PlanSubscriber::where('user_id', $user)->first();
                        $plan = Plan::find($plan_subscriber->plan_id);
                        $push_product = json_decode($plan->push_product);
                        if (count($posts) <= $push_product->push_product_by_day) {
                            $current_date = date('Y-m-d H');
                            $latest_products = Wooproduct::orderby('created_at', 'desc')->get();
                            $p_array = array();
                            foreach ($latest_products as $latest_product) {
                                if ($latest_product->created_at->format('Y-m-d H') == $current_date) {
                                    $p_array[] = $latest_product;
                                }
                            }
                            if (count($p_array) < $push_product->push_product_by_hour) {
                                /*product save in woo*/
                                $woo_data = [
                                    'name' => $product->product_name,
                                    'type' => 'simple',
                                    'regular_price' => $product->selling_price,
                                    'short_description' => $product->short_description,
                                    'description' => $product->brief_description,
                                    'manage_stock' => 1,
                                    'stock_quantity' => $product_stock,
                                    // 'images' => $array,
                                    'tags' => $tag_array,
                                    'tax_status' => "taxable",
                                    //'tax_class' => $woo_tax_class_name,
                                    "shipping_required" => true,
                                    "shipping_taxable" => true,
                                    'shipping_class' => $product_image->woo_shipping_class,
                                    'categories' => [
                                        [
                                            'id' => $cat_id,
                                        ]
                                    ],
                                ];
                                $send = $woo_store->post('products', $woo_data);
                                /*product save in db*/
                                $add = new Wooproduct();
                                $add->user_id = $user;
                                $add->type = "Drop";
                                $add->woo_id = $send->id;
                                $add->product_id = $final_id;
                                $add->save();

                                $list_platform = json_decode($product->platforms);
                                $this_plat = ["WooCommerce"];
                                if (!empty($list_platform)) {
                                    $final = array_merge($list_platform, $this_plat);
                                    $product->platforms = json_encode($final);
                                    $product->save();
                                } else {
                                    $product->platforms = json_encode($this_plat);
                                    $product->save();
                                }
                            } else {
                                return response()->json([
                                    'status' => 401,
                                    'message' => 'Upload hour Limit is full.',
                                ]);
                            }
                        } else {
                            $count = $push_product->push_product_by_day - count($posts);
                            if ($count > 0) {
                                $current_date = date('Y-m-d H');
                                $latest_products = Wooproduct::orderby('created_at', 'desc')->get();
                                $array = array();
                                foreach ($latest_products as $latest_product) {
                                    if ($latest_product->created_at->format('Y-m-d H') == $current_date) {
                                        $array[] = $latest_product;
                                    }
                                }
                                if (count($array) <= $push_product->push_product_by_hour) {
                                    /*product save in woo*/
                                    $woo_data = [
                                        'name' => $product->product_name,
                                        'type' => 'simple',
                                        'regular_price' => $product->selling_price,
                                        'short_description' => $product->short_description,
                                        'description' => $product->brief_description,
                                        'manage_stock' => 1,
                                        'stock_quantity' => $product_stock,
                                        // 'images' => $array,
                                        'tags' => $tag_array,
                                        'tax_status' => "taxable",
                                        //'tax_class' => $woo_tax_class_name,
                                        "shipping_required" => true,
                                        "shipping_taxable" => true,
                                        'shipping_class' => $product_image->woo_shipping_class,
                                        'categories' => [
                                            [
                                                'id' => $cat_id,
                                            ]
                                        ],
                                    ];
                                    $send = $woo_store->post('products', $woo_data);
                                    /*product save in db*/
                                    $add = new Wooproduct();
                                    $add->user_id = $user;
                                    $add->type = "Drop";
                                    $add->woo_id = $send->id;
                                    $add->product_id = $final_id;
                                    $add->save();

                                    $list_platform = json_decode($product->platforms);
                                    $this_plat = ["WooCommerce"];
                                    if (!empty($list_platform)) {
                                        $final = array_merge($list_platform, $this_plat);
                                        $product->platforms = json_encode($final);
                                        $product->save();
                                    } else {
                                        $product->platforms = json_encode($this_plat);
                                        $product->save();
                                    }
                                } else {
                                    return response()->json([
                                        'status' => 401,
                                        'message' => 'Upload hour Limit is full.',
                                    ]);
                                }
                            } else {
                                return response()->json([
                                    'status' => 401,
                                    'message' => 'Upload day Limit is full.',
                                ]);
                            }
                        }
                    } else {
                        $plan_subscriber = PlanSubscriber::where('user_id', $user)->first();
                        $plan = Plan::find($plan_subscriber->plan_id);
                        $push_product = json_decode($plan->push_product);
                        if (count($final_ids) <= $push_product->push_product_by_day) {
                            $current_date = date('Y-m-d H');
                            $latest_products = Wooproduct::orderby('created_at', 'desc')->get();
                            $array = array();
                            foreach ($latest_products as $latest_product) {
                                if ($latest_product->created_at->format('Y-m-d H') == $current_date) {
                                    $array[] = $latest_product;
                                }
                            }
                            if (count($array) <= $push_product->push_product_by_hour) {
                                /*product save in woo*/
                                $woo_data = [
                                    'name' => $product->product_name,
                                    'type' => 'simple',
                                    'regular_price' => $product->selling_price,
                                    'short_description' => $product->short_description,
                                    'description' => $product->brief_description,
                                    'manage_stock' => 1,
                                    'stock_quantity' => $product_stock,
                                    // 'images' => $array,
                                    'tags' => $tag_array,
                                    'tax_status' => "taxable",
                                    //'tax_class' => $woo_tax_class_name,
                                    "shipping_required" => true,
                                    "shipping_taxable" => true,
                                    'shipping_class' => $product_image->woo_shipping_class,
                                    'categories' => [
                                        [
                                            'id' => $cat_id,
                                        ]
                                    ],
                                ];
                                $send = $woo_store->post('products', $woo_data);
                                /*product save in db*/
                                $add = new Wooproduct();
                                $add->user_id = $user;
                                $add->type = "Drop";
                                $add->woo_id = $send->id;
                                $add->product_id = $final_id;
                                $add->save();

                                $list_platform = json_decode($product->platforms);
                                $this_plat = ["WooCommerce"];
                                if (!empty($list_platform)) {
                                    $final = array_merge($list_platform, $this_plat);
                                    $product->platforms = json_encode($final);
                                    $product->save();
                                } else {
                                    $product->platforms = json_encode($this_plat);
                                    $product->save();
                                }
                            } else {
                                $count1 = $push_product->push_product_by_hour - count($array);
                                if ($count1 > 0) {
                                    /*product save in woo*/
                                    $woo_data = [
                                        'name' => $product->product_name,
                                        'type' => 'simple',
                                        'regular_price' => $product->selling_price,
                                        'short_description' => $product->short_description,
                                        'description' => $product->brief_description,
                                        'manage_stock' => 1,
                                        'stock_quantity' => $product_stock,
                                        // 'images' => $array,
                                        'tags' => $tag_array,
                                        'tax_status' => "taxable",
                                        //'tax_class' => $woo_tax_class_name,
                                        "shipping_required" => true,
                                        "shipping_taxable" => true,
                                        'shipping_class' => $product_image->woo_shipping_class,
                                        'categories' => [
                                            [
                                                'id' => $cat_id,
                                            ]
                                        ],
                                    ];
                                    $send = $woo_store->post('products', $woo_data);
                                    /*product save in db*/
                                    $add = new Wooproduct();
                                    $add->user_id = $user;
                                    $add->type = "Drop";
                                    $add->woo_id = $send->id;
                                    $add->product_id = $final_id;
                                    $add->save();

                                    $list_platform = json_decode($product->platforms);
                                    $this_plat = ["WooCommerce"];
                                    if (!empty($list_platform)) {
                                        $final = array_merge($list_platform, $this_plat);
                                        $product->platforms = json_encode($final);
                                        $product->save();
                                    } else {
                                        $product->platforms = json_encode($this_plat);
                                        $product->save();
                                    }
                                } else {
                                    return response()->json([
                                        'status' => 401,
                                        'message' => 'Upload hour Limit is full.',
                                    ]);
                                }
                            }
                        } else {
                            $count = $push_product->push_product_by_day - count($posts);
                            if ($count > 0) {
                                $current_date = date('Y-m-d H');
                                $latest_products = Wooproduct::orderby('created_at', 'desc')->get();
                                $array = array();
                                foreach ($latest_products as $latest_product) {
                                    if ($latest_product->created_at->format('Y-m-d H') == $current_date) {
                                        $array[] = $latest_product;
                                    }
                                }
                                if (count($array) <= $push_product->push_product_by_hour) {
                                    /*product save in woo*/
                                    $woo_data = [
                                        'name' => $product->product_name,
                                        'type' => 'simple',
                                        'regular_price' => $product->selling_price,
                                        'short_description' => $product->short_description,
                                        'description' => $product->brief_description,
                                        'manage_stock' => 1,
                                        'stock_quantity' => $product_stock,
                                        // 'images' => $array,
                                        'tags' => $tag_array,
                                        'tax_status' => "taxable",
                                        //'tax_class' => $woo_tax_class_name,
                                        "shipping_required" => true,
                                        "shipping_taxable" => true,
                                        'shipping_class' => $product_image->woo_shipping_class,
                                        'categories' => [
                                            [
                                                'id' => $cat_id,
                                            ]
                                        ],
                                    ];
                                    $send = $woo_store->post('products', $woo_data);
                                    /*product save in db*/
                                    $add = new Wooproduct();
                                    $add->user_id = $user;
                                    $add->type = "Drop";
                                    $add->woo_id = $send->id;
                                    $add->product_id = $final_id;
                                    $add->save();

                                    $list_platform = json_decode($product->platforms);
                                    $this_plat = ["WooCommerce"];
                                    if (!empty($list_platform)) {
                                        $final = array_merge($list_platform, $this_plat);
                                        $product->platforms = json_encode($final);
                                        $product->save();
                                    } else {
                                        $product->platforms = json_encode($this_plat);
                                        $product->save();
                                    }
                                } else {
                                    $count1 = $push_product->push_product_by_hour - count($array);
                                    if ($count1 > 0) {
                                        /*product save in woo*/
                                        $woo_data = [
                                            'name' => $product->product_name,
                                            'type' => 'simple',
                                            'regular_price' => $product->selling_price,
                                            'short_description' => $product->short_description,
                                            'description' => $product->brief_description,
                                            'manage_stock' => 1,
                                            'stock_quantity' => $product_stock,
                                            // 'images' => $array,
                                            'tags' => $tag_array,
                                            'tax_status' => "taxable",
                                            //'tax_class' => $woo_tax_class_name,
                                            "shipping_required" => true,
                                            "shipping_taxable" => true,
                                            'shipping_class' => $product_image->woo_shipping_class,
                                            'categories' => [
                                                [
                                                    'id' => $cat_id,
                                                ]
                                            ],
                                        ];
                                        $send = $woo_store->post('products', $woo_data);
                                        /*product save in db*/
                                        $add = new Wooproduct();
                                        $add->user_id = $user;
                                        $add->type = "Drop";
                                        $add->woo_id = $send->id;
                                        $add->product_id = $final_id;
                                        $add->save();

                                        $list_platform = json_decode($product->platforms);
                                        $this_plat = ["WooCommerce"];
                                        if (!empty($list_platform)) {
                                            $final = array_merge($list_platform, $this_plat);
                                            $product->platforms = json_encode($final);
                                            $product->save();
                                        } else {
                                            $product->platforms = json_encode($this_plat);
                                            $product->save();
                                        }
                                    } else {
                                        return response()->json([
                                            'status' => 401,
                                            'message' => 'Upload hour Limit is full.',
                                        ]);
                                    }
                                }
                            } else {
                                return response()->json([
                                    'status' => 401,
                                    'message' => 'Upload day Limit is full.',
                                ]);
                            }
                        }
                    }
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'WooCommerce Listing Successful.',
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Data',
            ]);
        }
    }
    public function shopify_add(Request $request)
    {
        $user = Auth::user()->id;
        $ids = $request->ids;
        $final_ids = array();
        foreach ($ids as $id) {
            $old_shopify = ShopifyProduct::where('user_id', $user)
                ->where('type', "Drop")->where('product_id', $id)->first();
            if (empty($old_shopify)) {
                $final_ids[] = $id;
            }
        }
        /*Shopify config*/
        $client = Helpers::shopify_client($user);

        if (!empty($final_ids)) {
            foreach ($final_ids as $final_id) {
                /*Get Product Stock*/
                $product_stock = Helpers::get_product_stock($final_id);

                $product_image = Product::where('id', $final_id)->first();
                $product = Drop_shipping::where('user_id', $user)
                    ->where('product_id', $final_id)
                    ->where('status', "Active")
                    ->first();

                /*Images*/
                $image_array = array();
                if (empty($product->product_images)) {
                    $images = explode("|", $product_image->product_images);
                    foreach ($images as $img) {
                        $image_array[]['src'] = env('APP_URL') . "uploads/product_images/" . $img;
                    }
                } else {
                    $images = explode("|", $product->product_images);
                    foreach ($images as $img) {
                        $image_array[]['src'] = env('APP_URL') . "uploads/seller_products/product_images/" . $img;
                    }
                }

                if (!empty($product)) {

                    $posts = ShopifyProduct::where('user_id', $user)->where('type', 'Drop')->whereDate('created_at', Carbon::today())->get();
                    if (!$posts->isEmpty()) {
                        $plan_subscriber = PlanSubscriber::where('user_id', $user)->first();
                        $plan = Plan::find($plan_subscriber->plan_id);
                        $push_product = json_decode($plan->push_product);
                        if (count($posts) <= $push_product->push_product_by_day) {
                            $current_date = date('Y-m-d H');
                            $latest_products = ShopifyProduct::orderby('created_at', 'desc')->get();
                            $array = array();
                            foreach ($latest_products as $latest_product) {
                                if ($latest_product->created_at->format('Y-m-d H') == $current_date) {
                                    $array[] = $latest_product;
                                }
                            }
                            if (count($array) < $push_product->push_product_by_hour) {
                                /*product save in Shopify*/
                                $shopify_data = [
                                    'product' => [
                                        'title' => $product->product_name,
                                        'status' => "active",
                                        'body_html' => $product->brief_description,
                                        'product_type' => $product->category,
                                        'tags' => json_decode($product->tags),
                                        'variants' => [
                                            [
                                                'inventory_quantity' => $product_stock,
                                                'old_inventory_quantity' => $product_stock,
                                                'price' => $product->selling_price,
                                                'inventory_management' => "shopify",
                                                'taxable' => false,
                                            ]
                                        ],
                                        'images' => $image_array,
                                    ]
                                ];
                                $send = $client->post('products.json', $shopify_data);
                                $dec = json_decode($send);
                                $shopify_id = $dec->product->id;

                                /*product save in db*/
                                $add = new ShopifyProduct();
                                $add->user_id = $user;
                                $add->type = "Drop";
                                $add->shopify_id = strval($shopify_id);
                                $add->product_id = $final_id;
                                $add->save();

                                $list_platform = json_decode($product->platforms);
                                $this_plat = ["Shopify"];
                                if (!empty($list_platform)) {
                                    if (!in_array('Shopify', $list_platform)) {
                                        $final = array_merge($list_platform, $this_plat);
                                        $product->platforms = json_encode($final);
                                        $product->save();
                                    }
                                } else {
                                    $product->platforms = json_encode($this_plat);
                                    $product->save();
                                }
                            } else {
                                return response()->json([
                                    'status' => 401,
                                    'message' => 'Upload hour Limit is full.',
                                ]);
                            }
                        } else {
                            $count = $push_product->push_product_by_day - count($posts);
                            if ($count > 0) {
                                $current_date = date('Y-m-d H');
                                $latest_products = Wooproduct::orderby('created_at', 'desc')->get();
                                $array = array();
                                foreach ($latest_products as $latest_product) {
                                    if ($latest_product->created_at->format('Y-m-d H') == $current_date) {
                                        $array[] = $latest_product;
                                    }
                                }
                                if (count($array) <= $push_product->push_product_by_hour) {
                                    /*product save in Shopify*/
                                    $shopify_data = [
                                        'product' => [
                                            'title' => $product->product_name,
                                            'status' => "active",
                                            'body_html' => $product->brief_description,
                                            'product_type' => $product->category,
                                            'tags' => json_decode($product->tags),
                                            'variants' => [
                                                [
                                                    'inventory_quantity' => $product_stock,
                                                    'old_inventory_quantity' => $product_stock,
                                                    'price' => $product->selling_price,
                                                    'inventory_management' => "shopify",
                                                    'taxable' => false,
                                                ]
                                            ],
                                            'images' => $array,
                                        ]
                                    ];
                                    $send = $client->post('products.json', $shopify_data);
                                    $dec = json_decode($send);
                                    $shopify_id = $dec->product->id;

                                    /*product save in db*/
                                    $add = new ShopifyProduct();
                                    $add->user_id = $user;
                                    $add->type = "Drop";
                                    $add->shopify_id = strval($shopify_id);
                                    $add->product_id = $final_id;
                                    $add->save();

                                    $list_platform = json_decode($product->platforms);
                                    $this_plat = ["Shopify"];
                                    if (!empty($list_platform)) {
                                        if (!in_array('Shopify', $list_platform)) {
                                            $final = array_merge($list_platform, $this_plat);
                                            $product->platforms = json_encode($final);
                                            $product->save();
                                        }
                                    } else {
                                        $product->platforms = json_encode($this_plat);
                                        $product->save();
                                    }
                                } else {
                                    return response()->json([
                                        'status' => 401,
                                        'message' => 'Upload hour Limit is full.',
                                    ]);
                                }
                            } else {
                                return response()->json([
                                    'status' => 401,
                                    'message' => 'Upload day Limit is full.',
                                ]);
                            }
                        }
                    } else {
                        $plan_subscriber = PlanSubscriber::where('user_id', $user)->first();
                        $plan = Plan::find($plan_subscriber->plan_id);
                        $push_product = json_decode($plan->push_product);

                        if (count($final_ids) <= $push_product->push_product_by_day) {
                            $current_date = date('Y-m-d H');
                            $latest_products = ShopifyProduct::orderby('created_at', 'desc')->get();
                            $array = array();
                            foreach ($latest_products as $latest_product) {
                                if ($latest_product->created_at->format('Y-m-d H') == $current_date) {
                                    $array[] = $latest_product;
                                }
                            }
                            if (count($array) <= $push_product->push_product_by_hour) {
                                /*product save in Shopify*/
                                $shopify_data = [
                                    'product' => [
                                        'title' => $product->product_name,
                                        'status' => "active",
                                        'body_html' => $product->brief_description,
                                        'product_type' => $product->category,
                                        'tags' => json_decode($product->tags),
                                        'variants' => [
                                            [
                                                'inventory_quantity' => $product_stock,
                                                'old_inventory_quantity' => $product_stock,
                                                'price' => $product->selling_price,
                                                'inventory_management' => "shopify",
                                                'taxable' => false,
                                            ]
                                        ],
                                        'images' => $image_array,
                                    ]
                                ];
                                $send = $client->post('products.json', $shopify_data);
                                $dec = json_decode($send);
                                $shopify_id = $dec->product->id;

                                /*product save in db*/
                                $add = new ShopifyProduct();
                                $add->user_id = $user;
                                $add->type = "Drop";
                                $add->shopify_id = strval($shopify_id);
                                $add->product_id = $final_id;
                                $add->save();

                                $list_platform = json_decode($product->platforms);
                                $this_plat = ["Shopify"];
                                if (!empty($list_platform)) {
                                    if (!in_array('Shopify', $list_platform)) {
                                        $final = array_merge($list_platform, $this_plat);
                                        $product->platforms = json_encode($final);
                                        $product->save();
                                    }
                                } else {
                                    $product->platforms = json_encode($this_plat);
                                    $product->save();
                                }
                            } else {
                                $count1 = $push_product->push_product_by_hour - count($array);
                                if ($count1 > 0) {
                                    /*product save in Shopify*/
                                    $shopify_data = [
                                        'product' => [
                                            'title' => $product->product_name,
                                            'status' => "active",
                                            'body_html' => $product->brief_description,
                                            'product_type' => $product->category,
                                            'tags' => json_decode($product->tags),
                                            'variants' => [
                                                [
                                                    'inventory_quantity' => $product_stock,
                                                    'old_inventory_quantity' => $product_stock,
                                                    'price' => $product->selling_price,
                                                    'inventory_management' => "shopify",
                                                    'taxable' => false,
                                                ]
                                            ],
                                            'images' => $image_array,
                                        ]
                                    ];
                                    $send = $client->post('products.json', $shopify_data);
                                    $dec = json_decode($send);
                                    $shopify_id = $dec->product->id;

                                    /*product save in db*/
                                    $add = new ShopifyProduct();
                                    $add->user_id = $user;
                                    $add->type = "Drop";
                                    $add->shopify_id = strval($shopify_id);
                                    $add->product_id = $final_id;
                                    $add->save();

                                    $list_platform = json_decode($product->platforms);
                                    $this_plat = ["Shopify"];
                                    if (!empty($list_platform)) {
                                        if (!in_array('Shopify', $list_platform)) {
                                            $final = array_merge($list_platform, $this_plat);
                                            $product->platforms = json_encode($final);
                                            $product->save();
                                        }
                                    } else {
                                        $product->platforms = json_encode($this_plat);
                                        $product->save();
                                    }
                                } else {
                                    return response()->json([
                                        'status' => 401,
                                        'message' => 'Upload hour Limit is full.',
                                    ]);
                                }
                            }
                        } else {
                            $count = $push_product->push_product_by_day - count($posts);
                            if ($count > 0) {
                                $current_date = date('Y-m-d H');
                                $latest_products = ShopifyProduct::orderby('created_at', 'desc')->get();
                                $array = array();
                                foreach ($latest_products as $latest_product) {
                                    if ($latest_product->created_at->format('Y-m-d H') == $current_date) {
                                        $array[] = $latest_product;
                                    }
                                }
                                if (count($array) <= $push_product->push_product_by_hour) {
                                    /*product save in Shopify*/
                                    $shopify_data = [
                                        'product' => [
                                            'title' => $product->product_name,
                                            'status' => "active",
                                            'body_html' => $product->brief_description,
                                            'product_type' => $product->category,
                                            'tags' => json_decode($product->tags),
                                            'variants' => [
                                                [
                                                    'inventory_quantity' => $product_stock,
                                                    'old_inventory_quantity' => $product_stock,
                                                    'price' => $product->selling_price,
                                                    'inventory_management' => "shopify",
                                                    'taxable' => false,
                                                ]
                                            ],
                                            'images' => $image_array,
                                        ]
                                    ];
                                    $send = $client->post('products.json', $shopify_data);
                                    $dec = json_decode($send);
                                    $shopify_id = $dec->product->id;

                                    /*product save in db*/
                                    $add = new ShopifyProduct();
                                    $add->user_id = $user;
                                    $add->type = "Drop";
                                    $add->shopify_id = strval($shopify_id);
                                    $add->product_id = $final_id;
                                    $add->save();

                                    $list_platform = json_decode($product->platforms);
                                    $this_plat = ["Shopify"];
                                    if (!empty($list_platform)) {
                                        if (!in_array('Shopify', $list_platform)) {
                                            $final = array_merge($list_platform, $this_plat);
                                            $product->platforms = json_encode($final);
                                            $product->save();
                                        }
                                    } else {
                                        $product->platforms = json_encode($this_plat);
                                        $product->save();
                                    }
                                } else {
                                    $count1 = $push_product->push_product_by_hour - count($array);
                                    if ($count1 > 0) {
                                        /*product save in Shopify*/
                                        $shopify_data = [
                                            'product' => [
                                                'title' => $product->product_name,
                                                'status' => "active",
                                                'body_html' => $product->brief_description,
                                                'product_type' => $product->category,
                                                'tags' => json_decode($product->tags),
                                                'variants' => [
                                                    [
                                                        'inventory_quantity' => $product_stock,
                                                        'old_inventory_quantity' => $product_stock,
                                                        'price' => $product->selling_price,
                                                        'inventory_management' => "shopify",
                                                        'taxable' => false,
                                                    ]
                                                ],
                                                'images' => $image_array,
                                            ]
                                        ];
                                        $send = $client->post('products.json', $shopify_data);
                                        $dec = json_decode($send);
                                        $shopify_id = $dec->product->id;


                                        /*product save in db*/
                                        $add = new ShopifyProduct();
                                        $add->user_id = $user;
                                        $add->type = "Drop";
                                        $add->shopify_id = strval($shopify_id);
                                        $add->product_id = $final_id;
                                        $add->save();

                                        $list_platform = json_decode($product->platforms);
                                        $this_plat = ["Shopify"];
                                        if (!empty($list_platform)) {
                                            if (!in_array('Shopify', $list_platform)) {
                                                $final = array_merge($list_platform, $this_plat);
                                                $product->platforms = json_encode($final);
                                                $product->save();
                                            }
                                        } else {
                                            $product->platforms = json_encode($this_plat);
                                            $product->save();
                                        }
                                    } else {
                                        return response()->json([
                                            'status' => 401,
                                            'message' => 'Upload hour Limit is full.',
                                        ]);
                                    }
                                }
                            } else {
                                return response()->json([
                                    'status' => 401,
                                    'message' => 'Upload day Limit is full.',
                                ]);
                            }
                        }
                    }
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'Shopify Listing Successful.',
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Data',
            ]);
        }
    }
    public function test(OrderController $orderController) {}
}
