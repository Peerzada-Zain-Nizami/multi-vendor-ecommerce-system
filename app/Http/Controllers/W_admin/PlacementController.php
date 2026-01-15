<?php

namespace App\Http\Controllers\W_admin;

use App\Http\Controllers\Controller;
use App\Models\Final_Stock;
use App\Models\Placement;
use App\Models\Placement_list;
use App\Models\Product;
use App\Models\Rack;
use App\Models\Room_Block;
use App\Models\StockIn;
use App\Models\Stockins_list;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shelf;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Session;

class PlacementController extends Controller
{

    public function stock_in_view()
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
        return view('W_admin.stock_in', ['results' => $shelfs]);
    }

    public function stock_in_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shelf_code' => ['required']
        ], [], [
            'shelf_code' => 'Shelf Code',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $shelf = Shelf::find($request->shelf_code);
            if (empty($shelf)) {
                // Session::flash('danger', 'Invalid Barcode.');
                // return back();
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Invalid Barcode.'
                ]);
            } else {
                $stocks = Final_Stock::where('warehouse_id', $shelf->warehouse_id)->get();
                Cookie::queue(Cookie::forget('stock_in'));
                return view('W_admin.stock_in_2', ['shelf' => $shelf, 'stocks' => $stocks]);
            }
        }
    }
    public function get_stock_length(Request $request)
    {
        $check = Stockins_list::where('stock_ins_id', $request->id)->where('warehouse_id', $request->warehouse_id)->first();
        if ($check) {
            $final_stock = Final_Stock::where('stock_ins_id', $check->stock_ins_id)->where('warehouse_id', $request->warehouse_id)->first();
            if (!empty($final_stock)) {
                $old_data = Cookie::get('stock_in');
                if (empty($old_data)) {
                    return response()->json([
                        'final_stock' => $final_stock->stock,
                        'data' => $check,
                    ]);
                } else {
                    $data = json_decode($old_data, true);
                    $remaining_stock = null;
                    foreach ($data as $product) {
                        if ($product['stockins_list_id'] == $check->id) {
                            $remaining_stock = $final_stock->stock - $product['quantity'];
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
                $add = new Final_Stock();
                $add->stock_ins_id = $check->stock_ins_id;
                $add->stock = $check->stock;
                $add->display = 0;
                $add->selected_stock = 0;
                $add->save();

                $old_data = Cookie::get('stock_in');
                if (empty($old_data)) {
                    $add1 = Final_Stock::where('stock_ins_id', $check->stock_ins_id)->where('warehouse_id', $request->warehouse_id)->first();
                    if ($add1) {
                        return response()->json([
                            'final_stock' => $add1->stock,
                            'data' => $check,
                        ]);
                    }
                } else {
                    $data = json_decode($old_data, true);
                    $remaining_stock = null;
                    foreach ($data as $product) {
                        if ($product['stockins_list_id'] == $check->id) {
                            $remaining_stock = $final_stock->stock - $product['quantity'];
                        } else {
                            $remaining_stock = $final_stock->stock;
                        }
                    }
                    return response()->json([
                        'final_stock' => $remaining_stock,
                        'data' => $check,
                    ]);
                }
            }
        } else {
            return response()->json([
                'final_stock' => 0,
                'data' => 0,
            ]);
        }
    }
    public function shelf_stock_in(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
                $total_qty = null;
                if (!empty($old_data)) {
                    $json_datas = json_decode($old_data);
                    foreach ($json_datas as $json_data) {
                        if ($json_data->barcode_id == $barcode_id && $json_data->stockins_list_id == $stockins_list_id) {
                            $total_qty = $json_data->quantity + $quantity;
                        }
                    }
                } else {
                    $total_qty = $quantity;
                }
                if ($final_stock) {
                    if ($final_stock->stock == 0 || $final_stock->stock < $total_qty) {
                        return response()->json([
                            'status' => "fail",
                            'errors' => ['Invalid Product Stock'],
                        ]);
                    } else {
                        if (empty($old_data)) {
                            $array_data[] = [
                                'barcode_id' => $barcode_id,
                                'stockins_list_id' => $stockins_list_id,
                                'quantity' => $quantity,
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
                                ];
                                array_push($data, $array_data);
                                Cookie::queue('stock_in', json_encode($data));
                                return response()->json([
                                    'status' => "success",
                                ]);
                            }
                        }
                    }
                } else {
                    if ($stockins_list->stock < $total_qty) {
                        return response()->json([
                            'status' => "fail",
                            'errors' => ['Invalid Product Stock'],
                        ]);
                    } else {
                        if (empty($old_data)) {
                            $array_data[] = [
                                'barcode_id' => $barcode_id,
                                'stockins_list_id' => $stockins_list_id,
                                'quantity' => $quantity,
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
                $final_stock = Final_Stock::where('stock_ins_id', $stockins_list->stock_ins_id)->where('warehouse_id', $stockins_list->warehouse_id)->first();
                if ($final_stock) {
                    if ($final_stock->stock > $in_data['quantity']) {
                        $in_data['quantity'] = $in_data['quantity'] + 1;
                        $array[] = $in_data;
                    } else {
                        $array[] = $in_data;
                    }
                } else {
                    if ($stockins_list->stock > $in_data['quantity']) {
                        $in_data['quantity'] = $in_data['quantity'] + 1;
                        $array[] = $in_data;
                    } else {
                        $array[] = $in_data;
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
    public function shelf_stockIn_update($id)
    {
        $Cookie_ins_data = Cookie::get('stock_in');
        $ins_data = json_decode($Cookie_ins_data, true);
        if (!empty($ins_data)) {
            foreach ($ins_data as $in_data) {
                $stock_ins_list = Stockins_list::where('id', $in_data['stockins_list_id'])->first();
                $add_ = Final_Stock::where('stock_ins_id', $in_data['barcode_id'])->where('warehouse_id', $stock_ins_list->warehouse_id)->first();
                if ($add_) {
                    $add_->stock = $add_->stock - $in_data['quantity'];
                    $add_->display = $add_->display + $in_data['quantity'];
                    $add_->update();
                }
                $check = Placement::where('shelf_id', $id)->where('stock_in_id', $in_data['barcode_id'])->first();
                if (!empty($check)) {
                    $old_quantity = $check->quantity;
                    $new = $old_quantity + $in_data['quantity'];
                    $check->quantity = $new;
                    $check->update();

                    $add0 = new Placement_list();
                    $add0->placement_id = $check->id;
                    $add0->user_id = Auth::user()->id;
                    $add0->shelf_id = $id;
                    $add0->stock_in_id = $in_data['barcode_id'];
                    $add0->quantity = $in_data['quantity'];
                    $add0->type = "in";
                    $add0->save();
                } else {
                    $add = new Placement();
                    $add->user_id = Auth::user()->id;
                    $add->shelf_id = $id;
                    $add->stock_in_id = $in_data['barcode_id'];
                    $add->warehouse_id = $stock_ins_list->warehouse_id;
                    $add->quantity = $in_data['quantity'];
                    $add->save();

                    $add0 = new Placement_list();
                    $add0->placement_id = $add->id;
                    $add0->user_id = Auth::user()->id;
                    $add0->shelf_id = $id;
                    $add0->stock_in_id = $in_data['barcode_id'];
                    $add0->quantity = $in_data['quantity'];
                    $add0->type = "in";
                    $add0->save();
                }
            }
        }
        Session::flash('success', 'Record Updated Successful.');
        return redirect()->route('wadmin.stockIn.place.view');
    }

    public function stock_out_view()
    {
        $user = Auth::user()->id;
        $lists = array();
        $warehouses0 = Warehouse::all();
        foreach ($warehouses0 as $warehouse) {
            if (in_array($user, json_decode($warehouse->responsible))) {
                $lists[] = $warehouse->id;
            }
        }
        $shelfs = Shelf::whereIn('warehouse_id', $lists)->get();
        return view('W_admin.stock_out', ['results' => $shelfs]);
    }
    public function stock_out_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shelf_code' => ['required']
        ], [], [
            'shelf_code' => 'Shelf Code',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $shelf = Shelf::find($request->shelf_code);
            if (empty($shelf)) {
                // Session::flash('danger', 'Invalid Barcode.');
                // return back();
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Invalid Barcode.'
                ]);
            } else {
                $stocks = Final_Stock::where('warehouse_id', $shelf->warehouse_id)->get();
                $final_stock = array();
                foreach ($stocks as $stock) {
                    $placement = Placement::where('shelf_id', $request->shelf_code)->where('stock_in_id', $stock->stock_ins_id)->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
                    if ($placement != 0) {
                        $final_stock[] = [
                            'stock' => $stock,
                            'placement' => $placement,
                        ];
                    }
                }
                Cookie::queue(Cookie::forget('stock_out'));
                return view('W_admin.stock_out_2', ['shelf' => $shelf, 'stocks' => $final_stock]);
            }
        }
    }
    public function get_stock_out_length(Request $request)
    {
        $shelf = Shelf::find($request->shelf_id);
        $stock = Stockins_list::where('stock_ins_id', $request->id)->where('warehouse_id', $shelf->warehouse_id)->first();
        if ($stock) {
            $placement = Placement::where('shelf_id', $shelf->id)->where('stock_in_id', $stock->stock_ins_id)->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
            $final_stock = Final_Stock::where('stock_ins_id', $stock->stock_ins_id)->first();
            if (!empty($final_stock)) {
                $old_data = Cookie::get('stock_out');
                if (empty($old_data)) {
                    return response()->json([
                        'final_stock' => $placement,
                        'data' => $stock,
                    ]);
                } else {
                    $data = json_decode($old_data, true);
                    $remaining_stock = null;
                    foreach ($data as $product) {
                        if ($product['stockins_list_id'] == $stock->id) {
                            $remaining_stock = $placement - $product['quantity'];
                        } else {
                            $remaining_stock = $placement;
                        }
                    }
                    return response()->json([
                        'final_stock' => $remaining_stock,
                        'data' => $stock,
                    ]);
                }
            } else {
                return response()->json([
                    'final_stock' => 0,
                    'data' => 0,
                ]);
            }
        } else {
            return response()->json([
                'final_stock' => 0,
                'data' => 0,
            ]);
        }
    }
    public function shelf_stock_out(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_barcode' => ['required'],
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
                $total_qty = null;
                if (!empty($old_data)) {
                    $json_datas = json_decode($old_data);
                    foreach ($json_datas as $json_data) {
                        if ($json_data->barcode_id == $barcode_id && $json_data->stockins_list_id == $stockins_list_id && $json_data->shelf_id == $shelf_id && $json_data->stock_ins == $stock_ins) {
                            $total_qty = $json_data->quantity + $quantity;
                        }
                    }
                } else {
                    $total_qty = $quantity;
                }
                $stock_quantity = intval($placement);
                if ($stock_quantity == 0  || $stock_quantity < $total_qty) {
                    return response()->json([
                        'status' => "fail",
                        'errors' => ['Invalid Product Stock'],
                    ]);
                }
                if (empty($old_data)) {
                    $array_data[] = [
                        'barcode_id' => $barcode_id,
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
                $out_data['quantity'] = $out_data['quantity'] - 1;
                $array[] = $out_data;
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
                $shelf = Shelf::find($out_data['shelf_id']);
                $total_qty = null;
                $placement = Placement::where('shelf_id', $shelf->id)->where('stock_in_id', $out_data['stock_ins'])->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
                if ($placement) {
                    $stock_quantity = intval($placement);
                    if ($stock_quantity > $out_data['quantity']) {
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
                    $stockins_list = Stockins_list::find($out_data['stockins_list_id']);
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

                    $final_stock = Final_Stock::where('stock_ins_id', $out_data['barcode_id'])->where('warehouse_id', $check['warehouse_id'])->first();
                    $final_stock->display = $final_stock->display - $out_data['quantity'];
                    $final_stock->stock = $final_stock->stock + $out_data['quantity'];
                    $final_stock->update();

                    $add0 = new Placement_list();
                    $add0->placement_id = $check->id;
                    $add0->user_id = Auth::user()->id;
                    $add0->shelf_id = $id;
                    $add0->stock_in_id = $out_data['barcode_id'];
                    $add0->quantity = $out_data['quantity'];
                    $add0->type = "out";
                    $add0->save();
                } else {
                    $final_stock = Final_Stock::where('stock_ins_id', $out_data['barcode_id'])->where('warehouse_id', $check['warehouse_id'])->first();
                    $final_stock->display = $final_stock->display - $out_data['quantity'];
                    $final_stock->stock = $final_stock->stock + $out_data['quantity'];
                    $final_stock->update();

                    $add = new Placement();
                    $add->user_id = Auth::user()->id;
                    $add->shelf_id = $id;
                    $add->stock_in_id = $out_data['barcode_id'];
                    $add->quantity = $out_data['quantity'];
                    $add->save();

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
        return redirect()->route('wadmin.stockOut.place.view');
    }

    public function stock_move_view()
    {
        $user = Auth::user()->id;
        $lists = array();
        $warehouses0 = Warehouse::all();
        foreach ($warehouses0 as $warehouse) {
            if (in_array($user, json_decode($warehouse->responsible))) {
                $lists[] = $warehouse->id;
            }
        }
        $shelfs = Shelf::whereIn('warehouse_id', $lists)->get();
        return view('W_admin.stock_move', ['results' => $shelfs]);
    }
    public function stock_move_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shelf_code' => ['required']
        ], [], [
            'shelf_code' => 'Shelf Code',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $shelf = Shelf::find($request->shelf_code);
            if (empty($shelf)) {
                // Session::flash('danger', 'Invalid Barcode.');
                // return back();
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Invalid Barcode.'
                ]);
            } else {
                $stocks = Final_Stock::where('warehouse_id', $shelf->warehouse_id)->get();
                Cookie::queue(Cookie::forget('stock_move'));
                return view('W_admin.stock_move_2', ['shelf' => $shelf, 'stocks' => $stocks]);
            }
        }
    }
    public function get_stock_move_length(Request $request)
    {
        $shelf = Shelf::find($request->shelf_id);
        $stock = Stockins_list::where('stock_ins_id', $request->id)->where('warehouse_id', $shelf->warehouse_id)->first();
        if ($stock) {
            $placement = Placement::where('shelf_id', $shelf->id)->where('stock_in_id', $stock->stock_ins_id)->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
            $final_stock = Final_Stock::where('stock_ins_id', $stock->stock_ins_id)->first();
            if (!empty($final_stock)) {
                $old_data = Cookie::get('stock_move');
                if (empty($old_data)) {
                    return response()->json([
                        'final_stock' => $placement,
                        'data' => $stock,
                    ]);
                } else {
                    $data = json_decode($old_data, true);
                    $remaining_stock = null;
                    foreach ($data as $product) {
                        if ($product['stockins_list_id'] == $stock->id) {
                            $remaining_stock = $placement - $product['quantity'];
                        } else {
                            $remaining_stock = $placement;
                        }
                    }
                    return response()->json([
                        'final_stock' => $remaining_stock,
                        'data' => $stock,
                    ]);
                }
            } else {
                return response()->json([
                    'final_stock' => 0,
                    'data' => 0,
                ]);
            }
        } else {
            return response()->json([
                'final_stock' => 0,
                'data' => 0,
            ]);
        }
    }
    public function shelf_stock_move(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_barcode' => ['required'],
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
                $old_data = Cookie::get('stock_move');
                $shelf = Shelf::find($shelf_id);
                $placement = Placement::where('shelf_id', $shelf->id)->where('stock_in_id', $stock_ins)->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
                $total_qty = null;
                if (!empty($old_data)) {
                    $json_datas = json_decode($old_data);
                    foreach ($json_datas as $json_data) {
                        if ($json_data->barcode_id == $barcode_id && $json_data->stockins_list_id == $stockins_list_id && $json_data->shelf_id == $shelf_id && $json_data->stock_ins == $stock_ins) {
                            $total_qty = $json_data->quantity + $quantity;
                        }
                    }
                } else {
                    $total_qty = $quantity;
                }
                $stock_quantity = intval($placement);
                if ($stock_quantity == 0  || $stock_quantity < $total_qty) {
                    return response()->json([
                        'status' => "fail",
                        'errors' => ['Invalid Product Stock'],
                    ]);
                }
                if (empty($old_data)) {
                    $array_data[] = [
                        'barcode_id' => $barcode_id,
                        'shelf_id' => $shelf_id,
                        'stock_ins' => $stock_ins,
                        'stockins_list_id' => $stockins_list_id,
                        'quantity' => $quantity,
                    ];
                    Cookie::queue('stock_move', json_encode($array_data));
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
                        Cookie::queue('stock_move', json_encode($data));
                        return response()->json([
                            'status' => "success",
                        ]);
                    } else {
                        $array_data = [
                            'barcode_id' => $barcode_id,
                            'shelf_id' => $shelf_id,
                            'stock_ins' => $stock_ins,
                            'stockins_list_id' => $stockins_list_id,
                            'quantity' => $quantity,
                        ];
                        array_push($data, $array_data);
                        Cookie::queue('stock_move', json_encode($data));
                        return response()->json([
                            'status' => "success",
                        ]);
                    }
                }
            }
        }
    }
    public function shelf_stockmove_data()
    {
        $cookie_stock_move = Cookie::get('stock_move');
        $stock_moves = json_decode($cookie_stock_move, true);
        $moves = array();
        if (!empty($stock_moves)) {
            foreach ($stock_moves as $product0) {
                $fetch = StockIn::find($product0['barcode_id']);
                $get_product = Product::find($fetch->product_id);
                $data['product'] = $get_product;
                $newproduct = array_merge($product0, $data);
                array_push($moves, $newproduct);
            }
        }
        return response()->json([
            'stock_move' => $moves,
        ]);
    }
    public function shelf_stockmove_del(Request $request)
    {
        $id = $request->id;
        $Cookie_moves_data = Cookie::get('stock_move');
        $moves_data = json_decode($Cookie_moves_data, true);
        $array = array();
        foreach ($moves_data as $move_data) {
            if ($move_data['barcode_id'] != $id) {
                $array[] = $move_data;
            }
        }
        Cookie::queue('stock_move', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }
    public function shelf_stockmove_minus(Request $request)
    {
        $id = $request->id;
        $Cookie_moves_data = Cookie::get('stock_move');
        $moves_data = json_decode($Cookie_moves_data, true);
        $array = array();
        foreach ($moves_data as $move_data) {
            if ($move_data['barcode_id'] == $id) {
                if ($move_data['quantity'] != 0) {
                    $move_data['quantity'] = $move_data['quantity'] - 1;
                    $array[] = $move_data;
                }
            } else {
                $array[] = $move_data;
            }
        }
        Cookie::queue('stock_move', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }
    public function shelf_stockmove_plus(Request $request)
    {
        $id = $request->id;
        $Cookie_moves_data = Cookie::get('stock_move');
        $moves_data = json_decode($Cookie_moves_data, true);
        $array = array();
        foreach ($moves_data as $move_data) {
            if ($move_data['barcode_id'] == $id) {
                $shelf = Shelf::find($move_data['shelf_id']);
                $total_qty = null;
                $placement = Placement::where('shelf_id', $shelf->id)->where('stock_in_id', $move_data['stock_ins'])->where('warehouse_id', $shelf->warehouse_id)->sum('quantity');
                if ($placement) {
                    $stock_quantity = intval($placement);
                    if ($stock_quantity > $move_data['quantity']) {
                        $move_data['quantity'] = $move_data['quantity'] + 1;
                        $array[] = $move_data;
                    } else {
                        $array[] = $move_data;
                        return response()->json([
                            'status' => "fail",
                            'errors' => ['Invalid Product Stock'],
                        ]);
                    }
                } else {
                    $stockins_list = Stockins_list::find($move_data['stockins_list_id']);
                    if ($stockins_list->stock > $move_data['quantity']) {
                        $move_data['quantity'] = $move_data['quantity'] + 1;
                        $array[] = $move_data;
                    } else {
                        $array[] = $move_data;
                        return response()->json([
                            'status' => "fail",
                            'errors' => ['Invalid Product Stock'],
                        ]);
                    }
                }
            } else {
                $array[] = $move_data;
            }
        }
        Cookie::queue('stock_move', json_encode($array));
        return response()->json([
            'msg' => "success",
        ]);
    }
    public function shelf_stockmove_move($id, Request $request)
    {
        $old_shelf_id = $id;
        $shelf = Shelf::where('id', $old_shelf_id)->first();

        $shelfs = Shelf::whereNotIn('id', [$old_shelf_id, 0])->where('warehouse_id', $shelf->warehouse_id)->get();
        return view('W_admin.stock_move_to', ['shelf' => $old_shelf_id, 'results' => $shelfs]);
    }
    public function shelf_stockmove_update(Request $request)
    {
        $request->validate([
            'old_shelf_code' => 'required',
            'shelf_code' => 'required'
        ]);
        $old_shelf_id = $request->old_shelf_code;
        $new_shelf_id = $request->shelf_code;
        $shelf = Shelf::find($request->shelf_code);
        $old_shelf = Shelf::find($old_shelf_id);
        if (empty($shelf)) {
            Session::flash('danger', 'Invalid Barcode.');
            return back();
        } else {
            if ($shelf->warehouse_id != $old_shelf->warehouse_id) {
                Session::flash('danger', 'Invalid Barcode.');
                return back();
            } else {
                if ($old_shelf_id == $new_shelf_id) {
                    Session::flash('danger', 'Same Shelf Code.');
                    return back();
                } else {
                    $Cookie_moves_data = Cookie::get('stock_move');
                    $moves_data = json_decode($Cookie_moves_data, true);
                    if (!empty($moves_data)) {
                        foreach ($moves_data as $move_data) {
                            $old_check = Placement::where('shelf_id', $old_shelf->id)->where('stock_in_id', $move_data['barcode_id'])->first();
                            if (!empty($old_check)) {
                                $old_quantity = $old_check->quantity;
                                $new = $old_quantity - $move_data['quantity'];
                                $old_check->quantity = $new;
                                $old_check->update();

                                $add0 = new Placement_list();
                                $add0->placement_id = $old_check->id;
                                $add0->user_id = Auth::user()->id;
                                $add0->shelf_id = $old_shelf;
                                $add0->stock_in_id = $move_data['barcode_id'];
                                $add0->quantity = $move_data['quantity'];
                                $add0->type = "move";
                                $add0->save();
                            } else {
                                $add = new Placement();
                                $add->user_id = Auth::user()->id;
                                $add->shelf_id = $old_shelf;
                                $add->warehouse_id = $old_shelf->warehouse_id;
                                $add->stock_in_id = $move_data['barcode_id'];
                                $add->quantity = $move_data['quantity'];
                                $add->save();

                                $add0 = new Placement_list();
                                $add0->placement_id = $add->id;
                                $add0->user_id = Auth::user()->id;
                                $add0->shelf_id = $old_shelf;
                                $add0->stock_in_id = $move_data['barcode_id'];
                                $add0->quantity = $move_data['quantity'];
                                $add0->type = "move";
                                $add0->save();
                            }
                            $check = Placement::where('shelf_id', $new_shelf_id)->where('stock_in_id', $move_data['barcode_id'])->first();
                            if (!empty($check)) {
                                $old_quantity = $check->quantity;
                                $new = $old_quantity + $move_data['quantity'];
                                $check->quantity = $new;
                                $check->update();

                                $add0 = new Placement_list();
                                $add0->placement_id = $check->id;
                                $add0->user_id = Auth::user()->id;
                                $add0->shelf_id = $new_shelf_id;
                                $add0->stock_in_id = $move_data['barcode_id'];
                                $add0->quantity = $move_data['quantity'];
                                $add0->type = "move";
                                $add0->save();
                            } else {
                                $add = new Placement();
                                $add->user_id = Auth::user()->id;
                                $add->shelf_id = $new_shelf_id;
                                $add->warehouse_id = $shelf->warehouse_id;
                                $add->stock_in_id = $move_data['barcode_id'];
                                $add->quantity = $move_data['quantity'];
                                $add->save();

                                $add0 = new Placement_list();
                                $add0->placement_id = $add->id;
                                $add0->user_id = Auth::user()->id;
                                $add0->shelf_id = $new_shelf_id;
                                $add0->stock_in_id = $move_data['barcode_id'];
                                $add0->quantity = $move_data['quantity'];
                                $add0->type = "move";
                                $add0->save();
                            }
                        }
                    }
                    Session::flash('success', 'Record Updated Successful.');
                    return redirect()->route('wadmin.stockMove.place.view');
                }
            }
        }
    }

    public function shelf_stock_history()
    {

        $placements = Placement_list::with('user_get', 'shelf_get', 'stock_in_get')->orderBy('id', 'desc')->get();
        return view('W_admin.placement_history', compact('placements'));
    }

    /*Tracking Product*/
    public function product_place_tracking_page()
    {
        return view('W_admin.search_product');
    }
    public function product_place_tracking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_barcode' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $barcode_id = $request->product_barcode;
            $stockin = StockIn::find($barcode_id);

            $results = Placement::with('shelf_get', 'stock_in_get')->where('stock_in_id', $barcode_id)->get();
            if (empty($stockin)) {
                return response()->json([
                    'status' => "fail",
                    'errors' => ['Invalid Barcode'],
                ]);
            } else {
                $final_array = array();
                foreach ($results as $result) {
                    if ($result->quantity != 0) {
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
                    } else {
                        return response()->json([
                            'status' => "fail",
                            'errors' => ['Product Stock Not Available'],
                        ]);
                    }
                }
                return response()->json([
                    'status' => "success",
                    'data' => $final_array,
                ]);
            }
        }
    }
    /*Stock Placement On_Of*/
    public function on_off(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user->role == "Warehouse Admin") {
            if ($request->tab == "stock_in_check") {
                $user->stock_in_check = $request->value;
                $user->update();
            }
            if ($request->tab == "stock_out_check") {
                $user->stock_out_check = $request->value;
                $user->update();
            }
            if ($request->tab == "stock_move_check") {
                $user->stock_move_check = $request->value;
                $user->update();
            }
        }
    }
}
