<?php

namespace App\Http\Controllers\Seller;

use Helper;
use Session;
use SmsaSDK\Smsa;
use App\Models\Plan;
use App\Models\User;
use App\Models\Orders;
use App\Models\shipping;
use App\Models\SellerApi;
use App\Models\SMSAorder;
use App\Models\Warehouse;
use App\Models\Group_city;
use App\Models\SellerCity;
use App\MyClasses\Helpers;
use App\MyClasses\Shopify;
use App\Models\Final_Stock;
use App\Models\RefundOrder;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\Stockins_list;
use App\Models\PlanSubscriber;
use App\Models\SMSACredential;
use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MyNotification;
use Illuminate\Support\Facades\Crypt;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function woo_orders_index()
    {
        $user_id = Auth::id();
        $orders = Orders::where('user_id', $user_id)->where('status', 'New Order')->orwhere('status', 'Pending')->get();
        $plan_subscriber = PlanSubscriber::where('user_id', $user_id)->first();
        $plan = Plan::find($plan_subscriber->plan_id);
        return view('Seller.new_orders', ['orders' => $orders, 'plan' => $plan]);
    }

    public function woo_orders_management()
    {
        $user = Auth::user();
        $orders = Orders::where('user_id', $user->id)->get();
        $plan_subscriber = PlanSubscriber::where('user_id', $user->id)->first();
        $plan = Plan::find($plan_subscriber->plan_id);
        return view('Seller.orders_management', ['user' => $user, 'orders' => $orders, 'plan' => $plan]);
    }
    public function on_off(Request $request)
    {
        $user_id = $request->user_id;
        $value = $request->value;
        $user = User::find($user_id);
        $user->shipping_from_us = $value;
        $user->update();
    }
    public function payment_on_off(Request $request)
    {
        $user_id = $request->user_id;
        $value = $request->value;
        $user = User::find($user_id);
        $user->order_auto_payment = $value;
        $user->update();
    }
    public function refunded_orders()
    {
        $user_id = Auth::user()->id;
        $orders = RefundOrder::with('order')->get();
        $plan_subscriber = PlanSubscriber::where('user_id', $user_id)->first();
        $plan = Plan::find($plan_subscriber->plan_id);
        return view('Seller.refunded_orders', ['orders' => $orders, 'plan' => $plan]);
    }
    public function invoice_checkout_view($id)
    {
        $datas = Orders::find($id);
        return view('Seller.my_order_checkout', ['order' => $datas]);
    }

    public function refund_notification($id)
    {
        $admin = User::where('role', 'SuperAdmin')->first();
        $check_notification = Helpers::check_refund_notification($id);
        if ($check_notification  != true) {
            $notification = [
                'type' => 'Refund Order',
                'id' => $id,
            ];
            $admin->notify(new MyNotification($notification));
            Session::flash('success', 'The Order Return and Refund Request has been successfully send.');
            return back();
        }
        return back();
    }
    public function refunded_orders_view($id)
    {
        $user = Auth::user();
        $refund_order = RefundOrder::find($id);
        $datas = Orders::find($refund_order->order_id);
        return view('Seller.refunded_orders_view', ['datas' => $datas, 'user' => $user]);
    }
    // send order to admin
    public function invoice_confirm($id)
    {
        $order = Orders::find($id);
        $user = Auth::user();
        if ($user->order_auto_payment == 1) {
            $balance = Crypt::decrypt($user->seller_wallet->balance);
            if ($balance > $order->remaining && $order->remaining > 0) {
                $this->auto_invoice_pay($order->id);
            } elseif ($order->remaining > 0) {
                $notification = [
                    'type' => 'Insufficient Balance',
                    'id' => $user->id,
                ];
                $user->notify(new MyNotification($notification));
            }
        } elseif ($order->remaining > 0) {
            $notification = [
                'type' => 'Pay the Order',
                'id' => $order->id,
            ];
            $user->notify(new MyNotification($notification));
        }
        $order->is_confirm = true;

        $order->status = $user->order_auto_payment == 0 ? 'Pending' : "Processing";
        $order->warehouse = $order->status == "Processing" ? 1 : 0;
        $order->update();
        Session::flash('success', 'The Order has been confirmed successfully.');
        return back();
    }
    public function invoice_cancel($id)
    {
        $order = Orders::find($id);
        foreach (json_decode($order->product) as $product) {
            $stock = Stockins_list::where('product_id', $product->p_id)->where('warehouse_id', $order->order_warehouse_id)->first();
            if ($stock) {
                $stock->available += $product->available_qty;
                $stock->reserved -= $product->available_qty;
                $stock->update();
            }
        }

        /*Cancel Order on Shopify Store*/
        $api = SellerApi::where('user_id', Auth::user()->id)->first();
        if ($api && !empty($api->shopify_details)) {
            $api_details = json_decode($api->shopify_details);
            $api = decrypt($api_details->api_key);
            $password = decrypt($api_details->access_token);
            $hostname = decrypt($api_details->hostname);
            $client = new Shopify($api, $password, $hostname);

            $cancelPayload = [
                'reason' => 'Product out of stock',
            ];
            $cancelledOrder = $client->post("orders/{$order->order_id}/cancel.json", $cancelPayload);
        }

        $order->status = "Order Cancelled";
        $order->is_confirm = true;
        $order->update();

        Session::flash('success', 'The Order has been cancelled successfully.');
        return back();
    }
    public function send_order($id)
    {
        $admin = User::where('role', 'SuperAdmin')->first();
        $user = Auth::user();
        if ($admin->order_process_status == 1) {
            $update = Orders::find($id);
            $json_products = json_decode($update->product);
            $cut_price = 0;
            $remaining_prd = 0;
            foreach ($json_products as $json_product) {
                $product_stock = Helpers::get_product_stock($id, $json_product->product_id);
                if ($product_stock < $json_product->quantity && $product_stock > 0) {
                    $json_product->status = "out-of-stock";
                    $cut_price = $json_product->total;
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
                $update->order_status = "Packing";
                $update->receiver_admin = $admin->id;
                $update->admin_id = $admin->id;
                $update->status = "Send";
                $update->update();

                if ($user->order_auto_payment == 1) {
                    $this->auto_invoice_pay($id);
                }

                $notification = [
                    'type' => 'order_send',
                    'id' => $update->id,
                ];
                $admin->notify(new MyNotification($notification));
                Session::flash('success', 'Order has been successfully sent.');
                return back();
            } else {
                $update->remaining = 0;
                $update->product = json_encode($json_products);
                $update->order_status = "Out of Stock";
                $update->update();

                Session::flash('danger', 'Order product is Out of stock.');
                return back();
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
            Session::flash('success', 'Order has been successfully sent.');
            return back();
        }

        //        if ($update->order_status == "Packing")
        //        {
        //            if ($user->order_auto_payment == 1)
        //            {
        //                $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
        //                $current_balance = $wallet->balance;
        //                $balance = Crypt::decrypt($current_balance);
        //                if ($balance > $update->remaining)
        //                {
        //                    if ($update->remaining != 0)
        //                    {
        //                        $this->auto_invoice_pay($id);
        //                    }
        //                    else{
        //                        $update->payment = "Paid";
        //                        $update->update();
        //                    }
        //
        //                    $notification = [
        //                        'type'=> 'order_status',
        //                        'status'=> $update->order_status,
        //                        'id'=> $id,
        //                    ];
        //                    $warehouses = Warehouse::where('id',$update->order_warehouse_id)->first();
        //                    foreach (json_decode($warehouses->responsible) as $responsible_user_id)
        //                    {
        //                        $user = User::where('id',$responsible_user_id)->first();
        //                        $user->notify(new MyNotification($notification));
        //                    }
        //                    $notification = [
        //                        'type'=> 'order_send',
        //                        'id'=> $update->id,
        //                    ];
        //                    $admin->notify(new MyNotification($notification));
        //                    Session::flash('success', 'Order has been successfully sent.');
        //                    return back();
        //                }
        //                else{
        //                    if ($update->payment != "Paid" && $update->remaining != 0)
        //                    {
        //                        $notification = [
        //                            'type'=> 'Pay the Order',
        //                            'id'=> $id,
        //                        ];
        //                        $user = User::where('id',$update->user_id)->first();
        //                        $user->notify(new MyNotification($notification));
        //                        $notification = [
        //                            'type'=> 'order_send',
        //                            'id'=> $update->id,
        //                        ];
        //                        $user->notify(new MyNotification($notification));
        //                        Session::flash('success', 'Order has been successfully sent.');
        //                        Session::flash('danger', ' Your Balance is not sufficient.');
        //                        return back();
        //                    }
        //                }
        //            }
        //            else{
        //                if ($update->payment != "Paid" && $update->remaining != 0)
        //                {
        //                    $notification = [
        //                        'type'=> 'Pay the Order',
        //                        'id'=> $id,
        //                    ];
        //                    $user = User::where('id',$update->user_id)->first();
        //                    $user->notify(new MyNotification($notification));
        //                    $notification = [
        //                        'type'=> 'order_send',
        //                        'id'=> $update->id,
        //                    ];
        //                    $admin->notify(new MyNotification($notification));
        //                    Session::flash('success', 'Order has been successfully sent.');
        //                    return back();
        //                }
        //                else{
        //                    $notification = [
        //                        'type'=> 'order_status',
        //                        'status'=> $update->order_status,
        //                        'id'=> $id,
        //                    ];
        //                    $warehouses = Warehouse::where('id',$update->order_warehouse_id)->first();
        //                    foreach (json_decode($warehouses->responsible) as $responsible_user_id)
        //                    {
        //                        $user = User::where('id',$responsible_user_id)->first();
        //                        $user->notify(new MyNotification($notification));
        //                    }
        //                    $notification = [
        //                        'type'=> 'order_send',
        //                        'id'=> $update->id,
        //                    ];
        //                    $user->notify(new MyNotification($notification));
        //                    Session::flash('success', 'Order has been successfully sent.');
        //                    return back();
        //                }
        //            }
        //        }

    }
    public function auto_invoice_pay($id)
    {
        $order = Orders::find($id);
        $amount = $order->remaining;
        $from = User::find($order->user_id);
        $user = User::find($order->admin_id);
        $to = $user->email;
        $note = "This is Order Payment. Order No is #" . $order->order_no;
        // for seller
        $wallet = DB::table('wallets')->where('user_id', $from->id)->first();
        $old_balance = $wallet->balance;
        $balance = Crypt::decrypt($old_balance);

        $new_balance = $balance - $amount;
        $final_balance = Crypt::encrypt($new_balance);
        DB::table('wallets')->where('user_id', $from)->update(['balance' => $final_balance]);
        $new_amount =  Crypt::encrypt($amount);
        $tr = null;
        $old_tr = Transactions::select('transaction_id')->latest()->first();
        if (!empty($old_tr)) {
            $tr = $old_tr->transaction_id + 1;
        } else {
            $tr = '20211000';
        }
        $trs = new Transactions();
        $trs->transaction_id = $tr;
        $trs->user_id = $from->id;
        $trs->cash_out = $new_amount;
        $trs->previous_balance = $old_balance;
        $trs->type = "Order Payment";
        $trs->status = "Completed";
        $trs->transfar_to = $to;
        $trs->note = $note;
        $trs->save();

        // for admin
        $wallet = DB::table('wallets')->where('user_id', $user->id)->first();
        $old_balance = $wallet->balance;
        $balance = Crypt::decrypt($old_balance);
        $new_balance = $balance + $amount;
        $final_balance = Crypt::encrypt($new_balance);
        DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $final_balance]);

        $new_amount =  Crypt::encrypt($amount);
        $tr1 = null;
        $old_tr1 = Transactions::select('transaction_id')->latest()->first();
        if (!empty($old_tr1)) {
            $tr1 = $old_tr1->transaction_id + 1;
        } else {
            $tr1 = '20211000';
        }
        $trs = new Transactions();
        $trs->transaction_id = $tr1;
        $trs->user_id = $user->id;
        $trs->cash_in = $new_amount;
        $trs->previous_balance = $old_balance;
        $trs->transfar_from = $from->email;
        $trs->type = "Order Payment";
        $trs->status = "Completed";
        $trs->note = $note;
        $trs->save();

        $old_paid = $order->paid;
        $order->payment = "Paid";
        $order->remaining = 0;
        $order->paid = $old_paid + $amount;
        $order->status = $order->status == "Pending" ? "Processing" : ($order->status == "New Order" ? "Pending" : "New Order");
        $order->warehouse = $order->status == "Processing" ? 1 : 0;
        $order->update();

        $notification = [
            'type' => 'New Order',
            'id' => $id,
        ];
        $warehouse_id = $order->order_warehouse_id;
        $warehouse = Warehouse::where('id', $warehouse_id)->first();
        $warehouse_responsibles = json_decode($warehouse->responsible);
        foreach ($warehouse_responsibles as $warehouse_admin) {
            $user = User::where('id', $warehouse_admin)->first();
            $user->notify(new MyNotification($notification));
        }
        $notification = [
            'type' => 'order_full_pay',
            'trs' => $tr1,
        ];
        $admin = User::find($order->admin_id);
        $admin->notify(new MyNotification($notification));
        return;
    }
    // order payment
    public function invoice_pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'numeric'],
            'proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $order = Orders::find($request->id);
            $amount = $order->remaining;
            $from = Auth::user();
            $user = User::find($order->admin_id);
            $to = $user->email;
            $note = "This is Order Payment. Order No is #" . $order->order_no;
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('proof')->extension();
            // for seller
            $wallet = DB::table('wallets')->where('user_id', $from->id)->first();
            $old_balance = $wallet->balance;
            $balance = Crypt::decrypt($old_balance);
            if ($amount > $balance) {
                return response()->json([
                    'status' => 400,
                    'errors' => ["Not Enough Balance"],
                ]);
            }
            $new_balance = $balance - $amount;
            $final_balance = Crypt::encrypt($new_balance);
            DB::table('wallets')->where('user_id', $from->id)->update(['balance' => $final_balance]);
            $new_amount =  Crypt::encrypt($amount);
            $tr = null;
            $old_tr = Transactions::select('transaction_id')->latest()->first();
            if (!empty($old_tr)) {
                $tr = $old_tr->transaction_id + 1;
            } else {
                $tr = '20211000';
            }
            $trs = new Transactions();
            $trs->transaction_id = $tr;
            $trs->user_id = $from->id;
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Order Payment";
            $trs->status = "Completed";
            $trs->transfar_to = $to;
            $trs->attach = $file_name;
            $trs->note = $note;
            $trs->save();

            // for admin
            $wallet = DB::table('wallets')->where('user_id', $user->id)->first();
            $old_balance = $wallet->balance;
            $balance = Crypt::decrypt($old_balance);
            $new_balance = $balance + $amount;
            $final_balance = Crypt::encrypt($new_balance);
            DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $final_balance]);

            $new_amount =  Crypt::encrypt($amount);
            $tr1 = null;
            $old_tr1 = Transactions::select('transaction_id')->latest()->first();
            if (!empty($old_tr1)) {
                $tr1 = $old_tr1->transaction_id + 1;
            } else {
                $tr1 = '20211000';
            }
            $trs = new Transactions();
            $trs->transaction_id = $tr1;
            $trs->user_id = $user->id;
            $trs->cash_in = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->transfar_from = $from->email;
            $trs->type = "Order Payment";
            $trs->status = "Completed";
            $trs->attach = $file_name;
            $trs->note = $note;
            $trs->save();

            $old_paid = $order->paid;
            $order->payment = "Paid";
            $order->remaining = 0;
            $order->paid = $old_paid + $amount;
            // $order->status = $order->status == "Pending" ? "Processing" : ($order->status == "New Order" ? "Pending" : "New Order");
            $order->status = $order->status == "Pending" ? "Processing" : ($order->status == "New Order" ? "Pending" : ($order->status == "Processing" ? "Processing" : "New Order"));
            $order->warehouse = $order->status == "Processing" ? 1 : 0;
            $order->update();
            $request->file('proof')->move(public_path('uploads/proof_slips'), $file_name);

            if (!empty($order->shipping_id)) {
                $this->generate_shipment_waybill($request->id, $from->id);
            }

            $notification = [
                'type' => 'New Order',
                'id' => $order->id,
            ];
            $warehouse_id = $order->order_warehouse_id;
            $warehouse = Warehouse::where('id', $warehouse_id)->first();
            $warehouse_responsibles = json_decode($warehouse->responsible);
            foreach ($warehouse_responsibles as $warehouse_admin) {
                $user = User::where('id', $warehouse_admin)->first();
                $user->notify(new MyNotification($notification));
            }
            $notification = [
                'type' => 'order_full_pay',
                'trs' => $tr1,
            ];
            $admin = User::find($order->admin_id);
            $admin->notify(new MyNotification($notification));
            return response()->json([
                'status' => 200,
                'message' => "Payment has been successfully Paid.",
            ]);
        }
    }
    // add shippment detail
    public function generate_shipment_waybill($id, $user_id)
    {
        $user = User::find($user_id);
        if ($user->shipping_from_us == 1) {
            $smsa = SMSACredential::where('user_id', 1)->first();
        } else {
            $smsa = SMSACredential::where('user_id', 4)->first();
        }
        $order = Orders::find($id);
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
            'cName' => $consignee_data->first_name . ' ' . $consignee_data->last_name,
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
        $awbNumber = $status->getAddShipResult();
        if (is_numeric($awbNumber)) {
            $SMSA_order = SMSAorder::where('order_id', $id)->first();
            if (empty($SMSA_order)) {
                $SMSAadd = new SMSAorder();
                $SMSAadd->order_id = $id;
                $SMSAadd->AWB_no = $awbNumber;
                $SMSAadd->shipper = json_encode($shipper);
                $SMSAadd->consignee = json_encode($consignee);
                $SMSAadd->data = json_encode($data);
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
    // order tracking
    public function order_tracking($id)
    {
        $order = Orders::find($id);
        $user = User::find($order->user_id);
        if ($user->shipping_from_us == 1) {
            $smsa = SMSACredential::where('user_id', 1)->first();
        } else {
            $smsa = SMSACredential::where('user_id', 4)->first();
        }
        if ($order->company_name == "SMSA") {
            $smsa_order = SMSAorder::where('order_id', $id)->first();
            $status = Smsa::getTracking($smsa_order->AWB_no, $smsa->passkey);
            $results = $status->getGetTrackingResult();
            $result = \explode('</xs:schema>', $results->getAny());
            $array = json_decode(json_encode((array)simplexml_load_string($result[0])), true);
            $tracking_data = $array['NewDataSet']['Tracking'];
            return view('Seller.tracking', ['datas' => $order, 'tracking_data' => $tracking_data]);
        }
    }
    // refund status
    public function send_refund_order(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_value' => ['required'],
        ]);
        if ($validator->fails()) {
            return back()->with('danger', $validator->errors());
        } else {
            $update = Orders::find($id);
            if ($update->payment != "Paid") {
                $update->refund_status = "Cancellation Request";
                $update->update();
            } else {
                $update->refund_status = $request->status_value;
                $update->update();
            }
            $notification = [
                'type' => 'order_status',
                'id' => $update->id,
                'status' => "Refund Requested",
            ];
            $user = User::where('id', $update->admin_id)->first();
            $user->notify(new MyNotification($notification));
            Session::flash('success', 'The Order Return and Refund Request has been successfully send.');
            return back();
        }
    }
}
