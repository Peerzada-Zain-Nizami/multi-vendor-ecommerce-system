<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CronJob;
use App\Models\Drop_shipping;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\PlanSubscriber;
use App\Models\Product;
use App\Models\SellerApi;
use App\Models\ShopifyProduct;
use App\Models\Stock;
use App\Models\Stockins_list;
use App\Models\Tax;
use App\Models\User;
use App\MyClasses\Helpers;
use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\Auth;
use \OAuth2\Client\Provider\Salla;
use Shopify\Clients\Rest;
use Shopify\Rest\Admin2023_01\StorefrontAccessToken;
use Shopify\Utils;
use App\Notifications\MyNotification;
use App\MyClasses\Shopify;
use Shopify\Rest\Admin2023_01\Refund;



/*use Octw\Aramex\Aramex;*/

class SMSAshippingController extends Controller
{
      public function smsa_cities(){
          $specific_product_id = 1; // Replace this with the actual product ID you want to get the stock for
          $order_id = 5408025575746; // Replace this with the actual product ID you want to get the stock for

        //   dd(Helpers::get_product_stock($specific_product_id, $order_id));

          $users = User::where('role',"Seller")->get();
          $product_stock = 0;

          foreach ($users as $user) {
              $shopify_client = Helpers::shopify_client($user->id);
              if ($shopify_client) {
                  // Fetch all open orders for the user
                  $shopify_orders = $shopify_client->get('orders.json?status=open');
                  $shopify_orders = json_decode($shopify_orders)->orders;

                  // Fetch the Shopify product ID associated with the seller's product
                  $shopify_product_id = ShopifyProduct::where('user_id', $user->id)
                      ->where('product_id', $specific_product_id)
                      ->value('shopify_id');

                  if ($shopify_product_id) {
                      // Find the product in the specific seller's orders
                      foreach ($shopify_orders as $shopify_order) {
                          if ($shopify_order && $shopify_order->id == $order_id)
                          {
                              continue;
                          }
                          foreach ($shopify_order->line_items as $lineItem) {
                              if ($lineItem->product_id == $shopify_product_id) {
                                  $product_stock += $lineItem->quantity;
                              }
                          }
                      }
                  }
              }
          }
          dd($product_stock);

      }



//    public function smsa_cities()
//    {
//        $users = User::where('role',"Seller")->get();
//        foreach ($users as $user) {
//
//            $api = SellerApi::where('user_id', $user->id)->first();
//            if ($api)
//            {
//                if (!empty($api->shopify_details))
//                {
//                    $api_details = json_decode($api->shopify_details);
//                    $api = decrypt($api_details->api_key);
//                    $password = decrypt($api_details->access_token);
//                    $hostname = decrypt($api_details->hostname);
//                    /*$access_token = decrypt($api_details->access_token);*/
//                    $client = new \App\MyClasses\Shopify($api, $password, $hostname);
//
//                    /*Start Upload Product on Shopify */
//                    $send = $client->get('products.json');
//                    foreach (json_decode($send) as $data) {
//                        foreach ($data as $values) {
//                            $Shopify_product = Product_shopify::where('shopify_id', $values->id)->first();
//                            if (!empty($Shopify_product)) {
//                                /*Stock Start*/
//                                $product_stocks = Stockins_list::where('product_id', $Shopify_product->product_id)->with('final_stock')->get();
//                                $product_stock = 0;
//                                foreach ($product_stocks as $p_f_stock) {
//                                    $total_stock = $p_f_stock['final_stock'][0]->stock + $p_f_stock['final_stock'][0]->display;
//                                    $product_stock = $product_stock + $total_stock;
//                                }
//                                /*Stock End*/
//                                $product = Drop_shipping::where('user_id', $user->id)
//                                    ->where('product_id', $Shopify_product->product_id)
//                                    ->where('status', "Active")
//                                    ->first();
//                                $shopify_data = [
//                                    'product' => [
//                                        'variants' => [
//                                            [
//                                                'inventory_quantity' => $product_stock,
//                                                'price' => $product->selling_price,
//                                            ]
//                                        ],
//                                    ]
//                                ];
//                                $send = $client->put('products/' . $values->id . '.json', $shopify_data);
//                            }
//                        }
//                    }
//                    /*End Upload Product on Shopify */
//
//                    /*Start Get Orders from Shopify*/
//                    $orders = $client->get('orders.json?status=open');
//                    dd($orders);
//                    foreach (json_decode($orders)->orders as $order)
//                    {
//                        dd($order);
//                        $order_check = Orders::where('user_id',$user->id)
//                            ->where('order_id',$order->id)
//                            ->where('platform',"shopify")->first();
//                        if (empty($order_check))
//                        {
//                            $discounte = array();
//                            $tax_array = array();
//                            $totals = array();
//                            $final_order = array();
//                            foreach ($order->line_items as $product)
//                            {
//                                $shopify_products = Product_shopify::where('user_id',$user->id)->where('shopify_id',$product->product_id)->first();
//                                if (!empty($shopify_products))
//                                {
//                                    $stock_product = Stock::where('product_id',$shopify_products->product_id)->first();
//                                    $my_product = Product::where('id',$stock_product->product_id)->first();
//                                    $category = Category::where('category_name',$my_product->category)->first();
//                                    $plan_subscriber = PlanSubscriber::where('user_id',$user->id)->first();
//                                    $plan = Plan::find($plan_subscriber->plan_id);
//
//                                    foreach (json_decode($plan->product_price) as $plan_product)
//                                    {
//                                        $j_product = json_decode($plan_product);
//                                        if ($j_product->category == $category->id)
//                                        {
//                                            $product_disc = $j_product;
//                                        }
//                                        else{
//                                            continue;
//                                        }
//                                    }
//                                    $json_tax = json_decode($my_product->taxes);
//                                    $tax_rate = null;
//                                    foreach ($json_tax as $taxs)
//                                    {
//                                        $tax_table = Tax::where('name',$taxs)->first();
//                                        $tax_rate = $tax_table->percent;
//                                    }
//                                    $sub_total = $product->quantity*$stock_product->selling_price;
//                                    $dis = $product->quantity*$stock_product->discount;
//                                    $tax = $product->quantity*$tax_rate;
//                                    $tax_price = $tax/100*$stock_product->selling_price;
//                                    $dis_price = $dis/100*$stock_product->selling_price;
//                                    $discounte[] = $dis;
//                                    $tax_array[] = $tax;
//                                    $totals[] = $sub_total;
//
//                                    $total = $stock_product->selling_price + $tax_price - $dis_price;
//
//                                    $single_order = array();
//                                    $single_order['product_id'] = $shopify_products->product_id;
//                                    $single_order['quantity'] = $product->quantity;
//                                    $single_order['rate'] = $stock_product->selling_price;
//                                    $single_order['plan_product_discount'] = $product_disc;
//
//                                    $product_plan_disc = ($product_disc->price/100)*$stock_product->selling_price;
//                                    $discount_plan_pro = $product_disc->method == "percentage" ? $product_plan_disc : $product_disc->price;
//                                    $total1 = $total-$discount_plan_pro;
//
//                                    $single_order['discount'] = $stock_product->discount;
//                                    $single_order['tax'] = $tax_rate;
//                                    $single_order['plan_product_discount_price'] = $discount_plan_pro;
//                                    $single_order['tax_price'] = $tax_price;
//                                    $single_order['dis_price'] = $dis_price;
//                                    $single_order['total'] = $total;
//                                    $single_order['sub_total'] = $total*$product->quantity;
//                                    $final_order[] = $single_order;
//                                }
//                                else{
//                                    continue;
//                                }
//                            }
//
//                            /*Shipping Address*/
//                            $shipping_data = (isset($order->shipping_address))?$order->shipping_address:$order->customer->default_address;
//                            $shipping_address = array();
//                            $shipping_address['first_name'] = $shipping_data->first_name;
//                            $shipping_address['last_name'] = $shipping_data->last_name;
//                            $shipping_address['city'] = $shipping_data->city;
//                            $shipping_address['state'] = $shipping_data->province;
//                            $shipping_address['state_code'] = $shipping_data->province_code;
//                            $shipping_address['country'] = $shipping_data->country;
//                            $shipping_address['country_code'] = $shipping_data->country_code;
//                            $shipping_address['latitude'] = (isset($shipping_data->latitude))?$shipping_data->latitude:null;
//                            $shipping_address['longitude'] = (isset($shipping_data->longitude))?$shipping_data->longitude:null;
//                            $shipping_address['address_1'] = $shipping_data->address1;
//                            $shipping_address['address_2'] = $shipping_data->address2;
//                            $shipping_address['postcode'] = $shipping_data->zip;
//                            $shipping_address['company'] = $shipping_data->company;
//                            $shipping_address['phone'] = $shipping_data->phone;
//
//                            /*Place Order*/
//                            $order_no = '2022'.$user->id.$order->id;
//                            if (!empty($final_order))
//                            {
//                                $add = new Orders();
//                                $add->order_no = $order_no;
//                                $add->order_id = $order->id;
//                                $add->platform = "shopify";
//                                $add->user_id = $user->id;
//                                $add->product = json_encode($final_order);
//                                $add->sub_total = $total*$product->quantity;
//                                $add->shipping_fee = '0';
//                                $add->discount = array_sum($discounte);
//                                $add->tax = array_sum($tax_array);
//                                $add->shipping_address = json_encode($shipping_address);
//                                $add->api_status = "Working on it";
//                                $add->payment = "Unpaid";
//                                $add->paid = 0;
//                                $add->return_payment = 0;
//                                $add->order_status = "New Order";
//                                $add->status = "Draft";
//                                $add->save();
//
//                                $notification = [
//                                    'type'=> 'Shopify New Order',
//                                    'order_id'=> $add->id,
//                                ];
//                                $supplier = User::find($user->id);
//                                $supplier->notify(new MyNotification($notification));
//                            }
//                        }
//                        else{
//                            if ($order->refunds)
//                            {
//                                if ($order_check->api_status != "return-approved")
//                                {
//                                    $add = Orders::find($order_check->id);
//                                    $add->api_status = "return-requested";
//                                    $add->update();
//                                    $destination = User::findOrFail($add->user_id);
//                                    $query =[
//                                        'type' => 'order_status',
//                                        'id' => $add->id,
//                                        'status' => 'return-requested',
//                                    ];
//                                    $previusNotification = $destination->notifications()->whereJsonContains('data->user',$query)->where('notifiable_id', $add->user_id)->count() > 0;
//                                    if ($previusNotification != true)
//                                    {
//                                        $notification = [
//                                            'type'=> 'order_status',
//                                            'id'=> $add->id,
//                                            'status'=> "return-requested",
//                                        ];
//                                        $user = User::where('id',$add->user_id)->first();
//                                        $user->notify(new MyNotification($notification));
//                                    }
//                                }
//                            }
//                        }
//                    }
//                    /*End Get Orders from Shopify*/
//                }
//            }
//            continue;
//        }
//    }
}
