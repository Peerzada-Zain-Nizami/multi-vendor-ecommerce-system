<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Final_Stock;
use App\Models\Orders;
use App\Models\Product;
use App\Models\RefundOrder;
use App\Models\SellerApi;
use App\Models\shipping;
use App\Models\ShippingPrice;
use App\Models\SMSACredential;
use App\Models\SMSAorder;
use App\Models\Stock;
use App\Models\Stockins_list;
use App\Models\Tax;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Wooproduct;
use App\Notifications\MyNotification;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Warehouse;
use App\MyClasses\Helpers;
use App\MyClasses\Shopify;
use Session;
use SmsaSDK\Smsa;

class OrderManagementController extends Controller
{
    public function new_orders()
    {
        $orders = Orders::where('status', 'New Order')->orwhere('status', 'Pending')->get();

        return view('Admin.new_orders', ['orders' => $orders]);
    }
    // Order Management
    public function orders_management()
    {
        $user = Auth::user();
        $orders = Orders::all();
        return view('Admin.orders_management', ['orders' => $orders, 'user' => $user]);
    }

    public function refund_cancelorder(Request $request)
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
            $amount = $order->paid;
            $from = Auth::user()->id;
            $user = User::find($order->user_id);
            $to = $user->email;
            $note = "This is Order Payment. Order No is #" . $order->order_no;
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('proof')->extension();
            // for seller
            $wallet = DB::table('wallets')->where('user_id', $from)->first();
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
            $trs->user_id = $from;
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Payment Return";
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
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->transfar_from = "Admin";
            $trs->type = "Return Received";
            $trs->status = "Completed";
            $trs->attach = $file_name;
            $trs->note = $note;
            $trs->save();

            if ($order->status == 'Packed') {
                if ($order->company_name == "SMSA") {
                    $smsa_order = SMSAorder::where('order_id', $order->id)->first();
                    if ($smsa_order) {
                    $smsa = SMSACredential::where('user_id',1)->first();
                    $status = Smsa::cancelShipment($smsa_order->AWB_no, $smsa->passkey, 'no reason');
                    $result = $status->getCancelShipmentResult();
                    $explode_result = explode('::', $result);

                    if ($explode_result[0] == "Failed" && $explode_result[1] == "Cannot cancel Shipment") {
                        return response()->json([
                            'status' => 400,
                            'message' => "Cannot cancel Shipment.",
                        ]);
                    }
                    }
                }
            }

                $old_paid = $order->return_payment;
                $order->payment = "Return Received";
                $order->paid = $order->paid - $amount;
                $order->return_payment = $old_paid + $amount;
                $order->status = 'Cancelled';
                $order->update();

                $request->file('proof')->move(public_path('uploads/proof_slips'), $file_name);

                $notification = [
                    'type' => 'Order payment Return Received',
                    'order' => $order->id,
                ];
                $admin = User::find($order->user_id);
                $user->notify(new MyNotification($notification));
                return response()->json([
                    'status' => 200,
                    'message' => "Payment has been successfully Paid.",
                ]);

        }
    }

    public function on_off(Request $request)
    {
        $user_id = $request->user_id;
        $value = $request->value;
        $user = User::find($user_id);
        $user->order_process_status = $value;
        $user->update();
    }
    public function invoice_checkout_view($id)
    {
        //        $user = Auth::user();
        $order = Orders::find($id);


        //        $json_products = json_decode($datas->product);
        //        $company_orders = \App\Models\CompanyOrder::all();
        //        $warehouse_id = array();
        //        foreach ($company_orders as $company_order)
        //        {
        //            $json_company_order_products = json_decode($company_order->products);
        //            foreach ($json_company_order_products as $json_company_order_product)
        //            {
        //                foreach ($json_products as $json_product)
        //                {
        //                    if ($json_product->product_id == $json_company_order_product->product_id)
        //                    {
        //                        $stock_ins_list = Stockins_list::where('product_id',$json_company_order_product->product_id)->where('warehouse_id',$company_order->warehouse_id)->first();
        //                        $final_stock = Final_Stock::where('stock_ins_list_id',$stock_ins_list->id)->first();
        //                        if ($final_stock->display != 0)
        //                        {
        //                            $warehouse_id[] = $company_order->warehouse_id;
        //                        }
        //                    }
        //                }
        //            }
        //        }
        //        $warehouses = \App\Models\Warehouse::whereIn('id',$warehouse_id)->get();
        return view('Admin.order_checkout', ['order' => $order]);
    }

    public function order_approved(Request $request, $id)
    {
        $order = Orders::find($id);

        if ($request->order_approval == 'Accepted') {

            $order->status = $order->status == "Pending" ? "Processing" : "Pending";
            $order->warehouse = $order->status == "Processing" ? 1 : 0;
            $order->update();

            Session::flash('success', 'The Order has been Approved successfully.');
            return back();
        } else {
            foreach (json_decode($order->product) as $product) {
                $stock = Stockins_list::where('product_id', $product->p_id)->where('warehouse_id', $order->order_warehouse_id)
                    ->first();
                if ($stock) {
                    $stock->available += $product->available_qty;
                    $stock->reserved -= $product->available_qty;
                    $stock->update();
                }
            }

            /*Cancel Order on Shopify Store*/
            $client = Helpers::shopify_client($order->user_id);
            if ($client) {
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
    }

    //Refund order
    public function refunded_orders()
    {
        $user = Auth::user();
        $orders = RefundOrder::with('order')->get();
        return view('Admin.refunded_orders', ['orders' => $orders, 'user' => $user]);
    }
    public function refunded_order_view($id)
    {
        $user = Auth::user();
        $refund_order = RefundOrder::find($id);
        $datas = Orders::find($refund_order->order_id);
        return view('Admin.refunded_orders_view', ['datas' => $datas, 'user' => $user]);
    }
    public function shipping_get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $shipping = shipping::find($request->id);
            return response()->json([
                'status' => "success",
                'message' => 'Your Category has been successfully Created',
                'data' => $shipping,
            ]);
        }
    }
    public function order_status(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        if ($request->status == "Packing") {
            $order_table = Orders::find($id);
            if ($order_table->payment != "Paid" && $order_table->remaining != 0) {
                $notification = [
                    'type' => 'Pay the Order',
                    'id' => $id,
                ];
                $user = User::where('id', $order_table->user_id)->first();
                $user->notify(new MyNotification($notification));
                $order_table->order_status = $request->status;
                $order_table->update();
                Session::flash('success', 'Order has been Packed successfully.');
                return back();
            } else {
                $order_table->order_status = $request->status;
                $order_table->update();
                $notification = [
                    'type' => 'order_status',
                    'status' => $order_table->order_status,
                    'id' => $id,
                ];
                $warehouses = Warehouse::where('id', $order_table->order_warehouse_id)->first();
                foreach (json_decode($warehouses->responsible) as $responsible_user_id) {
                    $user = User::where('id', $responsible_user_id)->first();
                    $user->notify(new MyNotification($notification));
                }
                Session::flash('success', 'Order has been Packed successfully.');
                return back();
            }
        } else {
            $update = Orders::find($id);
            $update->order_status = $request->status;
            $update->receiver_admin = $user_id;
            $update->update();
            $notification = [
                'type' => 'order_status',
                'status' => $request->status,
                'id' => $id,
            ];
            $user = User::where('id', $update->user_id)->first();
            $user->notify(new MyNotification($notification));
            Session::flash('success', 'Order has been successfully Accepted.');
            return back();
        }
    }
    public function send_order(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $update = Orders::find($id);
        $update->order_warehouse_id = $request->warehouse_id;
        $update->admin_id = $user_id;
        $update->update();
        $notification = [
            'type' => 'order_status',
            'status' => $request->status,
            'id' => $id,
        ];
        $warehouses = Warehouse::where('id', $update->order_warehouse_id)->first();
        foreach (json_decode($warehouses->responsible) as $responsible_user_id) {
            $user = User::where('id', $responsible_user_id)->first();
            $user->notify(new MyNotification($notification));
        }
        Session::flash('success', 'Order has been successfully sent the Warehouse Admin.');
        return back();
    }
    public function order_tracking($id)
    {
        $order = Orders::find($id);
        $user = User::find($order->user_id);
        if ($user->shipping_from_us == 1) {
            $smsa =SMSACredential::where('user_id',1)->first();
        }
        else {
            $smsa =SMSACredential::where('user_id',4)->first();
        }
        if ($order->company_name == "SMSA") {
            $smsa_order = SMSAorder::where('order_id', $id)->first();
            $status = Smsa::getTracking($smsa_order->AWB_no, $smsa->passkey);
            $results = $status->getGetTrackingResult();
            $result = \explode('</xs:schema>', $results->getAny());
            $array = json_decode(json_encode((array)simplexml_load_string($result[0])), true);
            $tracking_data = $array['NewDataSet']['Tracking'];
            return view('Admin.tracking', ['datas' => $order, 'tracking_data' => $tracking_data]);
        }
    }
    // For Admin Order refund Approvel
    public function refund_order_status(Request $request, $id)
    {
        $smsa = SMSACredential::where('user_id',1)->first();
        if ($request->status == "cancel-approved" || $request->status == "Cancel-and-refund-approved" || $request->status == "return-approved" || $request->status == "refund-cancel") {
            $order_table = Orders::find($id);
            $Loggeduser = User::find($order_table->user_id);
            if ($Loggeduser->shipping_from_us == 1) {
                $smsa =SMSACredential::where('user_id',1)->first();
            }
            else {
                $smsa =SMSACredential::where('user_id',4)->first();
            }
            $users = User::where('role', "Seller")->get();

            if (in_array($order_table->status,['Collected from Retail','In Transit','Out for Delivery',"Dispatched","DELIVERED"]) && $order_table->api_status == "Refund Requested" && $order_table->refund_status == "Return and Refund") {
                if ($order_table->company_name == "SMSA") {
                    $smsa_order = SMSAorder::where('order_id', $order_table->id)->first();
                    if ($smsa_order) {
                    $status = Smsa::stoShipment($smsa_order->AWB_no, $smsa->passkey);
                    $result = $status->getStoShipmentResult();
                    $explode_result = explode('::', $result);

                    if ($explode_result[0] == "Failed ") {
                        return response()->json([
                            'status' => 400,
                            'message' => $explode_result[1],
                        ]);
                    } else {
                        $order_table->status = "Return Request Sent";
                        $order_table->save();
                        return response()->json([
                            'status' => 200,
                            'message' => "Order Return Request has been sent to Shipping Company.",
                        ]);
                    }
                }
                }
            }
            elseif ($order_table->status == "SMSA Processing" || $order_table->status == 'Packed') {
                if ($order_table->company_name == "SMSA") {
                    $smsa_order = SMSAorder::where('order_id', $order_table->id)->first();
                    if ($smsa_order) {
                    $status = Smsa::cancelShipment($smsa_order->AWB_no, $smsa->passkey, 'no reason');
                    $result = $status->getCancelShipmentResult();
                    $explode_result = explode('::', $result);

                    if ($explode_result[0] == "Failed" && $explode_result[1] == "Cannot cancel Shipment") {
                        return response()->json([
                            'status' => 400,
                            'message' => "Cannot cancel Shipment.",
                        ]);
                    }
                    }
                }
            }
            foreach ($users as $user) {
                if ($request->status == "refund-cancel") {
                    $order_table->refund_status = 'Refund Cancelled';
                }
                elseif ($request->status == "cancel-approved") {
                $order_table->refund_status =  'Cancel Approved';
                $order_table->api_status = 'Cancel Approved';
                }
                elseif ($request->status == "Cancel-and-refund-approved") {
                $order_table->refund_status =  'Cancel and Refund Approved';
                $order_table->api_status = 'Cancel and Refund Approved';
                }
                elseif ($request->status == "return-approved") {
                $order_table->refund_status =  'Refund Approved';
                $order_table->api_status = 'Refund Approved';
            }

                $order_table->update();

                // $woo_shopify = Helpers::woo_shopify_status($order_table->id);
                $refund_orders = new RefundOrder();
                $refund_orders->order_id = $order_table->id;
                $refund_orders->status = "Processing";
                $refund_orders->save();

                $notification = [
                    'type' => 'order_status',
                    'id' => $order_table->id,
                    'status' => $request->status,
                ];
                $user = User::where('id', $order_table->user_id)->first();
                $user->notify(new MyNotification($notification));

            }
            Session::flash('success', 'Order has been successfully Working.');
            return back();
        }
    }
    // Cancelled order from Shipping company on Client Request
    public function send_order_status($id)
    {
        $order = Orders::find($id);
        $user = User::find($order->user_id);
        if ($user->shipping_from_us == 1) {
            $smsa =SMSACredential::where('user_id',1)->first();
        }
        else {
            $smsa =SMSACredential::where('user_id',4)->first();
        }
        if ($order->company_name == "SMSA") {
            $smsa_order = SMSAorder::where('order_id', $order->id)->first();
            $status = Smsa::cancelShipment($smsa_order->AWB_no, $smsa->passkey, 'no reason');
            $result = $status->getCancelShipmentResult();
            $explode_result = explode('::', $result);

            if ($explode_result[0] == "Failed" && $explode_result[1] == "Cannot cancel Shipment") {
                Session::flash('danger', 'Cannot cancel Shipment');
                return back();
            } else {
                $order->order_status = "Order Cancelled";
                $order->save();
                Session::flash('success', 'Order Bill has been successfully Cancelled.');
                return back();
            }
        }
    }
    // Return order from customer to warehouse
    public function return_request_to_shipping_company($id)
    {
        $order = Orders::find($id);
        $user = User::find($order->user_id);
        if ($user->shipping_from_us == 1) {
            $smsa =SMSACredential::where('user_id',1)->first();
        }
        else {
            $smsa =SMSACredential::where('user_id',4)->first();
        }
        if ($order->company_name == "SMSA") {
            $smsa_order = SMSAorder::where('order_id', $order->id)->first();
            $status = Smsa::stoShipment($smsa_order->AWB_no, $smsa->passkey);
            $result = $status->getStoShipmentResult();
            $explode_result = explode('::', $result);

            if ($explode_result[0] == "Failed ") {
                Session::flash('danger', $explode_result[1]);
                return back();
            } else {
                $order->order_status = "Return Request Sent";
                $order->save();
                Session::flash('success', 'Order Return Request has been sent to Shipping Company.');
                return back();
            }
        }
    }
    // Return order payment to our client
    // If Not Shipped means Picked up from warehouse
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

            //return price
            $return_price = ShippingPrice::where('group_id', $order->shipping_group)->first();
            $amount = $order->paid;
            $from = Auth::user()->id;
            $user = User::find($order->user_id);
            $to = $user->email;
            $note = "This is Order Payment. Order No is #" . $order->order_no;
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('proof')->extension();
            // for seller
            $wallet = DB::table('wallets')->where('user_id', $from)->first();
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
            $trs->user_id = $from;
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Payment Return";
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
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->transfar_from = "Admin";
            $trs->type = "Return Received";
            $trs->status = "Completed";
            $trs->attach = $file_name;
            $trs->note = $note;
            $trs->save();


            foreach (json_decode($order->refund_items) as $refund) {
                foreach (json_decode($order->product) as $product) {
                    // $product_price = $product->total - ($product->sub_total * $refund->p_qty);

            $old_paid = $order->return_payment;
            $order->payment = "Return Received";
            $order->paid = $order->paid - $amount;
            if ($order->status == 'DELIVERED') {
                $order->return_payment = $order->paid-$order->shipping_fee-$return_price->return_price;
            }
            elseif ($order->status != 'Delivered' && in_array($order->status,["Collected from Retail","Dispatched","In Transit","Out for Delivery","Delivery Attempted"])) {

                $order->return_payment = $order->paid-$order->shipping_fee;
                $order->status = 'Order Cancelled';

            }
            elseif (in_array($order->status,["Processing","Packed","SMSA Processing"])) {
                $order->return_payment = $order->paid;
                $order->status = 'Order Cancelled';

            }
            $order->refund_status = 'Refunded';
            $order->api_status = 'Refunded';
            $order->update();
        }
      }
            $refund_orders = RefundOrder::where('order_id', $order->id)->first();
            $refund_orders->status = "Complete";
            $refund_orders->update();

            $request->file('proof')->move(public_path('uploads/proof_slips'), $file_name);

            // $woo_shopify = Helpers::woo_shopify_status($order->id);

            $order->refund_status = "Refunded";
            $order->api_status = "Refunded";
            $order->update();
            $notification = [
                'type' => 'order_status',
                'id' => $order->id,
                'status' => "return-approved",
            ];
            $Seller_user = User::where('id', $order->user_id)->first();
            $Seller_user->notify(new MyNotification($notification));

            $notification = [
                'type' => 'Order payment Return Received',
                'order' => $order->id,
            ];
            $admin = User::find($order->user_id);
            $admin->notify(new MyNotification($notification));
            return response()->json([
                'status' => 200,
                'message' => "Payment has been successfully Paid.",
            ]);
        }
    }


    // If order was Cancelled and then return payment
    public function refund_return_pay(Request $request)
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
            $return_price = ShippingPrice::where('group_id', $order->shipping_group)->first();
            $amount = $order->paid - $order->shipping_fee - $return_price->return_price;
            $from = Auth::user()->id;
            $user = User::find($order->user_id);
            $to = $user->email;
            $note = "This is Order Payment. Order No is #" . $order->order_no;
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('proof')->extension();
            // for seller
            $wallet = DB::table('wallets')->where('user_id', $from)->first();
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
            $trs->user_id = $from;
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Payment Return";
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
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->transfar_from = "Admin";
            $trs->type = "Return Received";
            $trs->status = "Completed";
            $trs->attach = $file_name;
            $trs->note = $note;
            $trs->save();

            $old_paid = $order->return_payment;
            $order->payment = "Return Received";
            $order->paid = $order->paid - $amount;
            $order->return_payment = $old_paid + $amount;
            $order->refund_status = "Complete";
            $order->order_status = "Cancelled";
            $order->update();

            $refund_orders = RefundOrder::where('order_id', $order->id)->first();
            $refund_orders->status = "Complete";
            $refund_orders->update();

            $request->file('proof')->move(public_path('uploads/proof_slips'), $file_name);

            $notification = [
                'type' => 'Order payment Return Received',
                'order' => $order->id,
            ];
            $admin = User::find($order->user_id);
            $admin->notify(new MyNotification($notification));
            return response()->json([
                'status' => 200,
                'message' => "Payment has been successfully Paid.",
            ]);
        }
    }
    // If order picked from customer and return then return payment
    public function refund_return_pay_3(Request $request)
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
            $amount = $order->paid - $order->shipping_fee;
            $from = Auth::user()->id;
            $user = User::find($order->user_id);
            $to = $user->email;
            $note = "This is Order Payment. Order No is #" . $order->order_no;
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('proof')->extension();
            // for seller
            $wallet = DB::table('wallets')->where('user_id', $from)->first();
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
            $trs->user_id = $from;
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Payment Return";
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
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->transfar_from = "Admin";
            $trs->type = "Return Received";
            $trs->status = "Completed";
            $trs->attach = $file_name;
            $trs->note = $note;
            $trs->save();

            $old_paid = $order->return_payment;
            $order->payment = "Return Received";
            $order->paid = $order->paid - $amount;
            $order->return_payment = $old_paid + $amount;
            $order->refund_status = "Complete";
            $order->order_status = "Cancelled";
            $order->update();

            $refund_orders = RefundOrder::where('order_id', $order->id)->first();
            $refund_orders->status = "Complete";
            $refund_orders->update();

            $request->file('proof')->move(public_path('uploads/proof_slips'), $file_name);

            $notification = [
                'type' => 'Order payment Return Received',
                'order' => $order->id,
            ];
            $admin = User::find($order->user_id);
            $admin->notify(new MyNotification($notification));
            return response()->json([
                'status' => 200,
                'message' => "Payment has been successfully Paid.",
            ]);
        }
    }

    public function shipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $shipping = shipping::find($request->id);
            return response()->json([
                'status' => "success",
                'message' => 'Your Category has been successfully Created',
                'data' => $shipping,
            ]);
        }
    }
    public function order(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        if ($request->status == "Packing") {
            $order_table = Orders::find($id);
            if ($order_table->payment != "Paid" && $order_table->remaining != 0) {
                $notification = [
                    'type' => 'Pay the Order',
                    'id' => $id,
                ];
                $user = User::where('id', $order_table->user_id)->first();
                $user->notify(new MyNotification($notification));
                $order_table->order_status = $request->status;
                $order_table->update();
                Session::flash('success', 'Order has been Packed successfully.');
                return back();
            } else {
                $order_table->order_status = $request->status;
                $order_table->update();
                $notification = [
                    'type' => 'order_status',
                    'status' => $order_table->order_status,
                    'id' => $id,
                ];
                $warehouses = Warehouse::where('id', $order_table->order_warehouse_id)->first();
                foreach (json_decode($warehouses->responsible) as $responsible_user_id) {
                    $user = User::where('id', $responsible_user_id)->first();
                    $user->notify(new MyNotification($notification));
                }
                Session::flash('success', 'Order has been Packed successfully.');
                return back();
            }
        } else {
            $update = Orders::find($id);
            $update->order_status = $request->status;
            $update->receiver_admin = $user_id;
            $update->update();
            $notification = [
                'type' => 'order_status',
                'status' => $request->status,
                'id' => $id,
            ];
            $user = User::where('id', $update->user_id)->first();
            $user->notify(new MyNotification($notification));
            Session::flash('success', 'Order has been successfully Accepted.');
            return back();
        }
    }
    public function send(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $update = Orders::find($id);
        $update->order_warehouse_id = $request->warehouse_id;
        $update->admin_id = $user_id;
        $update->update();
        $notification = [
            'type' => 'order_status',
            'status' => $request->status,
            'id' => $id,
        ];
        $warehouses = Warehouse::where('id', $update->order_warehouse_id)->first();
        foreach (json_decode($warehouses->responsible) as $responsible_user_id) {
            $user = User::where('id', $responsible_user_id)->first();
            $user->notify(new MyNotification($notification));
        }
        Session::flash('success', 'Order has been successfully sent the Warehouse Admin.');
        return back();
    }
}
