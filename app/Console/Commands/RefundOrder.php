<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orders;
use App\Models\SellerApi;
use App\Models\ShopifyProduct as Product_shopify;
use App\Models\User;
use App\Models\Wooproduct;
use App\MyClasses\Helpers;
class RefundOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:order';

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
        $users = User::where('role', "Seller")->with('seller_wallet')->get();
        foreach ($users as $user) {
            $api = SellerApi::where('user_id', $user->id)->first();
            if ($api) {

                // Check for Shopify Refund orders
                if (!empty($api->shopify_details)) {
                    $client = Helpers::shopify_client($user->id);
                    $system_orders = orders::where('platform','shopify')->get();
                    if ($system_orders) {
                        foreach ($system_orders as $system_order) {
                        $orders = $client->get('orders/' . $system_order->order_id . '/refunds.json');

                    foreach (json_decode($orders) as $order) {


                        $refundData = [];

                        foreach ($order[0]->refund_line_items as $shopify_product) {
                            $product_id = Product_shopify::where('shopify_id',$shopify_product->line_item->product_id)->value('product_id');
                            if ($product_id) {

                                $refundItem = [
                                    'p_id' => $product_id,
                                    'p_qty' => abs($shopify_product->quantity),
                                ];

                                $refundData[] = $refundItem;
                            }
                        }

                        // Update the 'refund' column in the 'orders' table with the $refundData array


                            if ($system_order->payment != 'Paid') {
                                $system_order->refund_items = json_encode($refundData);
                                $system_order->refund_status = "Refunded";
                                $system_order->api_status = "Refunded";
                                $system_order->update();
                            } else {
                                $system_order->refund_items = json_encode($refundData);
                                $system_order->api_status = "Refund Requested";
                                $system_order->update();
                            }

                    }

                    }
                }
                                   }
                // Check for Woocommerce Refund orders
                if (!empty($api->woo_details)) {
                    $woo_store = Helpers::woocommerce_client($user->id);
                    $system_orders = orders::where('platform','woocommerce')->get();

                    if ($system_orders) {
                       foreach ($system_orders as $system_order) {
                    $orders = $woo_store->get('orders/' . $system_order->order_id . '/refunds' );
                    foreach ($orders as $order) {
                        $refundData = [];

                        foreach ($order->line_items as $woo_product) {
                            $product_id = Wooproduct::where('woo_id',$woo_product->product_id)->value('product_id');
                             if ($product_id) {

                                $refundItem = [
                                    'p_id' => $product_id,
                                    'p_qty' => abs($woo_product->quantity),
                                ];

                                $refundData[] = $refundItem;
                            }
                        }

                        // Update the 'refund' column in the 'orders' table with the $refundData array



                            if ($system_order->payment != 'Paid' && $system_order->status != "Refunded") {
                                $system_order->refund_items = json_encode($refundData);
                                $system_order->refund_status = "Refunded";
                                $system_order->api_status = "Refunded";
                                $system_order->update();
                            } else if ($system_order->api_status != "Refunded" && $system_order->payment == 'Paid') {
                                $system_order->refund_items = json_encode($refundData);
                                $system_order->api_status = "Refund Requested";
                                $system_order->update();
                            }


                    }
                }
            }
                }
            }
        }
    }
}
