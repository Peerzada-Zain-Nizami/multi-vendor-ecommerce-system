<?php

namespace App\MyClasses;

use App\Http\Controllers\Seller\OrderController;
use App\Models\BusinessModel;
use App\Models\Category;
use App\Models\Drop_shipping;
use App\Models\Group_city;
use App\Models\Language_Meta;
use App\Models\Orders;
use App\Models\PlanSubscriber;
use App\Models\SellerApi;
use App\Models\SellerCategory;
use App\Models\SellerCity;
use App\Models\shipping;
use App\Models\SMSACredential;
use App\Models\ShopifyProduct;
use App\Models\Stockins_list;
use App\Models\User;
use App\Models\Woo_Continent;
use App\Models\Woo_Country;
use App\Models\Woo_State;
use App\Models\Wooproduct;
use App\Notifications\MyNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use function PHPUnit\Framework\isEmpty;
use Automattic\WooCommerce\Client;
use SmsaSDK\Smsa;
use App\Models\SMSAorder;
use Session;
class Helpers
{

    public static function packed_order($order_id)
    {
        $order = Orders::find($order_id);
        $allProductsPacked = true;
        foreach (json_decode($order->product) as $json_product) {
            if ($json_product->available_qty != $json_product->packed_qty) {
                $allProductsPacked = false; // If any product is not packed, set the flag to false
                break;
            }
        }
        if ($allProductsPacked) {
            $order->status = "Packed";
            $order->update();
            Helpers::generate_shipment_waybill($order_id,$order->user_id);
        }
    }

    public static function generate_shipment_waybill($id,$user_id)
    {
        $user = User::find($user_id);
        if ($user->shipping_from_us == 1) {
            $smsa =SMSACredential::where('user_id',1)->first();
        }
        else {
            $smsa =SMSACredential::where('user_id',4)->first();
        }
        $order = Orders::find($id);
        $admin = User::where('role','SuperAdmin')->first();
        $consignee_data = json_decode($order->shipping_address);
        $ref_no = time();
        $shipper = [
            'sName' => $user->company_name,
            'sContact' => $user->name,
            'sAddr1' => $user->address,
            'sCity' => $user->city,
            'sPhone' => $user->mobile_no,
            'sCntry' => $user->country,
        ];
        $consignee = [
            'cName' => $consignee_data->first_name . ' ' . $consignee_data->first_name,
            'cntry' => $consignee_data->country,
            'cCity' => $consignee_data->city,
            'cMobile' => ($consignee_data->phone) ? $consignee_data->phone : $user->mobile_no,
            'cAddr1' => $consignee_data->address_1,
        ];
        $data = [
            'shipType' => 'DLV',
            'codAmt' => 0,
            'custVal' => 0,
            'custCurr' => "SAR",
            'itemDesc' => "Supplies and catalogs",
            'refNo' => $ref_no,
            'PCs' => 1,
            'weight' => 0.5,
        ];

        $shipmentData = [
            'passKey' => $smsa->passkey,
            'refNo' => $ref_no,
            'sentDate' => time(),
            'idNo' => '',
            'cName' => $consignee_data->first_name.' '.$consignee_data->last_name,
            'cntry' => $consignee_data->country,
            'cCity' => $consignee_data->city,
            'cZip' => '',
            'cPOBox' => '',
            'cMobile' => ($consignee_data->phone != null) ? $consignee_data->phone : $user->mobile_no,
            'cTel1' => '',
            'cTel2' => '',
            'cAddr1' => $consignee_data->address_1,
            'cAddr2' => '',
            'shipType' => 'DLV',
            'PCs' => "1",
            'cEmail' => '',
            'carrValue' => '',
            'carrCurr' => '',
            'codAmt' => "0",
            'weight' => "0.5",
            'custVal' => '',
            'custCurr' => "SAR",
            'insrAmt' => '',
            'insrCurr' => '',
            'itemDesc' => "Supplies and catalogs",
            'sName' => $user->company_name,
            'sContact' => $user->name,
            'sAddr1' => $user->address,
            'sAddr2' => '',
            'sCity' => $user->city,
            'sPhone' => $user->mobile_no,
            'sCntry' => $user->country,
            'prefDelvDate' => '',
            'gpsPoints' => '',
        ];
        $status = Smsa::addShip($shipmentData);
        if (!$status)
         {
            $notification = [
                'type' => "api_error",
                'id' => $order->id,
            ];
            $admin->notify(new MyNotification($notification));
        }
        $awbNumber = $status->getAddShipResult();

        if (is_numeric($awbNumber))
        {
            $SMSA_order = SMSAorder::where('order_id', $id)->first();
            if (empty($SMSA_order)) {
                $SMSAadd = new SMSAorder();
                $SMSAadd->order_id = $id;
                $SMSAadd->AWB_no = $awbNumber;
                $SMSAadd->save();

                $status = Smsa::getStatus($SMSAadd->AWB_no, $smsa->passkey);
                $getstatus = $status->getGetStatusResult();

                $user = Auth::user()->id;
                $order->receiver_wadmin = $user;
                $order->delivery_status = $getstatus;
                $order->update();

            }
        }
    }

    public static function shopify_client($id)
    {
        $api = SellerApi::where('user_id', $id)->first();
        if (!$api || empty($api->shopify_details)) {
            return null; // Return null or handle the case when the Shopify API details are not found
        }
        $api_details = json_decode($api->shopify_details);
        $api = decrypt($api_details->api_key);
        $password = decrypt($api_details->access_token);
        $hostname = decrypt($api_details->hostname);
        return new Shopify($api, $password, $hostname);
    }
    public static function woocommerce_client($user)
    {
        $api = SellerApi::where('user_id', $user)->first();
        if (!$api || empty($api->woo_details)) {
            return null;
        }
        $api_details = json_decode($api->woo_details);
        $client = new Client(
            $api_details->domain_url,
            decrypt($api_details->consumer_key),
            decrypt($api_details->consumer_secret),
            [
                'wp_api' => true,
                'version' => 'wc/v3',
            ]
        );
        return $client;
    }
    public static function woo_shopify_status($id)
    {
        $order = Orders::where('id', $id)->first();
        // if ($order->platform == 'shopify') {
        //     $client = Helpers::shopify_client($order->user_id);
        //     if ($client !== null) {
        //         if ($order->refund_status == 'Refund Approved') {
        //             $data = [
        //                 'payment_status' => 'return-approved',
        //             ];
        //         }
        //         elseif ($order->refund_status == 'Refunded') {
        //             $data = [
        //                 'payment_status' => 'refunded',
        //             ];
        //         }
        //         $orders = $client->put('orders/' . $order->order_id , $data);
        //     }

        // }
        if($order->platform == 'woocommerce') {
            $woo_store = Helpers::woocommerce_client($order->user_id);
            if ($woo_store != null) {

                if ($order->refund_status == 'Refund Approved') {
                    $data = [
                        'status' => 'return-approved',
                    ];
                }
                elseif ($order->refund_status == 'Refunded') {
                    $data = [
                        'status' => 'refunded',
                    ];
                }
                $orders = $woo_store->put('orders/' . $order->order_id, $data);

            }


        }
    }
    public static function check_notification($order_id)
    {
        $notifications  = DB::table('notifications')->where('notifiable_id', 1)->get();
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data);
            if ($data->user && $data->user->type == 'Cancelled' && isset($data->user->id) && $data->user->id == $order_id) {
                return true;
            }
            continue;
        }
        return false;
    }

    public static function check_refund_notification($order_id)
    {
        $notifications  = DB::table('notifications')->where('notifiable_id', 1)->get();
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data);
            if ($data->user && $data->user->type == 'Refund Order' && isset($data->user->id) && $data->user->id == $order_id) {
                return true;
            }
            continue;
        }
        return false;
    }
    public static function get_lang($text, $ref_type, $lang)
    {
        $data = Language_Meta::where('reference_type', $ref_type)
            ->where('language', $lang)->first();
        if (!empty($data)) {
            return $data->lang_data;
        } else {
            return $text;
        }
    }
    public static function get_lang_cat($text, $ref_type, $lang)
    {
        $ref_id = Category::where('category_name', $text)->first();
        $data = Language_Meta::where('reference_id', $ref_id->id)
            ->where('reference_type', $ref_type)
            ->where('language', $lang)->first();
        if (!empty($data)) {
            return $data->lang_data;
        } else {
            return $text;
        }
    }
    public static function get_lang_seller_cat($text, $ref_type, $lang)
    {
        $ref_id = SellerCategory::where('category_name', $text)->first();
        $data = Language_Meta::where('reference_id', $ref_id->id)
            ->where('reference_type', $ref_type)
            ->where('language', $lang)->first();
        if (!empty($data)) {
            return $data->lang_data;
        } else {
            return $text;
        }
    }
    public static function get_lang_pro($text, $ref_type, $lang)
    {
        $ref_id = BusinessModel::where('name', $text)->first();
        $data = Language_Meta::where('reference_id', $ref_id->id)
            ->where('reference_type', $ref_type)
            ->where('language', $lang)->first();
        if (!empty($data)) {
            return $data->lang_data;
        } else {
            return $text;
        }
    }
    public static function act_lang($ref_id, $ref_type)
    {
        return $data = Language_Meta::where('reference_id', $ref_id)
            ->where('reference_type', $ref_type)->orderBy('id', 'desc')->get();
    }
    public static function rem_lang($ref_id, $ref_type)
    {
        $lang_set = DB::table('settings')->where('option_name', 'languages')->first();
        $langs = json_decode($lang_set->option_value);
        $array = array();
        foreach ($langs as $lang) {
            $active = Language_Meta::where('reference_id', $ref_id)
                ->where('reference_type', $ref_type)->where('language', $lang)->first();
            if (empty($active)) {
                $data = Config::get('languages')[$lang];
                $array[] = array_merge($data, ['sort' => $lang]);
            }
        }
        return $array;
    }
    public static function get_woo_location($data)
    {
        $exp = explode("|", $data);
        if ($exp[1] == "continent") {
            $continents = Woo_Continent::all();
            foreach ($continents as $continent) {
                $json_data = json_decode($continent->data);
                if ($json_data->code == $exp[0]) {
                    return $json_data->name;
                }
            }
        }
        if ($exp[1] == "country") {
            $countries = Woo_Country::all();
            foreach ($countries as $countrie) {
                $json_data = json_decode($countrie->data);
                if ($json_data->code == $exp[0]) {
                    return $json_data->name;
                }
            }
        }
        if ($exp[1] == "state") {
            $states = Woo_State::all();
            foreach ($states as $state) {
                $json_data = json_decode($state->data);
                if ($json_data->code == $exp[0]) {
                    return $json_data->name;
                }
            }
        }
    }
    public static function get_shipping_address($data)
    {
        $countries = Woo_Country::all();
        foreach ($countries as $countrie) {
            $json_data = json_decode($countrie->data);
            if ($json_data->code == $data) {
                return $json_data->name;
            }
        }
    }
    public static function get_warehouse($productws)
    {
        $warehouseProducts = Stockins_list::select('warehouse_id', 'product_id', 'stock')
            ->where('stock', '>', 0)
            ->groupBy('warehouse_id', 'product_id', 'stock')
            ->get();

        $warehouses = [];
        $products = [
            1 => 3,
            2 => 4,
            3 => 5
        ];

        foreach ($warehouseProducts as $item) {
            $warehouseId = $item->warehouse_id;
            $productId = $item->product_id;
            $stockQuantity = $item->stock;

            if (!isset($warehouses[$warehouseId])) {
                $warehouses[$warehouseId] = [];
            }

            $warehouses[$warehouseId][$productId] = $stockQuantity;
        }

        $maxProductCount = 0;
        $previousWarehouse = [];

        foreach ($warehouses as $warehouseId => $productStocks) {
            $intersection = array_intersect(array_keys($productStocks), array_keys($products));

            if (count($intersection) > $maxProductCount) {
                $maxProductCount = count($intersection);

                $verified_products = [];

                foreach ($products as $productId => $productQty) {
                    if (array_key_exists($productId, $productStocks)) {
                        if ($productStocks[$productId] < $productQty) {
                            $qty = $productStocks[$productId];
                        } else {
                            $qty = $productQty;
                        }

                        $status = ($qty > 0) ? 'true' : 'false';

                        $verified_products[$productId] = [
                            'status' => $status,
                            'qty' => $qty,
                        ];
                    } else {
                        $verified_products[$productId] = [
                            'status' => 'false',
                            'qty' => 0,
                        ];
                    }
                }

                if (!empty($previousWarehouse)) {
                    $previousWarehouseProducts = reset($previousWarehouse);
                    $maxTrueCount_previousWarehouse = count(array_filter($previousWarehouseProducts, function ($item) {
                        return $item["status"] === "true";
                    }));

                    $maxTrueCount_verifiedProduct = count(array_filter($verified_products, function ($item) {
                        return $item["status"] === "true";
                    }));

                    if ($maxTrueCount_previousWarehouse > $maxTrueCount_verifiedProduct) {
                        // Continue processing with the current warehouse
                    } elseif ($maxTrueCount_verifiedProduct > $maxTrueCount_previousWarehouse) {
                        $previousWarehouse = [];
                        $previousWarehouse[$warehouseId] = $verified_products;
                    } else {
                        $previousWarehousePercentage = 0;
                        foreach ($previousWarehouseProducts as $index => $item) {
                            if ($item['status'] === 'true') {
                                $totalval = $products[$index];
                                $availableval = $item['qty'];
                                $percentage = ($availableval / $totalval) * 100;
                                $previousWarehousePercentage += $percentage;
                            }
                        }
                        $verifiedProductsPercentage = 0;
                        foreach ($verified_products as $index => $item) {
                            if ($item['status'] === 'true') {
                                $totalval = $products[$index];
                                $availableval = $item['qty'];
                                $percentage = ($availableval / $totalval) * 100;
                                $verifiedProductsPercentage += $percentage;
                            }
                        }
                        if ($previousWarehousePercentage > $verifiedProductsPercentage) {
                            // Continue processing with the current warehouse
                        } elseif ($verifiedProductsPercentage > $previousWarehousePercentage) {
                            $previousWarehouse = [];
                            $previousWarehouse[$warehouseId] = $verified_products;
                        } else {
                            // Continue processing with the current warehouse
                        }
                    }
                } else {
                    $previousWarehouse[$warehouseId] = $verified_products;
                }
            } elseif (count($intersection) == $maxProductCount && $maxProductCount > 0) {

                $verified_products = [];

                foreach ($products as $productId => $productQty) {
                    if (array_key_exists($productId, $productStocks)) {
                        $availableQty = min($productStocks[$productId], $productQty);
                        $status = ($availableQty > 0) ? 'true' : 'false';

                        $verified_products[$productId] = [
                            'status' => $status,
                            'qty' => $availableQty,
                        ];
                    } else {
                        $verified_products[$productId] = [
                            'status' => 'false',
                            'qty' => 0,
                        ];
                    }
                }

                if (!empty($previousWarehouse)) {
                    $previousWarehouseProducts = reset($previousWarehouse);
                    $maxTrueCount_previousWarehouse = count(array_filter($previousWarehouseProducts, function ($item) {
                        return $item["status"] === "true";
                    }));

                    $maxTrueCount_verifiedProduct = count(array_filter($verified_products, function ($item) {
                        return $item["status"] === "true";
                    }));

                    if ($maxTrueCount_previousWarehouse > $maxTrueCount_verifiedProduct) {
                        // Continue processing with the current warehouse
                    } elseif ($maxTrueCount_verifiedProduct > $maxTrueCount_previousWarehouse) {
                        $previousWarehouse = [];
                        $previousWarehouse[$warehouseId] = $verified_products;
                    } else {
                        $previousWarehousePercentage = 0;
                        foreach ($previousWarehouseProducts as $index => $item) {
                            if ($item['status'] === 'true') {
                                $totalval = $products[$index];
                                $availableval = $item['qty'];
                                $percentage = ($availableval / $totalval) * 100;
                                $previousWarehousePercentage += $percentage;
                            }
                        }
                        $verifiedProductsPercentage = 0;
                        foreach ($verified_products as $index => $item) {
                            if ($item['status'] === 'true') {
                                $totalval = $products[$index];
                                $availableval = $item['qty'];
                                $percentage = ($availableval / $totalval) * 100;
                                $verifiedProductsPercentage += $percentage;
                            }
                        }
                        if ($previousWarehousePercentage > $verifiedProductsPercentage) {
                            // Continue processing with the current warehouse
                        } elseif ($verifiedProductsPercentage > $previousWarehousePercentage) {
                            $previousWarehouse = [];
                            $previousWarehouse[$warehouseId] = $verified_products;
                        } else {
                            // Continue processing with the current warehouse
                        }
                    }
                } else {
                    $previousWarehouse[$warehouseId] = $verified_products;
                }
            }
        }

        if ($previousWarehouse) {
            return array_keys($previousWarehouse)[0];
        }
    }
    public static function get_product_stock($specific_product_id)
    {
        // Calculate stock information
        $totalStock = Stockins_list::where('product_id', $specific_product_id)->sum('stock');
        $soldStock = Stockins_list::where('product_id', $specific_product_id)->sum('sold');
        $reservedStock = Stockins_list::where('product_id', $specific_product_id)->sum('reserved');
        $reservedStockInWarehouse = $reservedStock + $soldStock;
        $availableStockForCustomers = $totalStock - $reservedStockInWarehouse;
        return  $availableStockForCustomers;
    }
    public static function order_view($id, $user_id)
    {
        $user = User::find($user_id);
        $order = Orders::find($id);

        $shipping_address = json_decode($order->shipping_address);
        $seller_datas = SellerCity::where('seller_id', $user->id)->get();
        $shipping_data = "";
        foreach ($seller_datas as $seller_data) {
            $Seller_city = strtolower($seller_data->seller_city_name);
            $order_city = strtolower($shipping_address->city);
            $plan_subscriber = PlanSubscriber::where('user_id', $user->id)->with('plan_get')->first();

            if ($Seller_city == $order_city) {
                $shipping_data = shipping::where('id', $seller_data->admin_city_id)->first();
                $j_data = json_decode($datas->product);
                if ($user->shipping_from_us == 1 && $datas->status != "Send") {
                    if ($shipping_data->SMSA_cities != null) {
                        $datas->shipping_id = $shipping_data->id;
                        $datas->company_name = "SMSA";
                        $datas->update();

                        $shipping_city = Group_city::where('city_id', $datas->shipping_id)->with('shipping_price')->first();
                        if ($shipping_city->shipping_price[0] != null) {
                            $j_plan = json_decode($plan_subscriber->plan_get->shipping_price);
                            $total = $datas->sub_total + $shipping_city->shipping_price[0]->price;
                            if ($j_plan->method == "percentage") {
                                $shipping_discount = ($j_plan->discount / 100) * $shipping_city->shipping_price[0]->price;
                                $full_total = $total - $shipping_discount;
                            } else {
                                $shipping_discount = $shipping_city->shipping_price[0]->price - $j_plan->discount;
                                $full_total = $total - $shipping_discount;
                            }
                            $datas->shipping_fee = $shipping_city->shipping_price[0]->price;
                            $datas->shipping_group = $shipping_city->shipping_price[0]->group_id;
                            $datas->total = $full_total;
                            $datas->remaining = $full_total;
                            $datas->update();
                        } else {
                            $j_data = json_decode($datas->product);
                            $total = $j_data[0]->sub_total - $j_data[0]->plan_product_discount_price;
                            $datas->shipping_fee = 0;
                            $datas->total = $total;
                            $datas->remaining = $total;
                            $datas->update();
                        }
                    }
                } elseif ($user->shipping_from_us != 1 && $datas->status != "Send") {
                    $j_data = json_decode($datas->product);
                    $total = $j_data[0]->sub_total;
                    $datas->shipping_id = null;
                    $datas->company_name = null;
                    $datas->shipping_group = null;
                    $datas->shipping_fee = 0;
                    $datas->total = $total;
                    $datas->remaining = $total;
                    $datas->update();
                }
            }
        }
        return;
    }
    public static function send_order($id, $user_id, OrderController $orderController)
    {
        $admin = User::where('role', 'SuperAdmin')->first();
        $user = User::find($user_id);
        if ($admin->order_process_status == 1) {
            $update = Orders::find($id);
            $json_products = json_decode($update->product);
            $cut_price = 0;
            $remaining_prd = 0;
            foreach ($json_products as $json_product) {
                $product_stock = Helpers::get_product_stock($id, $json_product->p_id);
                if ($product_stock < $json_product->quantity && $product_stock > 0) {
                    $json_product->available_quantity = $product_stock;
                } elseif ($product_stock < $json_product->quantity && $product_stock == 0) {
                    $json_product->status = "out-of-stock";
                    $cut_price = $json_product->total;
                } else {
                    $remaining_prd++;
                }
            }

            if ($remaining_prd > 0) {
                $update->product = json_encode($json_products);
                $update->sub_total -= $cut_price;
                $update->total -= $cut_price;
                $update->remaining -= $cut_price;
                $update->order_status = "Pending";
                $update->receiver_admin = $admin->id;
                $update->admin_id = $admin->id;
                $update->status = "Send";
                $update->update();


                if ($update->order_status == "Pending") {
                    if ($user->order_auto_payment == 1) {
                        $wallet = DB::table('wallets')->where('user_id', $user->id)->first();
                        $current_balance = $wallet->balance;
                        $balance = Crypt::decrypt($current_balance);
                        if ($balance > $update->remaining) {
                            if ($update->remaining != 0) {
                                $orderController->auto_invoice_pay($id);
                            } else {
                                $update->payment = "Paid";
                                $update->update();
                            }

                            //                            $notification = [
                            //                                'type'=> 'order_status',
                            //                                'status'=> $update->order_status,
                            //                                'id'=> $id,
                            //                            ];
                            //                            $warehouses = Warehouse::where('id',$update->order_warehouse_id)->first();
                            //                            foreach (json_decode($warehouses->responsible) as $responsible_user_id)
                            //                            {
                            //                                $user = User::where('id',$responsible_user_id)->first();
                            //                                $user->notify(new MyNotification($notification));
                            //                            }
                            $notification = [
                                'type' => 'order_send',
                                'id' => $update->id,
                            ];
                            $admin->notify(new MyNotification($notification));
                            return;
                        } else {
                            if ($update->payment != "Paid" && $update->remaining != 0) {
                                $delay = now()->addMinutes(1);
                                $notification = [
                                    'type' => 'Pay the Order',
                                    'id' => $id,
                                ];
                                $user = User::where('id', $update->user_id)->first();
                                $user->notify((new MyNotification($notification))->delay($delay));
                                $notification = [
                                    'type' => 'order_send',
                                    'id' => $update->id,
                                ];
                                $user->notify(new MyNotification($notification));
                                return;
                            }
                        }
                    } else {
                        if ($update->payment != "Paid" && $update->remaining != 0) {
                            $delay = now()->addMinutes(1);
                            $notification = [
                                'type' => 'Pay the Order',
                                'id' => $id,
                            ];
                            $user = User::where('id', $update->user_id)->first();
                            $user->notify((new MyNotification($notification))->delay($delay));
                            $notification = [
                                'type' => 'order_send',
                                'id' => $update->id,
                            ];
                            $admin->notify(new MyNotification($notification));
                            return;
                        } else {
                            //                            $notification = [
                            //                                'type'=> 'order_status',
                            //                                'status'=> $update->order_status,
                            //                                'id'=> $id,
                            //                            ];
                            //                            $warehouses = Warehouse::where('id',$update->order_warehouse_id)->first();
                            //                            foreach (json_decode($warehouses->responsible) as $responsible_user_id)
                            //                            {
                            //                                $user = User::where('id',$responsible_user_id)->first();
                            //                                $user->notify(new MyNotification($notification));
                            //                            }
                            $update->payment = "Paid";
                            $update->update();

                            $notification = [
                                'type' => 'order_send',
                                'id' => $update->id,
                            ];
                            $user->notify(new MyNotification($notification));
                            return;
                        }
                    }
                }
            } else {
                $update->remaining = 0;
                $update->product = json_encode($json_products);
                $update->order_status = "Out of Stock";
                $update->update();

                return;
            }
        } else {
            $update = Orders::find($id);
            $update->payment = "Unpaid";
            $update->status = "Send";
            $update->order_status = "New Order";
            $update->receiver_admin = $admin->id;
            $update->admin_id = $admin->id;
            $update->update();

            $notification = [
                'type' => 'order_send',
                'id' => $update->id,
            ];
            $user->notify(new MyNotification($notification));
            return;
        }
    }
}
