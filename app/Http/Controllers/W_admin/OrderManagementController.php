<?php

namespace App\Http\Controllers\W_admin;

use App\Http\Controllers\Controller;
use App\Models\Final_Stock;
use App\Models\Orders;
use App\Models\Placement;
use App\Models\Placement_list;
use App\Models\Product;
use App\Models\Rack;
use App\Models\RefundOrder;
use App\Models\Room_Block;
use App\Models\Shelf;
use Illuminate\Http\Response;
use App\Models\shipping;
use App\Models\SMSAorder;
use App\Models\StockIn;
use App\Models\Stockins_list;
use App\Models\SMSACredential;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Notifications\MyNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
//use Automattic\WooCommerce\Client;
use App\Models\SellerApi;
use Session;
use SmsaSDK\Smsa;
//use Mohannadnaj\SMSA\Facades\SMSA;
//use Mohannadnaj\SMSA\Types\Waybill;
use GuzzleHttp\Client;
use App\MyClasses\Shopify;
use PDF;
use PhpParser\Node\Expr\Isset_;
use App\MyClasses\Helpers;

class OrderManagementController extends Controller
{
    public function woo_new_orders()
    {
        $user = Auth::user()->id;
        $lists = array();
        $warehouses0 = Warehouse::all();
        foreach ($warehouses0 as $warehouse) {
            if (in_array($user, json_decode($warehouse->responsible))) {
                $lists[] = $warehouse->id;
            }
        }
        $orders = Orders::where('status', 'Processing')->whereIn('payment', array('Return Received', 'Paid'))->whereIn('order_warehouse_id', $lists)->get();
        return view('W_admin.new_orders', ['orders' => $orders]);
    }
    public function woo_orders_management()
    {
        $user = Auth::user()->id;

        $lists = array();
        $warehouses0 = Warehouse::all();
        foreach ($warehouses0 as $warehouse) {
            if (in_array($user, json_decode($warehouse->responsible))) {
                $lists[] = $warehouse->id;
            }
        }
        $orders = Orders::where('warehouse', 1)->whereIn('payment', array('Return Received', 'Paid'))->whereIn('order_warehouse_id', $lists)->get();
        return view('W_admin.orders_management', ['orders' => $orders]);
    }
    public function invoice_checkout_view($id)
    {
        $datas = Orders::find($id);
        $count_of_original_data = count(json_decode($datas->product));
        $array_check = array();
        foreach (json_decode($datas->product) as $data) {
            $stock_ins_list = Stockins_list::where('product_id', $data->p_id)->where('warehouse_id', $datas->order_warehouse_id)->first();
            $final_stock = Final_Stock::where('stock_ins_id', $stock_ins_list->stock_ins_id)->where('warehouse_id', $stock_ins_list->warehouse_id)->first();
            if ($final_stock->display != 0) {
                $array_check[] = $data->p_id;
            }
        }
        $count_of_array_data = count($array_check);
        $flag = false;
        if ($count_of_original_data == $count_of_array_data) {
            $flag = true;
        }
        return view('W_admin.order_checkout', ['datas' => $datas, 'flag' => $flag]);
    }


    public function scanner_product(Request $request)
    {
        $productId = Stockins_list::where('stock_ins_id', $request->p_id)->value('product_id');
        $order_id = $request->order_id;
        $order = Orders::find($order_id);
        $products = json_decode($order->product);
        $order_product_quantity = null;
        foreach ($products as  $product) {
            if ($product->p_id == $productId) {
                $order_product_quantity = $product->available_qty - $product->packed_qty;
            }
        }
        if ($order_product_quantity == null) {
            return response()->json([
                'status' => "danger",
                'message' => 'Your Product has not Matched.',
            ]);
        }
        if ($request->shelf_id) {
            $placement = Placement::where('stock_in_id', $request->p_id)->where('warehouse_id', $order->order_warehouse_id)->where('shelf_id', $request->shelf_id)->first();

            if ($placement) {
                $available_final_stock = ($order_product_quantity >  $placement->quantity) ?  $placement->quantity : $order_product_quantity;
                $remaininQuantity = $placement->quantity - $order_product_quantity;
                if ($remaininQuantity <= 0) {
                    $placement->delete();
                } else {
                    $placement->quantity = $remaininQuantity;
                    $placement->update();
                }

                $shelf = Shelf::where('id', $placement->shelf_id)->first('warehouse_id');
                // $stock_ins_list = Stockins_list::where('stock_ins_id', $placement->stock_in_id)
                //     ->where('product_id', $productId)
                //     ->where('warehouse_id', $shelf->warehouse_id)->first();



                $final_stock = Final_Stock::where('stock_ins_id', $placement->stock_in_id)->where('warehouse_id', $order->order_warehouse_id)->first();
                $final_stock->display -= $available_final_stock;
                $final_stock->selected_stock += $available_final_stock;
                $final_stock->update();

                $add0 = new Placement_list();
                $add0->placement_id = $placement->id;
                $add0->user_id = Auth::user()->id;
                $add0->shelf_id = $placement->shelf_id;
                $add0->stock_in_id = $placement->stock_in_id;
                $add0->quantity = $available_final_stock;
                $add0->type = "out";
                $add0->save();


                //Update packed quantity
                foreach ($products as &$product) {
                    if ($product->p_id == $productId) {
                        $product->packed_qty += $available_final_stock;
                    }
                }
                $order->product = json_encode($products);
                $order->update();

                Helpers::packed_order($order_id);

                return response()->json([
                    'status' => "success",
                    'message' => 'Your Product has been Stocked Out successfully',
                ]);
            } else {
                return response()->json([
                    'status' => "danger",
                    'message' => "Shelf doesn't Contain this Product.",
                ]);
            }
        } else {
            return response()->json([
                'status' => "success",
                'message' => 'This Product has scanned Successfully.',
            ]);
        }
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
            return view('W_admin.tracking', ['datas' => $order, 'tracking_data' => $tracking_data]);
        }
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
        $user = Auth::user()->id;
        $update = Orders::find($id);
        $update->status = $request->status;
        $update->receiver_wadmin = $user;
        $update->update();
        $notification = [
            'type' => 'order_status',
            'status' => $request->status,
            'id' => $id
        ];
        $users = User::where('role', 'SuperAdmin')->get();
        foreach ($users as $user) {
            $user->notify(new MyNotification($notification));
        }
        Session::flash('success', 'Order has been successfully Working.');
        return back();
    }

    public function product_place_tracking_page($id)
    {
        $order = Orders::find($id);
        $j_products = json_decode($order->product);
        $final_array = array();
        foreach ($j_products as $j_product) {
            $stock_ins_list = Stockins_list::where('product_id', $j_product->p_id)->where('warehouse_id', $order->order_warehouse_id)->first();
            $results = Placement::with('shelf_get', 'stock_in_get')->where('stock_in_id', $stock_ins_list->stock_ins_id)->get();
            foreach ($results as $result) {
                $shelf_get = $result->shelf_get[0];
                $warehouse_get = Warehouse::find($shelf_get->warehouse_id);
                $room_get = Room_Block::find($shelf_get->block_id);
                $rack_get = Rack::find($shelf_get->rack_id);
                $product = Product::find($result->stock_in_get[0]->product_id);
                $array = array();
                $array['stock'] = $result;
                $array['product'] = $product;
                $array['warehouse'] = $warehouse_get;
                $array['block'] = $room_get;
                $array['rack'] = $rack_get;
                $array['shelf'] = $shelf_get;
                $final_array[] = $array;
            }
        }
        return view('W_admin.out_from_stock', ['datas' => $final_array, 'order_id' => $id]);
    }
    public function product_place_stock_out($id, $order_id)
    {
        $shelf = Shelf::find($id);
        $stocks = Stockins_list::where('warehouse_id', $shelf->warehouse_id)->get();
        $final_stock = array();
        foreach ($stocks as $stock) {
            $placement = Placement::where('shelf_id', $id)->where('stock_in_id', $stock->stock_ins_id)->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
            if ($placement != 0) {
                $final_stock[] = [
                    'stock' => $stock,
                    'placement' => $placement,
                ];
            }
        }
        Cookie::queue(Cookie::forget('stock_out'));
        return view('W_admin.stock_out_for_order', ['shelf' => $shelf, 'order_id' => $order_id, 'stocks' => $final_stock]);
    }
    public function get_stock_out_length(Request $request)
    {
        $shelf = Shelf::find($request->shelf_id);
        $stock = Stockins_list::where('stock_ins_id', $request->id)->where('warehouse_id', $shelf->warehouse_id)->first();
        if ($stock) {
            $placement = Placement::where('shelf_id', $shelf->id)->where('stock_in_id', $stock->stock_ins_id)->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
            $final_stock = Final_Stock::where('stock_ins_id', $stock->stock_ins_id)->where('warehouse_id', $stock->warehouse_id)->first();
            if (!empty($final_stock)) {
                $old_data = Cookie::get('stock_out');
                if (empty($old_data)) {
                    $order = Orders::find($request->order_id);
                    $products = json_decode($order->product);
                    foreach ($products as $product) {
                        if ($product->product_id == $stock->product_id) {
                            if ($placement >= $product->quantity) {
                                if ($product->quantity > $request->old_qty) {
                                    return response()->json([
                                        'final_stock' => $product->quantity,
                                        'data' => $stock,
                                    ]);
                                } else {
                                    return response()->json([
                                        'status' => "fail",
                                        'data' => $stock,
                                        'errors' => ['Invalid Product Stock'],
                                    ]);
                                }
                            } else {
                                return response()->json([
                                    'status' => "fail",
                                    'errors' => ['Invalid Product Stock'],
                                ]);
                            }
                        } else {
                            return response()->json([
                                'status' => "fail",
                                'errors' => ['Invalid Barcode'],
                            ]);
                        }
                    }
                } else {
                    $data = json_decode($old_data, true);
                    $remaining_stock = null;
                    foreach ($data as $product) {
                        if ($product['stockins_list_id'] == $stock->id) {
                            $order = Orders::find($request->order_id);
                            $products = json_decode($order->product);
                            foreach ($products as $product0) {
                                if ($product0->product_id == $stock->product_id) {
                                    if ($placement >= $product0->quantity) {
                                        $remaining_stock = $product0->quantity - $product['quantity'];
                                    }
                                } else {
                                    return response()->json([
                                        'status' => "fail",
                                        'errors' => ['Invalid Barcode'],
                                    ]);
                                }
                            }
                        } else {
                            return response()->json([
                                'status' => "fail",
                                'errors' => ['Invalid Barcode'],
                            ]);
                        }
                    }
                    return response()->json([
                        'final_stock' => $remaining_stock,
                        'data' => $stock,
                    ]);
                }
            } else {
                return response()->json([
                    'status' => "fail",
                    'errors' => ['Invalid Barcode'],
                ]);
            }
        } else {
            return response()->json([
                'status' => "fail",
                'errors' => ['Invalid Barcode'],
            ]);
        }
    }
    public function shelf_stock_out(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_barcode' => ['required'],
            'order_id' => ['required'],
            'shelf_id' => ['required'],
            'stock_ins' => ['required'],
            'stockins_list_id' => ['required'],
            'product_quantity' => ['required', 'numeric', 'min:1'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $barcode_id = $request->product_barcode;
            $order_id = $request->order_id;
            $shelf_id = $request->shelf_id;
            $stock_ins = $request->stock_ins;
            $stockins_list_id = $request->stockins_list_id;
            $quantity = $request->product_quantity;
            $check = StockIn::find($barcode_id);
            if (empty($check)) {
                return response()->json([
                    'status' => "fail",
                    'errors' => ['Invalid Barcode'],
                ]);
            } else {
                $old_data = Cookie::get('stock_out');
                $shelf = Shelf::find($shelf_id);
                $placement = Placement::where('shelf_id', $shelf->id)->where('stock_in_id', $stock_ins)->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
                $stock_quantity = intval($placement);
                $total_qty = null;
                if (!empty($old_data)) {
                    $json_datas = json_decode($old_data);
                    foreach ($json_datas as $json_data) {
                        if ($json_data->barcode_id == $barcode_id && $json_data->stockins_list_id == $stockins_list_id && $json_data->shelf_id == $shelf_id && $json_data->stock_ins == $stock_ins) {
                            $order = Orders::find($order_id);
                            $products = json_decode($order->product);
                            foreach ($products as $product0) {
                                $stock = Stockins_list::find($stockins_list_id);
                                if ($product0->product_id == $stock->product_id) {
                                    if ($stock_quantity > $product0->quantity) {
                                        if ($product0->quantity > $json_data->quantity) {
                                            $total_qty = $json_data->quantity + $quantity;
                                        } else {
                                            $array[] = $json_data;
                                            return response()->json([
                                                'status' => "fail",
                                                'errors' => ['Invalid Product Stock'],
                                            ]);
                                        }
                                    } else {
                                        return response()->json([
                                            'status' => "fail",
                                            'errors' => ['Invalid Product Stock'],
                                        ]);
                                    }
                                } else {
                                    return response()->json([
                                        'status' => "fail",
                                        'errors' => ['Invalid Product Stock'],
                                    ]);
                                }
                            }
                        }
                    }
                } else {
                    $total_qty = $quantity;
                }
                if ($stock_quantity == 0  || $stock_quantity < $total_qty) {
                    return response()->json([
                        'status' => "fail",
                        'errors' => ['Invalid Product Stock'],
                    ]);
                }
                if (empty($old_data)) {
                    $array_data[] = [
                        'barcode_id' => $barcode_id,
                        'order_id' => $order_id,
                        'shelf_id' => $shelf_id,
                        'stock_ins' => $stock_ins,
                        'stockins_list_id' => $stockins_list_id,
                        'quantity' => $quantity,
                    ];
                    Cookie::queue('stock_out', json_encode($array_data));
                    return response()->json([
                        'status' => "success",
                    ]);
                } else {
                    $data = json_decode($old_data, true);
                    $check = null;
                    $key = null;
                    foreach ($data as $key_value => $product) {
                        if ($product['barcode_id'] == $barcode_id) {
                            $check = true;
                            $key = $key_value;
                        }
                    }
                    if ($check == true) {
                        $sum = $data[$key]['quantity'] + $quantity;
                        $data[$key]['quantity'] = $sum;
                        Cookie::queue('stock_out', json_encode($data));
                        return response()->json([
                            'status' => "success",
                        ]);
                    } else {
                        $array_data = [
                            'barcode_id' => $barcode_id,
                            'order_id' => $order_id,
                            'shelf_id' => $shelf_id,
                            'stock_ins' => $stock_ins,
                            'stockins_list_id' => $stockins_list_id,
                            'quantity' => $quantity,
                        ];
                        array_push($data, $array_data);
                        Cookie::queue('stock_out', json_encode($data));
                        return response()->json([
                            'status' => "success",
                        ]);
                    }
                }
            }
        }
    }
    public function shelf_stockOut_data()
    {
        $cookie_stock_out = Cookie::get('stock_out');
        $stock_outs = json_decode($cookie_stock_out, true);
        $outs = array();
        if (!empty($stock_outs)) {
            foreach ($stock_outs as $product0) {
                $fetch = StockIn::find($product0['barcode_id']);
                $get_product = Product::find($fetch->product_id);
                $data['product'] = $get_product;
                $newproduct = array_merge($product0, $data);
                array_push($outs, $newproduct);
            }
        }
        return response()->json([
            'stock_out' => $outs,
        ]);
    }
    public function shelf_stockout_del(Request $request)
    {
        $id = $request->id;
        $Cookie_outs_data = Cookie::get('stock_out');
        $outs_data = json_decode($Cookie_outs_data, true);
        $array = array();
        foreach ($outs_data as $out_data) {
            if ($out_data['barcode_id'] != $id) {
                $array[] = $out_data;
            }
        }
        Cookie::queue('stock_out', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }
    public function shelf_stockout_minus(Request $request)
    {
        $id = $request->id;
        $Cookie_outs_data = Cookie::get('stock_out');
        $outs_data = json_decode($Cookie_outs_data, true);
        $array = array();
        foreach ($outs_data as $out_data) {
            if ($out_data['barcode_id'] == $id) {
                if ($out_data['quantity'] == 1) {
                    $array[] = $out_data;
                } else {
                    $out_data['quantity'] = $out_data['quantity'] - 1;
                    $array[] = $out_data;
                }
            } else {
                $array[] = $out_data;
            }
        }
        Cookie::queue('stock_out', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }
    public function shelf_stockout_plus(Request $request)
    {
        $id = $request->id;
        $Cookie_outs_data = Cookie::get('stock_out');
        $outs_data = json_decode($Cookie_outs_data, true);
        $array = array();
        foreach ($outs_data as $out_data) {
            if ($out_data['barcode_id'] == $id) {
                $stockins_list = Stockins_list::find($out_data['stockins_list_id']);
                $shelf = Shelf::find($out_data['shelf_id']);
                $total_qty = null;
                $placement = Placement::where('shelf_id', $shelf->id)->where('stock_in_id', $out_data['stock_ins'])->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
                if ($placement) {
                    $order = Orders::find($out_data['order_id']);
                    $products = json_decode($order->product);
                    $stock_quantity = intval($placement);
                    foreach ($products as $product) {
                        if ($product->product_id == $stockins_list->product_id) {
                            if ($stock_quantity > $out_data['quantity']) {
                                if ($product->quantity > $out_data['quantity']) {
                                    $out_data['quantity'] = $out_data['quantity'] + 1;
                                    $array[] = $out_data;
                                } else {
                                    $array[] = $out_data;
                                    return response()->json([
                                        'status' => "fail",
                                        'errors' => ['Invalid Product Stock'],
                                    ]);
                                }
                            } else {
                                $array[] = $out_data;
                                return response()->json([
                                    'status' => "fail",
                                    'errors' => ['Invalid Product Stock'],
                                ]);
                            }
                        }
                    }
                } else {
                    if ($stockins_list->stock > $out_data['quantity']) {
                        $out_data['quantity'] = $out_data['quantity'] + 1;
                        $array[] = $out_data;
                    } else {
                        $array[] = $out_data;
                        return response()->json([
                            'status' => "fail",
                            'errors' => ['Invalid Product Stock'],
                        ]);
                    }
                }
            } else {
                $array[] = $out_data;
            }
        }
        Cookie::queue('stock_out', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }
    public function shelf_stockOut_update($id)
    {
        $Cookie_outs_data = Cookie::get('stock_out');
        $outs_data = json_decode($Cookie_outs_data, true);
        if (!empty($outs_data)) {
            foreach ($outs_data as $out_data) {
                $check = Placement::where('shelf_id', $id)->where('stock_in_id', $out_data['barcode_id'])->first();
                if (!empty($check)) {
                    $old_quantity = $check->quantity;
                    $new = $old_quantity - $out_data['quantity'];
                    $check->quantity = $new;
                    $check->update();

                    $final_stock = Final_Stock::where('stock_ins_id', $out_data['barcode_id'])->where('warehouse_id', $check->warehouse_id)->first();
                    $final_stock->display = $final_stock->display - $out_data['quantity'];
                    $final_stock->selected_stock = $final_stock->selected_stock + $out_data['quantity'];
                    $final_stock->update();

                    $order = Orders::find($out_data['order_id']);
                    $order->order_status = "Packed";
                    $order->update();

                    $add0 = new Placement_list();
                    $add0->placement_id = $check->id;
                    $add0->user_id = Auth::user()->id;
                    $add0->shelf_id = $id;
                    $add0->stock_in_id = $out_data['barcode_id'];
                    $add0->quantity = $out_data['quantity'];
                    $add0->type = "out";
                    $add0->save();
                } else {
                    $final_stock = Final_Stock::where('stock_ins_id', $out_data['barcode_id'])->where('warehouse_id', $check->warehouse_id)->first();
                    $final_stock->display = $final_stock->display - $out_data['quantity'];
                    $final_stock->selected_stock = $final_stock->selected_stock + $out_data['quantity'];;
                    $final_stock->update();

                    $add = new Placement();
                    $add->user_id = Auth::user()->id;
                    $add->shelf_id = $id;
                    $add->stock_in_id = $out_data['barcode_id'];
                    $add->quantity = $out_data['quantity'];
                    $add->save();

                    $order = Orders::where('id', $out_data['order_id'])->first();
                    $order->order_status = "Packed";
                    $order->update();

                    $add0 = new Placement_list();
                    $add0->placement_id = $add->id;
                    $add0->user_id = Auth::user()->id;
                    $add0->shelf_id = $id;
                    $add0->stock_in_id = $out_data['barcode_id'];
                    $add0->quantity = $out_data['quantity'];
                    $add0->type = "out";
                    $add0->save();
                }
            }
        }
        Session::flash('success', 'Record Updated Successful.');
        return redirect()->route('wadmin.order.checkout.view', $out_data['order_id']);
    }
    public function add_shipping_page($id)
    {
        $shipping_orders = SMSAorder::where('id', $id)->first();
        return view('W_admin.add_shipment', ['shipping_orders' => $shipping_orders]);
    }
    /*SMSA Shipping Order*/
    public function add_smsa_shipment(Request $request)
    {
        $smsa = SMSACredential::where('user_id',1)->first();
        $request->validate([
            'company_name' => 'required',
            'shipper_address' => 'required',
            'shipper_city' => 'required',
            'shipper_phone' => 'required | numeric',
            'shipper_country' => 'required',
            'consignee_name' => 'required',
            'consignee_country' => 'required',
            'consignee_city' => 'required',
            'consignee_phone' => 'required',
            'consignee_address' => 'required',
            'item_description' => 'required',
            'COD_amount' => 'required',
            'weight' => 'required',
            'boxes' => 'required',
        ]);
        $shipmentData = [
            'passKey' =>$smsa->passkey,
            'refNo' => Date('ymd'),
            'sentDate' =>  time(),
            'idNo' =>  '',
            'cName' => $request->consignee_name,
            'cntry' => $request->consignee_country,
            'cCity' => $request->consignee_city,
            'cZip' => '',
            'cPOBox' => '',
            'cMobile' => $request->consignee_phone,
            'cTel1' => '',
            'cTel2' => '',
            'cAddr1' => $request->consignee_address,
            'cAddr2' => '',
            'shipType' => 'DLV',
            'PCs' => $request->boxes,
            'cEmail' => '',
            'carrValue' => '',
            'carrCurr' => '',
            'codAmt' => $request->COD_amount,
            'weight' => $request->weight,
            'custVal' => ($request->customs) ? $request->customs : '',
            'custCurr' => $request->customs_currency,
            'insrAmt' => '',
            'insrCurr' => '',
            'itemDesc' => $request->item_description,
            'sName' => $request->company_name,
            'sContact' => $request->contact_name,
            'sAddr1' => $request->shipper_address,
            'sAddr2' => '',
            'sCity' => $request->shipper_city,
            'sPhone' => $request->shipper_phone,
            'sCntry' => $request->shipper_country,
            'prefDelvDate' => '',
            'gpsPoints' => '',
        ];

        //        $url = "https://track.smsaexpress.com/SecomRestWebApi/api/addship";
        //        $curl = curl_init();
        //        curl_setopt($curl, CURLOPT_POST, 1);
        //        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($shipmentData));
        //        curl_setopt($curl, CURLOPT_URL, $url);
        //        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        //            'Content-Type: application/json',
        //        ));
        //        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //        $result = curl_exec($curl);
        //        if(!$result){die("Connection Failure");}
        //        curl_close($curl);
        //        return $result;
        $status = Smsa::addShip($shipmentData);
        $awbNumber = $status->getAddShipResult();

        $datas = Orders::find($request->order_id);
        if (is_numeric($awbNumber)) {
            $smsa_data = SMSAorder::where('order_id', $request->order_id)->first();
            if (empty($data->AWB_no)) {
                $table_data = json_decode($smsa_data->data);
                $data = [
                    'refNo' => Date('ymd'),
                    'PCs' => $request->boxes,
                    'weight' => $request->weight,
                ];
                foreach ($table_data as $key => $old_data) {
                    $data[$key] = $old_data;
                }
                $smsa_data->AWB_no = $awbNumber;
                $smsa_data->data = json_encode($data);
                $smsa_data->update();

                $status = Smsa::getStatus($smsa_data->AWB_no, $smsa->passkey);
                $getstatus = $status->getGetStatusResult();

                $user = Auth::user()->id;
                $datas->receiver_wadmin = $user;
                $datas->delivery_status = $getstatus;
                $datas->update();

                Session::flash('success', 'Order Bill has been successfully Generated.');
                return view('W_admin.order_checkout', ['datas' => $datas]);
            } else {
                Session::flash('danger', 'Order Bill has already Generated.');
                return view('W_admin.order_checkout', ['datas' => $datas]);
            }
        } else {
            Session::flash('danger ', $awbNumber);
            return view('W_admin.order_checkout', ['datas' => $datas]);
        }
    }
    public function smsa_shipping_status()
    {
        $smsa = SMSACredential::where('user_id',1)->first();
        $shipmentData = [
            $smsa->passkey, '290336044980'
        ];
        $curl = curl_init();
        $url = "http://track.smsaexpress.com/secom/SMSAWebserviceIntl/getStatus";
        $url1 = sprintf("%s?%s", $url, http_build_query($shipmentData));
        curl_setopt($curl, CURLOPT_URL, $url1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        //        return $result;


        $status = Smsa::getStatus('290336044980', $smsa->passkey);
        $status = $status->getGetStatusResult();
    }
    public function cancel_smsa_shipment(Request $request)
    {
        $Loggeduser = Auth::user();
        if ($Loggeduser->shipping_from_us == 1) {
            $smsa =SMSACredential::where('user_id',1)->first();
        }
        else {
            $smsa =SMSACredential::where('user_id',4)->first();
        }
        $status = Smsa::cancelShipment($request->AWB_no,$smsa->passkey, 'no reason');
        $result = $status->getCancelShipmentResult();
        $explode_result = explode('::', $result);
        $datas = Orders::find($request->order_id);
        $user = Auth::user()->id;
        $datas->receiver_wadmin = $user;
        $datas->update();
        if ($explode_result[0] == "Failed" && $explode_result[1] == "Cannot cancel Shipment") {
            Session::flash('danger', 'Cannot cancel Shipment');
            return view('W_admin.order_checkout', ['datas' => $datas]);
        }
        Session::flash('success', 'Order Bill has been successfully Canceled.');
        return view('W_admin.order_checkout', ['datas' => $datas]);
    }
    public function send_order(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $update = Orders::find($id);
        $update->order_status = $request->status;
        $update->receiver_wadmin = $user_id;
        $update->update();
        Session::flash('success', 'Order has been successfully dispatched.');
        return back();
    }
    public function get_PDF($order_id)
    {
        $order = Orders::where('id', $order_id)->first();
        $user = User::find($order->user_id);
        if ($user->shipping_from_us == 1) {
            $smsa =SMSACredential::where('user_id',1)->first();
        }
        else {
            $smsa =SMSACredential::where('user_id',4)->first();
        }
        $smsa_order = SMSAorder::where('order_id', $order->id)->first();
        $seller = User::where('id', $order->user_id)->first();
        if (!empty($smsa_order->AWB_no) && $order->company_name == "SMSA" && $order->status == 'Packed') {
            $response = SMSA::getPDF($smsa_order->AWB_no, $SMSA->passkey);
            $pdfData = $response->getGetPDFResult();
            return view('pdf-view', compact('pdfData'));
        }
        // elseif ($seller->shipping_from_us == 0 && $order->company_name != "SMSA" && $order->status == 'Packed') {
        //     $data = [
        //         'Order' => $order,
        //     ];
        //     $pdf = PDF::loadView('SMSA_AWB_pdf', $data)->setPaper("A4", "portrait");
        //     return $pdf->stream('pdfview.pdf');
        // }
    }
    public function system_waybill($id)
    {

        $order = Orders::where('id', $id)->first();
        $data = [
            'Order' => $order,
        ];
        $pdf = PDF::loadView('SMSA_AWB_pdf', $data)->setPaper("A4", "portrait");
        return $pdf->stream('pdfview.pdf');
    }
    /*Refund Order*/
    public function woo_orders_refunded_index()
    {
        $query0 = [
            "refund_status" => "Return and Refund"
        ];
        $query1 = [
            "refund_status" => "Complete"
        ];
        $query2 = [
            "refund_status" => "return-approved"
        ];
        $query3 = [
            "refund_status" => "return-cancelled"
        ];
        $query4 = [
            "refund_status" => "Return Received"
        ];
        $query5 = [
            "refund_status" => "Refunded"
        ];
        $user = Auth::user()->id;
        $orders = Orders::where('receiver_wadmin', $user)->where($query1)->orwhere($query0)->orwhere($query2)->orwhere($query3)->orwhere($query4)->orwhere($query5)->get();
        return view('W_admin.refunded_orders', ['orders' => $orders]);
    }
    public function refunded_order_view($id)
    {
        $datas = Orders::find($id);
        return view('W_admin.refunded_orders_view', ['datas' => $datas]);
    }
    public function refunded_order_received(Request $request, $id)
    {
        $datas = Orders::find($id);
        $datas->refund_status = $request->status;
        $datas->update();
        $refund_order = new RefundOrder();
        $refund_order->order_id = $datas->id;
        $refund_order->save();
        Session::flash('success', 'Order has been Received Successfully.');
        return back();
    }
    /*Refund Order Stock In*/
    public function stock_in_view($id)
    {
        $user = Auth::user()->id;
        $lists = array();
        $warehouses0 = Warehouse::all();
        foreach ($warehouses0 as $warehouse) {
            $stock_in_list = Stockins_list::where('warehouse_id', $warehouse->id)->first();
            if (in_array($user, json_decode($warehouse->responsible)) && $stock_in_list) {
                $lists[] = $warehouse->id;
            }
        }
        $shelfs = Shelf::whereIn('warehouse_id', $lists)->get();
        return view('W_admin.stock_in_for_return', ['results' => $shelfs, 'order_id' => $id]);
    }
    public function stock_in_add(Request $request)
    {
        $request->validate([
            'shelf_code' => 'required',
            'order_id' => 'required'
        ]);
        $shelf = Shelf::find($request->shelf_code);
        if (empty($shelf)) {
            Session::flash('danger', 'Invalid Barcode.');
            return back();
        } else {
            $order = Orders::find($request->order_id);
            $j_products = json_decode($order->product);
            $stocks = array();
            foreach ($j_products as $j_product) {
                $stock_ins_list = Stockins_list::where('product_id', $j_product->p_id)->where('warehouse_id', $order->order_warehouse_id)->first();
                $stocks[] = $stock_ins_list;
            }
            Cookie::queue(Cookie::forget('stock_in'));

            return view('W_admin.stock_in_2_for_return', ['shelf' => $shelf, 'order' => $order, 'stocks' => $stocks, 'order_id' => $request->order_id]);
        }
    }
    public function get_stock_length(Request $request)
    {
        $check = Stockins_list::where('stock_ins_id', $request->id)->where('warehouse_id', $request->warehouse_id)->first();
        if ($check) {
            $final_stock = Final_Stock::where('stock_ins_id', $check->stock_ins_id)->where('warehouse_id', $check->warehouse_id)->first();
            if (!empty($final_stock)) {
                $old_data = Cookie::get('stock_in');
                if (empty($old_data)) {
                    $order = Orders::find($request->order_id);
                    $products = json_decode($order->product);
                    $refund_items = json_decode($order->refund_items);
                    foreach ($products as $product) {
                        foreach ($refund_items as $refund_item) {

                            if ($product->p_id == $check->product_id) {
                                if ($refund_item->p_qty > $request->old_qty) {
                                    return response()->json([
                                        'final_stock' => $refund_item->p_qty,
                                        'data' => $check,
                                    ]);
                                } else {
                                    return response()->json([
                                        'status' => "fail",
                                        'data' => $check,
                                        'errors' => ['Invalid Product Stock'],
                                    ]);
                                }
                            } else {
                                return response()->json([
                                    'status' => "fail",
                                    'errors' => ['Invalid Barcode'],
                                ]);
                            }
                        }
                    }
                } else {
                    $data = json_decode($old_data, true);
                    $remaining_stock = null;
                    foreach ($data as $product) {
                        if ($product['stockins_list_id'] == $check->id) {
                            $order = Orders::find($request->order_id);
                            $products = json_decode($order->product);
                            $refund_items = json_decode($order->refund_items);
                            foreach ($products as $product0) {
                                foreach ($refund_items as $refund_item) {
                                    if ($product0->p_id == $check->product_id) {
                                        $remaining_stock = $refund_item->p_qty - $product['quantity'];
                                    } else {
                                        return response()->json([
                                            'status' => "fail",
                                            'errors' => ['Invalid Barcode'],
                                        ]);
                                    }
                                }
                            }
                        } else {
                            $remaining_stock = $final_stock->stock;
                        }
                    }
                    return response()->json([
                        'final_stock' => $remaining_stock,
                        'data' => $check,
                    ]);
                }
            } else {
                return response()->json([
                    'final_stock' => 0,
                    'data' => 0,
                    'status' => "fail",
                    'errors' => ['Invalid Barcode'],
                ]);
            }
        } else {
            return response()->json([
                'final_stock' => 0,
                'data' => 0,
                'status' => "fail",
                'errors' => ['Invalid Barcode'],
            ]);
        }
    }
    public function shelf_stock_in(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => ['required'],
            'product_barcode' => ['required'],
            'stockins_list_id' => ['required'],
            'product_quantity' => ['required', 'numeric', 'min:1'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $barcode_id = $request->product_barcode;
            $stockins_list_id = $request->stockins_list_id;
            $quantity = $request->product_quantity;
            $order_id = $request->order_id;
            $check = StockIn::find($barcode_id);
            if (empty($check)) {
                return response()->json([
                    'status' => "fail",
                    'errors' => ['Invalid Barcode'],
                ]);
            } else {
                $old_data = Cookie::get('stock_in');
                $stockins_list = Stockins_list::find($stockins_list_id);
                $final_stock = Final_Stock::where('stock_ins_id', $stockins_list->stock_ins_id)->where('warehouse_id', $stockins_list->warehouse_id)->first();
                $order = Orders::find($order_id);
                $products = json_decode($order->product);
                $total_qty = null;
                if (!empty($old_data)) {
                    $json_datas = json_decode($old_data);
                    foreach ($json_datas as $json_data) {
                        if ($json_data->barcode_id == $barcode_id && $json_data->stockins_list_id == $stockins_list_id) {
                            foreach ($products as $product0) {
                                if ($product0->p_id == $stockins_list->product_id) {
                                    if ($product0->available_qty >= $json_data->quantity) {
                                        $total_qty = $json_data->quantity + $quantity;
                                    } else {
                                        $array[] = $json_data;
                                        return response()->json([
                                            'status' => "fail",
                                            'errors' => ['Invalid Product Stock'],
                                        ]);
                                    }
                                } else {
                                    return response()->json([
                                        'status' => "fail",
                                        'errors' => ['Invalid Product Stock'],
                                    ]);
                                }
                            }
                        }
                    }
                } else {
                    $total_qty = $quantity;
                }

                // if ($final_stock)
                // {
                //     if ($final_stock->stock == 0 || $final_stock->stock < $total_qty)
                //     {
                //         return response()->json([
                //             'status'=>"fail",
                //             'errors'=>['Invalid Product Stock'],
                //         ]);
                //     }
                //     else{
                //         if (empty($old_data))
                //         {
                //             $array_data[] = [
                //                 'barcode_id'=>$barcode_id,
                //                 'stockins_list_id'=>$stockins_list_id,
                //                 'quantity'=>$quantity,
                //             ];
                //             Cookie::queue('stock_in',json_encode($array_data));
                //             return response()->json([
                //                 'status'=>"success",
                //             ]);
                //         }
                //         else{
                //             $data = json_decode($old_data,true);
                //             $check = null;
                //             $key = null;
                //             foreach ($data as $key_value=>$product)
                //             {
                //                 if ($product['barcode_id'] == $barcode_id)
                //                 {
                //                     $check = true;
                //                     $key = $key_value;
                //                 }
                //             }
                //             if ($check == true)
                //             {
                //                 $sum = $data[$key]['quantity']+$quantity;
                //                 $data[$key]['quantity'] = $sum;
                //                 Cookie::queue('stock_in',json_encode($data));
                //                 return response()->json([
                //                     'status'=>"success",
                //                 ]);
                //             }
                //             else{
                //                 $array_data = [
                //                     'barcode_id'=>$barcode_id,
                //                     'stockins_list_id'=>$stockins_list_id,
                //                     'quantity'=>$quantity,
                //                 ];
                //                 array_push($data,$array_data);
                //                 Cookie::queue('stock_in',json_encode($data));
                //                 return response()->json([
                //                     'status'=>"success",
                //                 ]);
                //             }
                //         }
                //     }
                // }
                // else
                // {
                //     if ($stockins_list->stock < $total_qty)
                //     {
                //         return response()->json([
                //             'status'=>"fail",
                //             'errors'=>['Invalid Product Stock'],
                //         ]);
                //     }
                //     else{
                //         if (empty($old_data))
                //         {
                //             $array_data[] = [
                //                 'barcode_id'=>$barcode_id,
                //                 'stockins_list_id'=>$stockins_list_id,
                //                 'quantity'=>$quantity,
                //             ];
                //             Cookie::queue('stock_in',json_encode($array_data));
                //             return response()->json([
                //                 'status'=>"success",
                //             ]);
                //         }
                //         else{
                //             $data = json_decode($old_data,true);
                //             $check = null;
                //             $key = null;
                //             foreach ($data as $key_value=>$product)
                //             {
                //                 if ($product['barcode_id'] == $barcode_id)
                //                 {
                //                     $check = true;
                //                     $key = $key_value;
                //                 }
                //             }
                //             if ($check == true)
                //             {
                //                 $sum = $data[$key]['quantity']+$quantity;
                //                 $data[$key]['quantity'] = $sum;
                //                 Cookie::queue('stock_in',json_encode($data));
                //                 return response()->json([
                //                     'status'=>"success",
                //                 ]);
                //             }
                //             else{
                //                 $array_data = [
                //                     'barcode_id'=>$barcode_id,
                //                     'stockins_list_id'=>$stockins_list_id,
                //                     'quantity'=>$quantity,
                //                 ];
                //                 array_push($data,$array_data);
                //                 Cookie::queue('stock_in',json_encode($data));
                //                 return response()->json([
                //                     'status'=>"success",
                //                 ]);
                //             }
                //         }
                //     }
                // }

                $product_stock = 0;
                foreach ($products as $product0) {
                    if ($product0->p_id == $stockins_list->product_id) {
                        $product_stock = $product0->available_qty;
                    }
                }
                if ($product_stock < $total_qty) {
                    return response()->json([
                        'status' => "fail",
                        'errors' => ['Invalid Product Stock'],
                    ]);
                }
                if (empty($old_data)) {
                    $array_data[] = [
                        'barcode_id' => $barcode_id,
                        'stockins_list_id' => $stockins_list_id,
                        'quantity' => $quantity,
                        'order_id' => $order_id,
                    ];
                    Cookie::queue('stock_in', json_encode($array_data));
                    return response()->json([
                        'status' => "success",
                    ]);
                } else {
                    $data = json_decode($old_data, true);
                    $check = null;
                    $key = null;
                    foreach ($data as $key_value => $product) {
                        if ($product['barcode_id'] == $barcode_id) {
                            $check = true;
                            $key = $key_value;
                        }
                    }
                    if ($check == true) {
                        $sum = $data[$key]['quantity'] + $quantity;
                        $data[$key]['quantity'] = $sum;
                        Cookie::queue('stock_in', json_encode($data));
                        return response()->json([
                            'status' => "success",
                        ]);
                    } else {
                        $array_data = [
                            'barcode_id' => $barcode_id,
                            'stockins_list_id' => $stockins_list_id,
                            'quantity' => $quantity,
                            'order_id' => $order_id,
                        ];
                        array_push($data, $array_data);
                        Cookie::queue('stock_in', json_encode($data));
                        return response()->json([
                            'status' => "success",
                        ]);
                    }
                }
            }
        }
    }
    public function shelf_stockIn_data()
    {
        $cookie_stock_in = Cookie::get('stock_in');
        $stock_ins = json_decode($cookie_stock_in, true);
        $ins = array();
        if (!empty($stock_ins)) {
            foreach ($stock_ins as $product) {
                $fetch = StockIn::find($product['barcode_id']);
                $get_product = Product::find($fetch->product_id);
                $data['product'] = $get_product;
                $newproduct = array_merge($product, $data);
                array_push($ins, $newproduct);
            }
        }
        return response()->json([
            'stock_in' => $ins,
        ]);
    }

    public function shelf_stockIn_minus(Request $request)
    {
        $id = $request->id;
        $Cookie_ins_data = Cookie::get('stock_in');
        $ins_data = json_decode($Cookie_ins_data, true);
        $array = array();
        foreach ($ins_data as $in_data) {
            if ($in_data['barcode_id'] == $id) {
                if ($in_data['quantity'] != 0) {
                    $in_data['quantity'] = $in_data['quantity'] - 1;
                    $array[] = $in_data;
                }
            } else {
                $array[] = $in_data;
            }
        }
        Cookie::queue('stock_in', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }
    public function shelf_stockIn_plus(Request $request)
    {
        $id = $request->id;
        $Cookie_ins_data = Cookie::get('stock_in');
        $ins_data = json_decode($Cookie_ins_data, true);
        $array = array();
        foreach ($ins_data as $in_data) {
            if ($in_data['barcode_id'] == $id) {
                $stockins_list = Stockins_list::find($in_data['stockins_list_id']);
                $order = Orders::find($in_data['order_id']);
                $products = json_decode($order->product);
                foreach ($products as $product) {
                    if ($product->product_id == $stockins_list->product_id) {
                        if ($product->quantity > $in_data['quantity']) {
                            $out_data['quantity'] = $in_data['quantity'] + 1;
                            $array[] = $out_data;
                        } else {
                            $array[] = $in_data;
                            return response()->json([
                                'status' => "fail",
                                'errors' => ['Invalid Product Stock'],
                            ]);
                        }
                    } else {
                        $array[] = $in_data;
                        return response()->json([
                            'status' => "fail",
                            'errors' => ['Invalid Product Stock'],
                        ]);
                    }
                }
            } else {
                $array[] = $in_data;
            }
        }
        Cookie::queue('stock_in', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }

    public function shelf_stockIn_del(Request $request)
    {
        $id = $request->id;
        $Cookie_ins_data = Cookie::get('stock_in');
        $ins_data = json_decode($Cookie_ins_data, true);
        $array = array();
        foreach ($ins_data as $in_data) {
            if ($in_data['barcode_id'] != $id) {
                $array[] = $in_data;
            }
        }
        Cookie::queue('stock_in', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }
    public function shelf_stockIn_update($id)
    {
        $Cookie_ins_data = Cookie::get('stock_in');
        $ins_data = json_decode($Cookie_ins_data, true);

        if (!empty($ins_data)) {
            foreach ($ins_data as $in_data) {
                $stock_ins_list = Stockins_list::where('id', $in_data['stockins_list_id'])->first();
                $add_ = Final_Stock::where('stock_ins_id', $in_data['barcode_id'])->where('warehouse_id', $stock_ins_list->warehouse_id)->first();
                $order = Orders::find($in_data['order_id']);
                $refund_items = json_decode($order->refund_items);
                foreach ($refund_items as $refund_item) {
                    if ($add_) {
                        if (in_array($order->picked_status, ["DELIVERED", "PICKED UP"])) {
                            $add_->delivered_stock = $add_->delivered_stock - $refund_item->p_qty;
                            $add_->display = $add_->display + $refund_item->p_qty;
                            $add_->update();

                            $stock_ins_list->available = $refund_item->p_qty + $stock_ins_list->available;
                            $stock_ins_list->sold = $stock_ins_list->sold - $refund_item->p_qty;
                            $stock_ins_list->reserved = $stock_ins_list->stock - $stock_ins_list->available;
                            $stock_ins_list->update();
                        } else {
                            $add_->display = $add_->display + $refund_item->p_qty;
                            $add_->update();
                        }

                        $order->refund_stock_status = "True";
                        $order->update();
                    }
                    $check = Placement::where('shelf_id', $id)->where('stock_in_id', $in_data['barcode_id'])->first();
                    if (!empty($check)) {
                        $old_quantity = $check->quantity;
                        $new = $old_quantity + $refund_item->p_qty;
                        $check->quantity = $new;
                        $check->update();

                        $add0 = new Placement_list();
                        $add0->placement_id = $check->id;
                        $add0->user_id = Auth::user()->id;
                        $add0->shelf_id = $id;
                        $add0->stock_in_id = $in_data['barcode_id'];
                        $add0->quantity = $refund_item->p_qty;
                        $add0->type = "in";
                        $add0->save();
                    } else {
                        $add = new Placement();
                        $add->user_id = Auth::user()->id;
                        $add->shelf_id = $id;
                        $add->stock_in_id = $in_data['barcode_id'];
                        $add->warehouse_id = $stock_ins_list->warehouse_id;
                        $add->quantity = $refund_item->p_qty;
                        $add->save();

                        $add0 = new Placement_list();
                        $add0->placement_id = $add->id;
                        $add0->user_id = Auth::user()->id;
                        $add0->shelf_id = $id;
                        $add0->stock_in_id = $in_data['barcode_id'];
                        $add0->quantity = $refund_item->p_qty;
                        $add0->type = "in";
                        $add0->save();
                    }
                }
            }
        }
        Session::flash('success', 'Record Updated Successful.');
        return redirect()->route('wadmin.refunded.order.view', $ins_data[0]['order_id']);
    }
}
