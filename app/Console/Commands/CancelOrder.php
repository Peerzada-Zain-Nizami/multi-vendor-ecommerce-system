<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orders;
use App\Models\SellerApi;
use App\Models\User;
use App\MyClasses\Helpers;
use Illuminate\Notifications\Notification;
use App\Notifications\MyNotification;




class CancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:order';

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
        $admin = User::where('role', 'SuperAdmin')->first();
        foreach ($users as $user) {
            $api = SellerApi::where('user_id', $user->id)->first();
            if ($api) {

                // Check for Shopify Cancel orders
                if (!empty($api->shopify_details)) {
                    $client = Helpers::shopify_client($user->id);
                    $orders = $client->get('orders.json?status=cancelled');
                    foreach (json_decode($orders)->orders as $order) {
                        $order_check = Orders::where('user_id', $user->id)
                            ->where('order_id', $order->id)
                            ->where('platform', "shopify")->first();
                        if ($order_check && $order_check->status) {
                            if ($order_check->payment != 'Paid') {
                                $order_check->status = "Cancelled";
                                $order_check->update();
                            } else {
                                $order_check->api_status = "Cancelled";
                                $order_check->update();
                                $check_notification = Helpers::check_notification($order_check->id);
                                if ($check_notification  != true) {
                                    $notification = [
                                        'type' => 'Cancelled',
                                        'id' => $order_check->id,
                                    ];
                                    $admin->notify(new MyNotification($notification));

                                    $notification1 = [
                                        'type' => 'Pay the Order',
                                        'id' => $order_check->id,
                                    ];
                                    $admin->notify(new MyNotification($notification1));
                                }
                            }
                        }
                    }
                }

                // Check for Woocommerce Cancel orders
                if (!empty($api->woo_details)) {
                    $woo_store = Helpers::woocommerce_client($user->id);
                    $orders = $woo_store->get('orders',  [
                        'status' => 'cancelled',
                    ]);
                    foreach ($orders as $order) {
                        $order_check = Orders::where('user_id', $user->id)
                            ->where('order_id', $order->id)
                            ->where('platform', "woocommerce")->first();

                        if ($order_check && $order_check->status) {
                            if ($order_check->payment != 'Paid') {
                                $order_check->status = "Cancelled";
                                $order_check->update();
                            }
                            else {
                                $order_check->api_status = "Cancelled";
                                $order_check->update();
                                $check_notification = Helpers::check_notification($order_check->id);
                                if ($check_notification  != true) {
                                    $notification = [
                                        'type' => 'Cancelled',
                                        'id' => $order_check->id,
                                    ];
                                    $admin->notify(new MyNotification($notification));

                                    $notification1 = [
                                        'type' => 'Pay the Order',
                                        'id' => $order_check->id,
                                    ];
                                    $admin->notify(new MyNotification($notification1));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
