<?php

namespace App\Console\Commands;

use App\Http\Controllers\Seller\OrderController;
use App\MyClasses\WarehouseClass;
use Illuminate\Console\Command;
use App\Models\Orders;
use App\Models\PlanSubscriber;
use App\Models\SellerApi;
use App\Models\Stock;
use App\Models\Tax;
use App\Models\User;
use App\MyClasses\Helpers;
use App\MyClasses\Shopify;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\MyNotification;
use App\Models\ShopifyProduct as Product_shopify;
use App\Models\Group_city;
use App\Models\SellerCity;
use App\Woo_State;
use App\Models\Wooproduct;
use Session;

class StoreOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(OrderController $orderController)
    {
        $users = User::where('role', "Seller")->with('seller_wallet')->get();
        $admin = User::where('role', 'SuperAdmin')->first();
        foreach ($users as $user) {
            $api = SellerApi::where('user_id', $user->id)->first();
            if ($api) {
                // Check for Shopify store orders
                if (!empty($api->shopify_details)) {

                    $client = Helpers::shopify_client($user->id);

                    /*Start Get Orders from Shopify*/
                    $orders = $client->get('orders.json?status=open');
                    foreach (json_decode($orders)->orders as $order) {
                        $order_check = Orders::where('user_id', $user->id)
                            ->where('order_id', $order->id)
                            ->where('platform', "shopify")->first();
                            if (empty($order_check)) {
                                /*Customer Shipping Data*/
                                $shipping_data = (isset($order->shipping_address)) ? $order->shipping_address : $order->customer->default_address;

                                /*Manage order placement in warehouse*/
                                $productInfo = [];
                                $productdata = array_column($order->line_items, "quantity", "product_id");
                                 $pids =array_column($order->line_items, "product_id");
                            foreach ($pids as $pid) {
                            $existing_products = Product_shopify::where('user_id', $user->id)
                                ->where('shopify_id', strval($pid))
                                ->get();

                            }
                            foreach ($existing_products as $existing_product) {
                                if (isset($productdata[$existing_product->shopify_id])) {
                                    $productInfo[$existing_product->product_id] = $productdata[$existing_product->shopify_id];
                                }
                            }
                            $products_warehouse = WarehouseClass::getWarehouse($productInfo, $shipping_data->city);

                            /*Manage Order Assets*/
                            $sub_total_array = array();
                            $final_order = array();
                            $plan_subscriber = PlanSubscriber::where('user_id', $user->id)->with('plan_get')->first();
                            $flag = false;
                            $stock_count = 0;
                            foreach ($order->line_items as $product) {
                                $shopify_products = Product_shopify::where('user_id', $user->id)->where('shopify_id', strval($product->product_id))->first();

                                if (!empty($shopify_products)) {
                                    $stock_product = Stock::where('product_id', $shopify_products->product_id)->with('product_relation')->first();

                                    /*Manage Product Taxes*/
                                    $json_tax = json_decode($stock_product->product_relation->taxes);

                                    $product_tax = 0;
                                    if ($json_tax != null) {
                                        foreach ($json_tax as $taxs) {
                                            $tax_table = Tax::where('id', $taxs)->first();
                                            $product_tax += $tax_table->percent;
                                        }
                                    }
                                    $pro_tax_price = $product_tax / 100 * $stock_product->selling_price;

                                    /*Manage Product Discount*/
                                    $pro_disc_price = $stock_product->discount / 100 * $stock_product->selling_price;

                                    /*Manage Product plan Discount*/
                                    $plan_disc_info = null;
                                    foreach (json_decode($plan_subscriber->plan_get->product_price) as $plan_product) {
                                        $j_product = json_decode($plan_product);
                                        if ($j_product->category == $stock_product->product_relation->category_relation->id) {
                                            $plan_disc_info = $j_product;
                                        }
                                        continue;
                                    }
                                    $plan_disc_percent_price = ($plan_disc_info != null) ? ($plan_disc_info->price / 100) * $stock_product->selling_price : 0;

                                    $plan_disc_price = ($plan_disc_info != null) ? ($plan_disc_info->method == "percentage" ? $plan_disc_percent_price : $plan_disc_info->price) : 0;

                                    /* Sub Total */
                                    $sub_total = abs($stock_product->selling_price + $pro_tax_price - ($pro_disc_price + $plan_disc_price));

                                    /*Manage Quantity*/
                                    $qty = $product->quantity;
                                    $available_qty = WarehouseClass::getProductStock($shopify_products->product_id, $products_warehouse);

                                    if ($qty > $available_qty) {
                                        $flag = true;
                                    }
                                    $stock_count += $available_qty;

                                    /*Total Tax and Discount Price*/
                                    $total_tax_price = $pro_tax_price * $available_qty;
                                    $total_pro_disc = $pro_disc_price * $available_qty;
                                    $total_plan_disc = $plan_disc_price * $available_qty;
                                    $total_disc_price = $total_pro_disc + $total_plan_disc;

                                    /*Here "p" means Product */
                                    $single_order = array();
                                    $single_order['p_id'] = $shopify_products->product_id;
                                    $single_order['p_price'] = $stock_product->selling_price;
                                    $single_order['p_tax'] = $product_tax;
                                    $single_order['p_disc'] = $stock_product->discount;
                                    $single_order['p_plan_disc'] = ($plan_disc_info != null) ? ($plan_disc_info->price) : 0;
                                    $single_order['p_plan_disc_method'] = ($plan_disc_info != null) ? ($plan_disc_info->method) : null;
                                    $single_order['sub_total'] = $sub_total;
                                    $single_order['order_qty'] = $product->quantity;
                                    $single_order['available_qty'] = $available_qty;
                                    $single_order['packed_qty'] = 0;
                                    $single_order['tax_price'] = $total_tax_price;
                                    $single_order['dis_price'] = $total_disc_price;
                                    $single_order['total'] = $sub_total * $available_qty;
                                    $final_order[] = $single_order;

                                    $sub_total_array[] = $sub_total * $available_qty;
                                }
                            }

                            $order_sub_total = intval(array_sum($sub_total_array));

                            /* Manage Shipping Address*/
                            $shipping_address = array();
                            $shipping_address['first_name'] = $shipping_data->first_name;
                            $shipping_address['last_name'] = $shipping_data->last_name;
                            $shipping_address['city'] = $shipping_data->city;
                            $shipping_address['state'] = $shipping_data->province;
                            $shipping_address['state_code'] = $shipping_data->province_code;
                            $shipping_address['country'] = $shipping_data->country;
                            $shipping_address['country_code'] = $shipping_data->country_code;
                            $shipping_address['latitude'] = (isset($shipping_data->latitude)) ? $shipping_data->latitude : null;
                            $shipping_address['longitude'] = (isset($shipping_data->longitude)) ? $shipping_data->longitude : null;
                            $shipping_address['address_1'] = $shipping_data->address1;
                            $shipping_address['address_2'] = $shipping_data->address2;
                            $shipping_address['postcode'] = $shipping_data->zip;
                            $shipping_address['company'] = $shipping_data->company;
                            $shipping_address['phone'] = $shipping_data->phone;

                            /*Manage Shipping*/
                            $seller_datas = SellerCity::where('seller_id', $user->id)->with('get_city_name')->get();
                            foreach ($seller_datas as $seller_data) {
                                if (strtolower($seller_data->seller_city_name) == strtolower($shipping_address['city'])) {
                                        if ($seller_data->get_city_name->SMSA_cities != null) {
                                            $shipping_id = $seller_data->get_city_name->id;
                                            $shipping_company = "SMSA";

                                            $shipping_city = Group_city::where('city_id', $shipping_id)->with('shipping_price')->first();
                                            if ($shipping_city && $shipping_city->shipping_price != null) {
                                                $j_plan = json_decode($plan_subscriber->plan_get->shipping_price);
                                                if ($j_plan->method == "percentage") {
                                                    $shipping_discount = ($j_plan->discount / 100) * $shipping_city->shipping_price->price;
                                                    $order_total = array_sum($sub_total_array) + ($shipping_city->shipping_price->price - $shipping_discount);
                                                } else {
                                                    $order_total = array_sum($sub_total_array) + ($shipping_city->shipping_price->price - $j_plan->discount);
                                                }
                                                $shipping_fee = $shipping_city->shipping_price->price;
                                                $shipping_group = $shipping_city->shipping_price->group_id;
                                            }
                                        }

                                }
                            }
                            $shipping_price = [
                                'price' => isset($shipping_fee) ? $shipping_fee : 0,
                                'discount' => isset($shipping_fee) ? $j_plan->discount : 0,
                                'discount_method' => isset($shipping_fee) ? ($j_plan->method == "percentage" ? '%' : 'SAR') : 0,
                                'discount_price' => 0
                                // 'discount_price' => isset($shipping_fee) ? $shipping_discount : 0
                            ];

                            /*Place Order*/
                            $order_no = '2022' . $user->id . $order->id;
                            if (!empty($final_order)) {
                                $add = new Orders();
                                $add->order_no = $order_no;
                                $add->user_id = $user->id;
                                $add->order_id = $order->id;
                                $add->shipping_id = isset($shipping_id) ? $shipping_id : null;
                                $add->order_warehouse_id = !empty($products_warehouse) ? array_keys($products_warehouse)[0] : null;
                                $add->admin_id = $admin->id;
                                $add->company_name = isset($shipping_company) ? $shipping_company : null;
                                $add->shipping_address = json_encode($shipping_address);
                                $add->platform = "shopify";
                                $add->shipping_fee = json_encode($shipping_price);
                                $add->shipping_group = isset($shipping_group) ? $shipping_group : null;
                                $add->product = json_encode($final_order);
                                $add->sub_total = $order_sub_total;
                                $add->remaining = isset($order_total) ? ($stock_count > 0 ? $order_total : $order_sub_total) : $order_sub_total;
                                $add->total = isset($order_total) ? ($stock_count > 0 ? $order_total : $order_sub_total) : $order_sub_total;
                                $add->api_status = "Working on it";
                                $add->status = ($flag != true) ? (($admin->order_process_status == 1 && !empty($products_warehouse)) ? "Pending" : "New Order") : (($admin->order_process_status == 1) ? "Pending" : "New Order");
                                $add->is_confirm = ($flag != true) ? 1 : 0;
                                $add->save();

                                /*Manage Order Payment*/
                                if ($flag != true && $user->order_auto_payment == 1) {
                                    $balance = Crypt::decrypt($user->seller_wallet->balance);
                                    if ($balance > $add->remaining && $add->remaining > 0) {
                                        $orderController->auto_invoice_pay($add->id);
                                    } elseif ($add->remaining > 0) {
                                        $notification = [
                                            'type' => 'Insufficient Balance',
                                            'id' => $user->id,
                                        ];
                                        $user->notify(new MyNotification($notification));
                                    }
                                } elseif ($flag != true && $add->remaining > 0) {
                                    $notification = [
                                        'type' => 'Pay the Order',
                                        'id' => $add->id,
                                    ];
                                    $user->notify(new MyNotification($notification));
                                } elseif ($flag == true) {
                                    $notification = [
                                        'type' => 'Verify Order',
                                        'id' => $add->id,
                                    ];
                                    $user->notify(new MyNotification($notification));
                                }

                                /*Manage Notifications*/
                                $notification = [
                                    'type' => 'Shopify New Order',
                                    'order_id' => $add->id,
                                ];
                                $user->notify(new MyNotification($notification));
                                $notification = [
                                    'type' => 'order_send',
                                    'id' => $add->id,
                                ];
                                $admin->notify(new MyNotification($notification));
                            }
                        } else {
                            // if ($order->refunds)
                            // {
                            //     if ($order_check->api_status != "return-approved")
                            //     {
                            //         $add = Orders::find($order_check->id);
                            //         $add->api_status = "return-requested";
                            //         $add->update();
                            //         $destination = User::findOrFail($add->user_id);
                            //         $query =[
                            //             'type' => 'order_status',
                            //             'id' => $add->id,
                            //             'status' => 'return-requested',
                            //         ];
                            //         $previusNotification = $destination->notifications()->whereJsonContains('data->user',$query)->where('notifiable_id', $add->user_id)->count() > 0;
                            //         if ($previusNotification != true)
                            //         {
                            //             $notification = [
                            //                 'type'=> 'order_status',
                            //                 'id'=> $add->id,
                            //                 'status'=> "return-requested",
                            //             ];
                            //             $user = User::where('id',$add->user_id)->first();
                            //             $user->notify(new MyNotification($notification));
                            //         }
                            //     }
                            // }
                        }
                    }
                    /*End Get Orders from Shopify*/
                }

                // Check for WooCommerce store orders
                if (!empty($api->woo_details)) {
                    $woo_store = Helpers::woocommerce_client($user->id);

                    /*Start Get Orders from woocommerce*/
                    $orders = $woo_store->get('orders');

                    foreach ($orders as $order) {
                        $order_check = Orders::where('user_id', $user->id)
                            ->where('order_id', $order->id)
                            ->where('platform', "woocommerce")->first();
                        if (empty($order_check)) {
                            /*Customer Shipping Data*/
                            $shipping_data = $order->shipping;

                            /*Manage order placement in warehouse*/
                            $productInfo = [];
                            $productdata = array_column($order->line_items, "quantity", "product_id");
                            $existing_products = Wooproduct::where('user_id', $user->id)
                                ->whereIn('woo_id', array_keys($productdata))
                                ->get();
                            foreach ($existing_products as $existing_product) {
                                if (isset($productdata[$existing_product->woo_id])) {
                                    $productInfo[$existing_product->product_id] = $productdata[$existing_product->woo_id];
                                }
                            }
                            $products_warehouse = WarehouseClass::getWarehouse($productInfo, $shipping_data->city);

                            /*Manage Order Assets*/
                            $sub_total_array = array();
                            $final_order = array();
                            $plan_subscriber = PlanSubscriber::where('user_id', $user->id)->with('plan_get')->first();
                            $flag = false;
                            $stock_count = 0;

                            foreach ($order->line_items as $product) {
                                $woo_products = Wooproduct::where('user_id', $user->id)->where('woo_id', $product->product_id)->first();
                                if (!empty($woo_products)) {
                                    $stock_product = Stock::where('product_id', $woo_products->product_id)->with('product_relation')->first();

                                    /*Manage Product Taxes*/
                                    $json_tax = json_decode($stock_product->product_relation->taxes);

                                    $product_tax = 0;
                                    if ($json_tax != null) {
                                        foreach ($json_tax as $taxs) {
                                            $tax_table = Tax::where('id', $taxs)->first();
                                            $product_tax += $tax_table->percent;
                                        }
                                    }


                                    $pro_tax_price = $product_tax / 100 * $stock_product->selling_price;

                                    /*Manage Product Discount*/
                                    $pro_disc_price = $stock_product->discount / 100 * $stock_product->selling_price;

                                    /*Manage Product plan Discount*/
                                    $plan_disc_info = null;
                                    foreach (json_decode($plan_subscriber->plan_get->product_price) as $plan_product) {
                                        $j_product = json_decode($plan_product);
                                        if ($j_product->category == $stock_product->product_relation->category_relation->id) {
                                            $plan_disc_info = $j_product;
                                        }
                                        continue;
                                    }

                                    $plan_disc_percent_price = ($j_product != null) ? ($j_product->price / 100) * $stock_product->selling_price : 0;

                                    $plan_disc_price = ($j_product != null) ? ($j_product->method == "percentage" ? $plan_disc_percent_price : $j_product->price) : 0;

                                    /* Sub Total */
                                    $sub_total = $stock_product->selling_price + $pro_tax_price - ($pro_disc_price + $plan_disc_price);


                                    /*Manage Quantity*/
                                    $qty = $product->quantity;
                                    $available_qty = WarehouseClass::getProductStock($woo_products->product_id, $products_warehouse);
                                    if ($qty > $available_qty) {
                                        $flag = true;
                                    }
                                    $stock_count += $available_qty;


                                    /*Total Tax and Discount Price*/
                                    $total_tax_price = $pro_tax_price * $available_qty;
                                    $total_pro_disc = $pro_disc_price * $available_qty;
                                    $total_plan_disc = $plan_disc_price * $available_qty;
                                    $total_disc_price = $total_pro_disc + $total_plan_disc;


                                    $single_order = array();
                                    $single_order['p_id'] = $woo_products->product_id;
                                    $single_order['p_price'] = $stock_product->selling_price;
                                    $single_order['order_qty'] = $product->quantity;
                                    $single_order['rate'] = $stock_product->selling_price;
                                    $single_order['p_plan_disc'] =  ($plan_disc_info != null) ? ($plan_disc_info->price) : 0;
                                    $single_order['p_disc'] = $stock_product->discount;
                                    $single_order['p_tax'] = $product_tax;
                                    $single_order['p_plan_disc_method'] = ($plan_disc_info != null) ? ($plan_disc_info->method) : null;
                                    $single_order['sub_total'] = $sub_total;
                                    $single_order['available_qty'] = $available_qty;
                                    $single_order['packed_qty'] = 0;
                                    $single_order['tax_price'] = $total_tax_price;
                                    $single_order['dis_price'] = $total_disc_price;
                                    $single_order['total'] = $sub_total * $available_qty;
                                    $final_order[] = $single_order;
                                    $sub_total_array[] = $sub_total * $available_qty;
                                } else {
                                    continue;
                                }
                            }

                            $order_sub_total = intval(array_sum($sub_total_array));
                            /*Shipping Address*/
                            $shipping_address = array();
                            $shipping_address['first_name'] = $order->shipping->first_name;
                            $shipping_address['last_name'] = $order->shipping->last_name;
                            $shipping_address['city'] = $order->shipping->city;
                            $shipping_address['state'] = $order->shipping->state;
                            $shipping_address['state_code'] = null;
                            $shipping_address['country'] = $order->shipping->country;
                            $shipping_address['country_code'] = $order->shipping->country;
                            $shipping_address['latitude'] = null;
                            $shipping_address['longitude'] = null;
                            $shipping_address['address_1'] = $order->shipping->address_1;
                            $shipping_address['address_2'] = $order->shipping->address_2;
                            $shipping_address['postcode'] = $order->shipping->postcode;
                            $shipping_address['company'] = $order->shipping->company;
                            $shipping_address['phone'] = $order->shipping->phone;

                            /*Manage Shipping*/
                            $seller_datas = SellerCity::where('seller_id', $user->id)->with('get_city_name')->get();
                            foreach ($seller_datas as $seller_data) {
                                if (strtolower($seller_data->seller_city_name) == strtolower($shipping_address['city'])) {
                                        if ($seller_data->get_city_name->SMSA_cities != null) {
                                            $shipping_id = $seller_data->get_city_name->id;
                                            $shipping_company = "SMSA";

                                            $shipping_city = Group_city::where('city_id', $shipping_id)->with('shipping_price')->first();
                                            if ($shipping_city && $shipping_city->shipping_price != null) {
                                                $j_plan = json_decode($plan_subscriber->plan_get->shipping_price);
                                                if ($j_plan->method == "percentage") {
                                                    $shipping_discount = ($j_plan->discount / 100) * $shipping_city->shipping_price->price;
                                                    $order_total = array_sum($sub_total_array) + ($shipping_city->shipping_price->price - $shipping_discount);
                                                } else {
                                                    $order_total = array_sum($sub_total_array) + ($shipping_city->shipping_price->price - $j_plan->discount);
                                                }
                                                $shipping_fee = $shipping_city->shipping_price->price;
                                                $shipping_group = $shipping_city->shipping_price->group_id;
                                            }
                                        }

                                }
                            }
                            $shipping_price = [
                                'price' => isset($shipping_fee) ? $shipping_fee : 0,
                                'discount' => isset($shipping_fee) ? $j_plan->discount : 0,
                                'discount_method' => isset($shipping_fee) ? ($j_plan->method == "percentage" ? '%' : 'SAR') : 0,
                                'discount_price' => 0
                                // 'discount_price' => isset($shipping_fee) ? $shipping_discount : 0
                            ];

                            /*Place Order*/
                            $order_no = '2022' . $user->id . $order->id;
                            if (!empty($final_order)) {
                                $add = new Orders();
                                $add->order_no = $order_no;
                                $add->user_id = $user->id;
                                $add->order_id = $order->id;
                                $add->shipping_id = isset($shipping_id) ? $shipping_id : null;
                                $add->order_warehouse_id = !empty($products_warehouse) ? array_keys($products_warehouse)[0] : null;
                                $add->admin_id = $admin->id;
                                $add->company_name = isset($shipping_company) ? $shipping_company : null;
                                $add->shipping_address = json_encode($shipping_address);
                                $add->platform = "woocommerce";
                                $add->shipping_fee = json_encode($shipping_price);;
                                $add->shipping_group = isset($shipping_group) ? $shipping_group : null;
                                $add->product = json_encode($final_order);
                                $add->sub_total = $order_sub_total;
                                $add->remaining = isset($order_total) ? ($stock_count > 0 ? $order_total : $order_sub_total) : $order_sub_total;
                                $add->total = isset($order_total) ? ($stock_count > 0 ? $order_total : $order_sub_total) : $order_sub_total;
                                $add->api_status = $order->status;
                                $add->status = ($flag != true) ? (($admin->order_process_status == 1 && !empty($products_warehouse)) ? "Pending" : "New Order") : (($admin->order_process_status == 1) ? "Pending" : "New Order");;
                                $add->is_confirm = ($flag != true) ? 1 : 0;
                                $add->save();

                                /*Manage Order Payment*/
                                if ($flag != true && $user->order_auto_payment == 1) {
                                    $balance = Crypt::decrypt($user->seller_wallet->balance);
                                    if ($balance > $add->remaining && $add->remaining > 0) {
                                        $orderController->auto_invoice_pay($add->id);
                                    } elseif ($add->remaining > 0) {
                                        $notification = [
                                            'type' => 'Insufficient Balance',
                                            'id' => $user->id,
                                        ];
                                        $user->notify(new MyNotification($notification));
                                    }
                                } elseif ($flag != true && $add->remaining > 0) {
                                    $notification = [
                                        'type' => 'Pay the Order',
                                        'id' => $add->id,
                                    ];
                                    $user->notify(new MyNotification($notification));
                                } elseif ($flag == true) {
                                    $notification = [
                                        'type' => 'Verify Order',
                                        'id' => $add->id,
                                    ];
                                    $user->notify(new MyNotification($notification));
                                }

                                /*Manage Notifications*/
                                $notification = [
                                    'type' => 'Woocommerce New Order',
                                    'order_id' => $add->id,
                                ];
                                $user->notify(new MyNotification($notification));

                                $notification = [
                                    'type' => 'order_send',
                                    'id' => $add->id,
                                ];
                                $admin->notify(new MyNotification($notification));
                            } else {
                                // if ($order->refunds) {
                                //     if ($order_check->api_status != "return-approved") {
                                //         $add = Orders::find($order_check->id);
                                //         $add->api_status = "return-requested";
                                //         $add->update();
                                //         $destination = User::findOrFail($add->user_id);
                                //         $query = [
                                //             'type' => 'order_status',
                                //             'id' => $add->id,
                                //             'status' => 'return-requested',
                                //         ];
                                //         $previusNotification = $destination->notifications()->whereains('data->user', $query)->where('notifiable_id', $add->user_id)->count() > 0;
                                //         if ($previusNotification != true) {
                                //             $notification = [
                                //                 'type' => 'order_status',
                                //                 'id' => $add->id,
                                //                 'status' => "return-requested",
                                //             ];
                                //             $user = User::where('id', $add->user_id)->first();
                                //             $user->notify(new MyNotification($notification));
                                //         }
                                //     }
                                // }
                                // continue;
                            }
                        }
                    }
                }
            }
            continue;
        }
    }
}
