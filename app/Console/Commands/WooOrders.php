<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\CronJob;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\PlanSubscriber;
use App\Models\Product;
use App\Models\SellerApi;
use App\Models\SMSAorder;
use App\Models\Stock;
use App\Models\Tax;
use App\Models\User;
use App\Models\Wooproduct;
use App\Notifications\MyNotification;
use Automattic\WooCommerce\Client;
use Illuminate\Console\Command;
use Session;

class WooOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woo:orders';

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
    public function handle()
    {
        $users = User::where('role',"Seller")->get();
        foreach ($users as $user) {
            /*woo config*/
            $api = SellerApi::where('user_id', $user->id)->first();
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
                $orders = $woo_store->get('orders');
                foreach ($orders as $order)
                {
                    $order_check = Orders::where('user_id',$user->id)
                        ->where('order_id',$order->id)
                        ->where('platform',"woocommerce")->first();
                    if (!empty($order_check))
                    {
                        $refunds = $woo_store->get('orders/'.$order->id.'/notes');
                        $time = null;
                        foreach ($refunds as $refund) {
                            $pieces = explode(" ", $refund->note);
                            $received = array_pop($pieces);
                            if ($received == "Received.")
                            {
                                $time = $refund->date_created;
                            }
                        }
                        $today = date_create();
                        $date = date_create($time);

                        // Now find the difference in days.
                        $difference = date_diff( $today,$date,true );
                        $days = $difference->days;

                        // If the difference is less than 60, apply "NEW" label to product archive.
                        if ($order_check->order_status == "DELIVERED" && $order->status == "completed") {
                            $add = Orders::find($order_check->id);
                            $add->api_status = $order->status;
                            $add->order_status = $order->status;
                            $add->update();
                        }
                        if ( $days >= 30 && $order_check->order_status == "DELIVERED") {
                            $data = [
                                'status' => "completed",
                            ];
                            $orders = $woo_store->put('orders/'.$order->id,$data);
                        }
                        else{
                            $add = Orders::find($order_check->id);
                            $add->api_status = $order->status;
                            $add->update();
                            $notification = [
                                'type'=> 'order_status',
                                'id'=> $add->id,
                                'status'=> $order->status,
                            ];
                            $user = User::where('id',$add->user_id)->first();
                            $user->notify(new MyNotification($notification));
                        }
                        continue;
                    }
                    else{
                        $discounte = array();
                        $tax_array = array();
                        $totals = array();
                        $final_order = array();
                        foreach ($order->line_items as $product)
                        {
                            $woo_products = Wooproduct::where('user_id',$user->id)->where('woo_id',$product->product_id)->first();
                            if (!empty($woo_products))
                            {
                                $stock_product = Stock::where('product_id',$woo_products->product_id)->first();
                                $my_product = Product::where('id',$stock_product->product_id)->first();
                                $category = Category::where('category_name',$my_product->category)->first();
                                $plan_subscriber = PlanSubscriber::where('user_id',$user->id)->first();
                                $plan = Plan::find($plan_subscriber->plan_id);

                                foreach (json_decode($plan->product_price) as $plan_product)
                                {
                                    $j_product = json_decode($plan_product);
                                    if ($j_product->category == $category->id)
                                    {
                                        $product_disc = $j_product;
                                    }
                                    else{
                                        continue;
                                    }

                                }
                                $json_tax = json_decode($my_product->taxes);
                                $tax_rate = null;
                                foreach ($json_tax as $taxs)
                                {
                                    $tax_table = Tax::where('name',$taxs)->first();
                                    $tax_rate = $tax_table->percent;
                                }
                                $sub_total = $product->quantity*$stock_product->selling_price;
                                $dis = $product->quantity*$stock_product->discount;
                                $tax = $product->quantity*$tax_rate;
                                $tax_price = $tax/100*$stock_product->selling_price;
                                $dis_price = $dis/100*$stock_product->selling_price;
                                $discounte[] = $dis;
                                $tax_array[] = $tax;
                                $totals[] = $sub_total;

                                $total = $stock_product->selling_price + $tax_price - $dis_price;

                                $single_order = array();
                                $single_order['product_id'] = $woo_products->product_id;
                                $single_order['quantity'] = $product->quantity;
                                $single_order['rate'] = $stock_product->selling_price;
                                $single_order['plan_product_discount'] = $product_disc;

                                $product_plan_disc = ($product_disc->price/100)*$stock_product->selling_price;
                                $discount_plan_pro = $product_disc->method == "percentage" ? $product_plan_disc : $product_disc->price;
                                $total1 = $total-$discount_plan_pro;

                                $single_order['discount'] = $stock_product->discount;
                                $single_order['tax'] = $tax_rate;
                                $single_order['plan_product_discount_price'] = $discount_plan_pro;
                                $single_order['tax_price'] = $tax_price;
                                $single_order['dis_price'] = $dis_price;
                                $single_order['total'] = $total;
                                $single_order['sub_total'] = $total*$product->quantity;
                                $final_order[] = $single_order;
                            }
                            else{
                                continue;
                            }
                        }

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

                        /*Place Order*/
                        $order_no = '2022'.$user->id.$order->id;
                        if (!empty($final_order))
                        {
                            $add = new Orders();
                            $add->order_no = $order_no;
                            $add->order_id = $order->id;
                            $add->platform = "woocommerce";
                            $add->user_id = $user->id;
                            $add->product = json_encode($final_order);
                            $add->sub_total = $total*$product->quantity;
                            $add->shipping_fee = '0';
                            $add->discount = array_sum($discounte);
                            $add->tax = array_sum($tax_array);
                            $add->shipping_address = json_encode($shipping_address);
                            $add->api_status = $order->status;
                            $add->payment = "Unpaid";
                            $add->paid = 0;
                            $add->return_payment = 0;
                            $add->order_status = "New Order";
                            $add->status = "Draft";
                            $add->save();

                            $notification = [
                                'type'=> 'Woocommerce New Order',
                                'order_id'=> $add->id,
                            ];
                            $supplier = User::find($user->id);
                            $supplier->notify(new MyNotification($notification));
                        }
                    }
                }
        }
    }
}
