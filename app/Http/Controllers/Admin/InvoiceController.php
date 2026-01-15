<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyCheckout;
use App\Models\CompanyOrder;
use App\Models\CompanyReturn;
use App\Models\Final_Stock;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockIn;
use App\Models\Stockins_list;
use App\Models\SupplierProduct;
use App\Models\Transactions;
use App\Models\User;
use App\Notifications\MyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\W_admin\PlacementController;
use App\Models\Orders;
use App\Models\Placement;
use App\Models\Placement_list;
use App\Models\Shelf;
use Session;

class InvoiceController extends Controller
{
    public function new_invoice()
    {
        $data = Product::all();
        return view('Admin.new_invoice',['results'=>$data]);
    }
    public function show_supplier(Request $request)
    {
        $product = $request->id;
        $data = SupplierProduct::with('suppliers_name')->where('product_id', $product)->get();
        return response()->json([
            'result' => $data,
        ]);
    }
    public function list_check(Request $request)
    {
        $where = ['supplier_id'=>$request->user,'product_id'=>$request->p_id,'status'=>"Checkout"];
        $check = CompanyCheckout::where($where)->first();
        if (!empty($check))
        {
            return response()->json([
                'status'=>400,
            ]);
        }
        else{
            return response()->json([
                'status'=>200,
            ]);
        }
    }
    public function add_list(Request $request)
    {
        $product = $request->id;
        $data = SupplierProduct::where('id', $product)->first();
        $max = 'max:'.$data->stock;
        $validator = Validator::make($request->all(), [
            'id'=> ['required','numeric'],
            'quantity'=> ['required','numeric','min:1',$max],
        ],[],['quantity'=>'Quantity']) ;
        if ($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $user = Auth::user()->id;
            $add = new CompanyCheckout();
            $add->user_id = $user;
            $add->supplier_id = $data->user_id;
            $add->s_product_id = $data->id;
            $add->product_id = $data->product_id;
            $add->quantity = $request->quantity;
            $add->status = "Checkout";
            $add->save();
            return response()->json([
                'status'=>200,
                'message'=>"Product Quantity added in your list.",
            ]);
        }
    }
    public function invoice_checkout()
    {
        $data = CompanyCheckout::with('suppliers_name','invoicer_name')->where('status',"Checkout")->orderBy('id', 'desc')->get()->groupBy('supplier_id');
        return view('Admin.invoice_checkout',['results'=>$data]);
    }
    public function invoice_checkout_view($id)
    {
        return view('Admin.checkout_view',['id'=>$id]);
    }
    public function invoice_checkout_get(Request $request)
    {
        $id = $request->id;
        $data = CompanyCheckout::where('supplier_id',$id)->where('status',"Checkout")->with('supplier_product','company_product')->get();
        return response()->json([
            'result' => $data,
        ]);
    }
    public function checkout_pro_del(Request $request)
    {
        $delete = CompanyCheckout::find($request->id);
        $delete->delete();
        return response()->json([
            'status' => 'success',
            'message' => "Invoice Deleted Successful.",
        ]);
    }
    public function checkout_pro_plus(Request $request)
    {
        $plus = CompanyCheckout::find($request->id);
        $check = SupplierProduct::find($plus->s_product_id);
        $old = $plus->quantity;
        $new = $old+1;
        if ($new <= $check->stock)
        {
            $plus->quantity = $new;
            $plus->update();
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => "Stock is End!",
            ]);
        }
    }
    public function checkout_pro_minus(Request $request)
    {
        $minus = CompanyCheckout::find($request->id);
        $old = $minus->quantity;
        $new = $old-1;
        $minus->quantity = $new;
        $minus->update();
    }
    public function create_order($id)
    {
        $data = CompanyCheckout::where('supplier_id',$id)->where('status',"Checkout")->with('supplier_product')->get();
        if ($data->isEmpty())
        {
            // Session::flash('danger', 'Invalid URL');
            // return redirect()->route('admin.invoice.checkout');
            return response()->json([
                'status' => 'danger',
                'message' => "Invalid URL",
            ]);


        }
        else{
            $orders = array();
            $for_total = array();
            $for_ship = array();
            foreach ($data as $p)
            {
                $order = array();
                $order['product_id'] = $p->product_id;
                $order['quantity'] = $p->quantity;
                $order['rate'] = $p->supplier_product[0]->selling_price;
                $order['shipping_charges'] = $p->supplier_product[0]->shipping_charges;
                $for_total[] = $p->quantity*$p->supplier_product[0]->selling_price;
                $for_ship[] = $p->quantity*$p->supplier_product[0]->shipping_charges;
                array_push($orders,$order);
                $checkout = CompanyCheckout::find($p->id);
                $checkout->status = "Orderd";
                $checkout->update();
            }
            $sub_total = array_sum($for_total);
            $fee = array_sum($for_ship);
            $total = $sub_total+$fee;
            $invoice_no = null;
            $old_invoice = CompanyOrder::latest()->first();
            if (!empty($old_invoice))
            {
                $invoice_no = $old_invoice->invoice_no+1;
            }else{
                $invoice_no = 20211115;
            }
            $user = Auth::user()->id;
            $add = new CompanyOrder();
            $add->invoice_no = $invoice_no;
            $add->user_id = $user;
            $add->supplier_id = $id;
            $add->original_order = json_encode($orders);
            $add->products = json_encode($orders);
            $add->sub_total = $sub_total;
            $add->shipping_fee = $fee;
            $add->total = $total;
            $add->status = "New Order";
            $add->payment = "Unpaid";
            $add->remaining = $total;
            $add->save();
            $notification = [
                'type'=> 'new_order',
                'invoice'=> $invoice_no,
            ];
            $supplier = User::find($id);
            $supplier->notify(new MyNotification($notification));
            // Session::flash('success', 'Order has been successfully submit.');
            // return redirect()->route('admin.invoice.checkout');
            return response()->json([
                'status' => 'success',
                'message' => "Order has been successfully submit.",
            ]);
        }
    }
    public function manage_invoice()
    {
        $data = CompanyOrder::with('suppliers_name')->orderBy('id', 'desc')->get();
        return view('Admin.manage_invoice',['results'=>$data]);
    }
    public function invoice_view($id)
    {
        $data = CompanyOrder::where('invoice_no',$id)->first();
        if (empty($data))
        {
            Session::flash('danger', 'Invalid Invoice No.');
            return back();
        }
        $data2 = CompanyReturn::where('invoice_no',$id)->orderBy('id','desc')->get();
        return view('Admin.invoice_view',['result'=>$data,'invoices'=>$data2]);
    }
    public function invoice_status(Request $request,$id)
    {
        $request->validate([
           'status' => 'required',
        ]);
        $update = CompanyOrder::find($id);
        $update->status = $request->status;
        $update->update();
        if ($request->status == "Complete")
        {
            $notification = [
                'type'=> 'complete_order',
                'invoice'=> $update->invoice_no,
            ];
            $supplier = User::find($update->supplier_id);
            $supplier->notify(new MyNotification($notification));
        }
        if ($request->status == "Onway")
        {
            $notification = [
                'type'=> 'Order Onway',
                'invoice'=> $update->invoice_no,
            ];
            $warehouses = User::where('role','Warehouse Admin')->get();
            foreach ($warehouses as $warehouse)
            {
                $warehouse->notify(new MyNotification($notification));
            }
        }
        if ($request->status == "Cancel")
        {
            $notification = [
                'type'=> 'cancel_order',
                'invoice'=> $update->invoice_no,
            ];
            $supplier = User::find($update->supplier_id);
            $supplier->notify(new MyNotification($notification));
        }
        Session::flash('success', 'Status has been successfully updated.');
        return redirect()->back();
    }
    public function invoice_pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=> ['required','numeric'],
            'option'=> ['required','string'],
            'proof'=> ['required','image','mimes:jpg,jpeg,png','max:1024'],
        ]);
        $validator2 = Validator::make($request->all(), [
            'amount' =>['required','numeric'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            if ($request->option == "Custom")
            {
                if ($validator2->fails())
                {
                    return response()->json([
                        'status'=>400,
                        'errors'=>$validator2->errors(),
                    ]);
                }
                else{
                    $invoice = CompanyOrder::find($request->id);
                    $amount = $request->amount;
                    $from = Auth::user()->id;
                    $user = User::find($invoice->supplier_id);
                    $to = $user->email;
                    $note = "This is Company Invoice Payment. Inovoice No is #".$invoice->invoice_no;
                    $file_name = date('YmdHis').rand(1,10000).".".$request->file('proof')->extension();
     // for admin
                    $wallet = DB::table('wallets')->where('user_id',$from)->first();
                    $old_balance = $wallet->balance;
                    $balance = Crypt::decrypt($old_balance);
                    if ($amount > $balance)
                    {
                        return response()->json([
                            'status'=>400,
                            'errors'=>["Not Enough Balance"],
                        ]);
                    }
                    if($amount <= 0)
                    {
                        return response()->json([
                            'status'=>400,
                            'errors'=>["Please put valid amount"],
                        ]);
                    }
                    if ($amount > $invoice->remaining)
                    {
                        return response()->json([
                            'status'=>400,
                            'errors'=>["This amount is greater than remaining amount."],
                        ]);
                    }
                    $new_balance = $balance-$amount;
                    $final_balance = Crypt::encrypt($new_balance);
                    DB::table('wallets')->where('user_id',$from)->update(['balance' => $final_balance]);
                    $new_amount =  Crypt::encrypt($amount);
                    $tr = null;
                    $old_tr = Transactions::select('transaction_id')->latest()->first();
                    if (!empty($old_tr))
                    {
                        $tr = $old_tr->transaction_id+1;
                    }
                    else{
                        $tr = '20211000';
                    }
                    $trs = new Transactions();
                    $trs->transaction_id = $tr;
                    $trs->user_id = $from;
                    $trs->cash_out = $new_amount;
                    $trs->previous_balance = $old_balance;
                    $trs->type = "Invoice Payment";
                    $trs->status = "Completed";
                    $trs->transfar_to = $to;
                    $trs->attach = $file_name;
                    $trs->note = $note;
                    $trs->save();

     // for supplier
                    $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
                    $old_balance = $wallet->balance;
                    $balance = Crypt::decrypt($old_balance);
                    $new_balance = $balance+$amount;
                    $final_balance = Crypt::encrypt($new_balance);
                    DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

                    $new_amount =  Crypt::encrypt($amount);
                    $tr1 = null;
                    $old_tr1 = Transactions::select('transaction_id')->latest()->first();
                    if (!empty($old_tr1))
                    {
                        $tr1 = $old_tr1->transaction_id+1;
                    }
                    else{
                        $tr1 = '20211000';
                    }
                    $trs = new Transactions();
                    $trs->transaction_id = $tr1;
                    $trs->user_id = $user->id;
                    $trs->cash_in = $new_amount;
                    $trs->previous_balance = $old_balance;
                    $trs->type = "Invoice Payment";
                    $trs->status = "Completed";
                    $trs->transfar_from = "Company";
                    $trs->attach = $file_name;
                    $trs->note = $note;
                    $trs->save();

                    $old_remaining = $invoice->remaining;
                    $total_rem = $old_remaining-$amount;
                    $status = null;
                    if ($total_rem == 0)
                    {
                        $status = "Paid";
                    }
                    else{
                        $status = "Pending";
                    }
                    $old_paid = $invoice->paid;
                    $invoice->payment = $status;
                    $invoice->remaining = $old_remaining-$amount;
                    $invoice->paid = $old_paid+$amount;
                    $invoice->update();
                    $request->file('proof')->move(public_path('uploads/proof_slips'),$file_name);
                    $notification = [
                        'type'=> 'invoice_custom_pay',
                        'trs'=> $tr1,
                    ];
                    $supplier = User::find($invoice->supplier_id);
                    $supplier->notify(new MyNotification($notification));
                    return response()->json([
                        'status'=>200,
                        'message'=>"Custom Payment has been successfully Paid.",
                    ]);
                }
            }
            else{
                $invoice = CompanyOrder::find($request->id);
                $amount = $invoice->remaining;
                $from = Auth::user()->id;
                $user = User::find($invoice->supplier_id);
                $to = $user->email;
                $note = "This is Company Invoice Payment. Inovoice No is #".$invoice->invoice_no;
                $file_name = date('YmdHis').rand(1,10000).".".$request->file('proof')->extension();
     // for admin
                $wallet = DB::table('wallets')->where('user_id',$from)->first();
                $old_balance = $wallet->balance;
                $balance = Crypt::decrypt($old_balance);
                if ($amount > $balance)
                {
                    return response()->json([
                        'status'=>400,
                        'errors'=>["Not Enough Balance"],
                    ]);
                }
                $new_balance = $balance-$amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id',$from)->update(['balance' => $final_balance]);
                $new_amount =  Crypt::encrypt($amount);
                $tr = null;
                $old_tr = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr))
                {
                    $tr = $old_tr->transaction_id+1;
                }
                else{
                    $tr = '20211000';
                }
                $trs = new Transactions();
                $trs->transaction_id = $tr;
                $trs->user_id = $from;
                $trs->cash_out = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->type = "Invoice Payment";
                $trs->status = "Completed";
                $trs->transfar_to = $to;
                $trs->attach = $file_name;
                $trs->note = $note;
                $trs->save();

     // for supplier
                $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
                $old_balance = $wallet->balance;
                $balance = Crypt::decrypt($old_balance);
                $new_balance = $balance+$amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

                $new_amount =  Crypt::encrypt($amount);
                $tr1 = null;
                $old_tr1 = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr1))
                {
                    $tr1 = $old_tr1->transaction_id+1;
                }
                else{
                    $tr1 = '20211000';
                }
                $trs = new Transactions();
                $trs->transaction_id = $tr1;
                $trs->user_id = $user->id;
                $trs->cash_in = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->transfar_from = "Company";
                $trs->type = "Invoice Payment";
                $trs->status = "Completed";
                $trs->attach = $file_name;
                $trs->note = $note;
                $trs->save();

                $old_paid = $invoice->paid;
                $invoice->payment = "Paid";
                $invoice->remaining = 0;
                $invoice->paid = $old_paid+$amount;
                $invoice->update();
                $request->file('proof')->move(public_path('uploads/proof_slips'),$file_name);
                $notification = [
                    'type'=> 'invoice_full_pay',
                    'trs'=> $tr1,
                ];
                $supplier = User::find($invoice->supplier_id);
                $supplier->notify(new MyNotification($notification));
                return response()->json([
                    'status'=>200,
                    'message'=>"Payment has been successfully Paid.",
                ]);
            }
        }
    }
    public function invoice_return()
    {
        $data = CompanyReturn::with('suppliers_name')->orderBy('id','desc')->get();
        return view('Admin.manage_invoice_return',['results'=>$data]);
    }
    public function invoice_return_partial($id)
    {
        $data = CompanyOrder::find($id);
        $stockins_list = Stockins_list::where('invoice_no',$data->invoice_no)->first();


        if ($data->return == false && $data->status == "Complete")
        {
            if ($stockins_list->reserved > 0) {
                Session::flash('danger', 'Available quantity for return : '.$stockins_list->available);
                return view('Admin.partial_return',['result'=>$data]);
            }
            else {
                return view('Admin.partial_return',['result'=>$data]);

            }
        }
        else{
            return back();
        }
    }
    public function invoice_return_full($id)
    {
        $data = CompanyOrder::find($id);
        if ($data->return == false && $data->status == "Complete")
        {
            return view('Admin.full_return',['result'=>$data]);
        }
        else{
            return back();
        }
    }
    public function partial_return(Request $request,$id)
    {
        $data = CompanyOrder::where('invoice_no',$id)->first();
        $stockins_list = Stockins_list::where('invoice_no',$id)->first();
        $products = json_decode($data->products);
        $quantity = $request->quantity;
        $i = 0;
        $new_update = array();
        $new_return = array();
        $error = array();
        foreach($products as $product)
        {
            $qt = $quantity[$i++];
            if ($qt > $stockins_list->available) {
                Session::flash('danger', 'Please return Available Quantity : '.$stockins_list->available);
                return back();
            }

            $p = (array)$product;
            $qty = array('return_quantity'=>$qt);
            $array =array_merge($p,$qty);
            array_push($new_return,$array);

            $old_qty = $product->quantity;
            $product->quantity = $old_qty-$qt;
            array_push($new_update,$product);
        }
        $for_total = array();
        foreach ($new_return as $p) {
            $for_total[] = $p['return_quantity'] * $p['rate'];
        }
        $total = array_sum($for_total);
        if ($total > 0) {

            foreach ($new_return as $product)
            {
                $skoclInId = StockIn::where('supplier_id',$data->supplier_id)->where('product_id',$product['product_id'])->value('id');
                $where = [
                    'invoice_no'=>$id,
                    'stock_ins_id'=>$skoclInId,
                    'product_id'=>$product['product_id'],
                ];
                $check = Stockins_list::where($where)->first();
                // if (!empty($check))
                // {

                //     $check1 = Final_Stock::where('stock_ins_id',$check->stock_ins_id)->where('warehouse_id',$check->warehouse_id)->first();
                //     if ($product['return_quantity'] > $check1->stock && $check1->display > 0) {

                //         Session::flash('danger', 'Some stock of this product is on the shelf.');
                //         return redirect()->route('admin.manage.invoice');
                //     }
                //     elseif ($check1->stock == 0 && $check1->display > 0) {

                //         Session::flash('danger', 'This product on shelf now.');
                //         return redirect()->route('admin.manage.invoice');
                //     }
                //     else {
                //         $check1->stock = $check1->stock-$product['return_quantity'];
                //         $check1->update();
                //     }

                //     $old_stock = $check->stock;
                //     $new_stock = $old_stock-$product['return_quantity'];
                //     $new_avail = $check->available-$product['return_quantity'];
                //     $check->stock = $new_stock;
                //     $check->available = $new_avail;
                //     $check->update();

                // }

                $status = '';
                if ($data->remaining >= $total)
                {
                    $status = "Return";
                }
                else{
                    $status = "Unpaid";
                }
                $data->return = "Partial";
                $data->products = json_encode($new_update);
                $data->update();

                // $supplier_stock = SupplierProduct::where(['user_id'=>$data->supplier_id,'product_id'=>$product['product_id']])->first();
                // $stock_old = $supplier_stock->stock;
                // $stock_new = $stock_old+$product['return_quantity'];
                // $supplier_stock->stock = $stock_new;
                // $supplier_stock->update();
            }
            $user = Auth::user()->id;
            $add = new CompanyReturn();
            $add->invoice_no = $id;
            $add->user_id = $user;
            $add->supplier_id = $data->supplier_id;
            $add->products = json_encode($new_return);
            $add->total = $total;
            $add->status = "New Return";
            $add->type = "Partial Return";
            $add->payment = $status;
            $add->remaining = $total;
            $add->save();
            $notification = [
                'type'=> 'new_partial_return',
                'invoice'=> $id,
                'link_id'=> $add->id,
            ];
            $supplier = User::find($data->supplier_id);
            $supplier->notify(new MyNotification($notification));
            Session::flash('success', 'Partial Return has been successfully submit.');
            return redirect()->route('admin.manage.invoice');
        }
        else{
            $msg = ["Please return at least one product."];
            array_push($error,$msg);
            return back()->with('errors',$error)->withInput();
        }

    }
    public function full_return($id)
    {
        $data = CompanyOrder::where('invoice_no',$id)->first();
        $products = json_decode($data->products);
        $new_update = array();
        $new_return = array();
        $error = array();
        foreach($products as $product)
        {
            $sold = CompanyOrder::sold($id,$data->supplier_id,$product->product_id);
                $p = (array)$product;
                $qty = array('return_quantity'=>$product->quantity-$sold);
                $array =array_merge($p,$qty);
                array_push($new_return,$array);

                $old_qty = $product->quantity;
                $product->quantity = $old_qty-$old_qty;
                array_push($new_update,$product);
        }
        $for_total = array();
        foreach ($new_return as $p) {
            $for_total[] = $p['return_quantity'] * $p['rate'];
        }
        $total = array_sum($for_total);
        if ($total > 0) {

            foreach ($new_return as $product)
            {
                $where = [
                    'invoice_no'=>$id,
                    'product_id'=>$product['product_id'],
                ];
                $check = Stockins_list::where($where)->first();
                // if (!empty($check))
                // {
                //     $check1 = Final_Stock::where('stock_ins_id',$check->stock_ins_id)->where('warehouse_id',$check->warehouse_id)->first();
                //     if ($check1->stock > 0 && $product['return_quantity'] > $check1->stock && $check1->display > 0) {

                //         Session::flash('danger', 'Some stock of this product is on the shelf.');
                //         return redirect()->route('admin.manage.invoice');
                //     }
                //     elseif ($check1->stock == 0 && $check1->display > 0) {

                //         Session::flash('danger', 'This product on shelf now.');
                //         return redirect()->route('admin.manage.invoice');
                //     }
                //     else {
                //         $check1->stock = $check1->stock-$product['return_quantity'];
                //         $check1->update();
                //     }

                //     $old_stock = $check->stock;
                //     $new_stock = $old_stock-$product['return_quantity'];
                //     $new_avail = $check->available-$product['return_quantity'];
                //     $check->stock = $new_stock;
                //     $check->available = $new_avail;
                //     $check->update();

                // }

                $status = '';
                if ($data->remaining >= $total)
                {
                    $status = "Return";
                }
                else{
                    $status = "Unpaid";
                }
                $data->return = "Full";
                $data->products = json_encode($new_update);
                $data->update();

                // $supplier_stock = SupplierProduct::where(['user_id'=>$data->supplier_id,'product_id'=>$product['product_id']])->first();
                // $stock_old = $supplier_stock->stock;
                // $stock_new = $stock_old+$product['return_quantity'];
                // $supplier_stock->stock = $stock_new;
                // $supplier_stock->update();
            }
            $user = Auth::user()->id;
            $add = new CompanyReturn();
            $add->invoice_no = $id;
            $add->user_id = $user;
            $add->supplier_id = $data->supplier_id;
            $add->products = json_encode($new_return);
            $add->total = $total;
            $add->status = "New Return";
            $add->type = "Full Return";
            $add->payment = $status;
            $add->remaining = $total;
            $add->save();
            $notification = [
                'type'=> 'new_full_return',
                'invoice'=> $id,
                'link_id'=> $add->id,
            ];
            $supplier = User::find($data->supplier_id);
            $supplier->notify(new MyNotification($notification));
            Session::flash('success', 'Full Return has been successfully submit.');
            return redirect()->route('admin.manage.invoice');
        }
        else{
            $msg = ["Please return at least one product."];
            array_push($error,$msg);
            return back()->with('errors',$error)->withInput();
        }

    }
    public function return_view($id)
    {
        $data = CompanyReturn::find($id);
        return view('Admin.return_view',['result'=>$data]);
    }
    public function invoice_return_status(Request $request,$id)
    {
        $request->validate([
            'status' => 'required',
        ]);
        if ($request->status == "Cancel")
        {
            $update = CompanyReturn::find($id);
            $products = json_decode($update->products);
            $data = CompanyOrder::where('invoice_no',$update->invoice_no)->first();
            $orders = json_decode($data->products);
            $new_update = array();
            $i = 0;
            foreach ($orders as $order)
            {
                $new_qty = $order->quantity+$products[$i++]->return_quantity;
                $order->quantity = $new_qty;
                array_push($new_update,$order);
            }
            $data->products = json_encode($new_update);
            $data->return = false;
            $data->update();
            foreach ($products as $product)
            {

                $where = [
                    'invoice_no'=>$id,
                    'supplier_id'=>$data->supplier_id,
                    'product_id'=>$product['product_id'],
                ];
                $check = StockIn::where($where)->first();
                if (!empty($check))
                {
                    $old_stock = $check->stock;
                    $new_stock = $old_stock+$product->return_quantity;
                    $check->stock = $new_stock;
                    $check->update();
                }
                $supplier_stock = SupplierProduct::where(['user_id'=>$update->supplier_id,'product_id'=>$product->product_id])->first();
                $stock_old = $supplier_stock->stock;
                $stock_new = $stock_old-$product->return_quantity;
                $supplier_stock->stock = $stock_new;
                $supplier_stock->update();
            }
            $update->status = $request->status;
            $update->update();
            $notification = [
                'type'=> 'return_cancel',
                'invoice'=> $update->invoice_no,
                'link_id'=> $id,
            ];
            $supplier = User::find($update->supplier_id);
            $supplier->notify(new MyNotification($notification));
            Session::flash('success', 'Status and Stock has been successfully updated.');
            return redirect()->back();
        }
        if ($request->status == "Reject")
        {
            $update = CompanyReturn::find($id);
            $products = json_decode($update->products);
            $data = CompanyOrder::where('invoice_no',$update->invoice_no)->first();
            $orders = json_decode($data->products);
            $new_update = array();
            $i = 0;
            foreach ($orders as $order)
            {
                $new_qty = $order->quantity+$products[$i++]->return_quantity;
                $order->quantity = $new_qty;
                array_push($new_update,$order);
            }
            $data->products = json_encode($new_update);
            $data->return = false;
            $data->update();
            foreach ($products as $product)
            {

                $where = [
                    'invoice_no'=>$id,
                    'supplier_id'=>$data->supplier_id,
                    'product_id'=>$product['product_id'],
                ];
                $check = StockIn::where($where)->first();
                if (!empty($check))
                {
                    $old_stock = $check->stock;
                    $new_stock = $old_stock+$product->return_quantity;
                    $check->stock = $new_stock;
                    $check->update();
                }
                $supplier_stock = SupplierProduct::where(['user_id'=>$update->supplier_id,'product_id'=>$product->product_id])->first();
                $stock_old = $supplier_stock->stock;
                $stock_new = $stock_old-$product->return_quantity;
                $supplier_stock->stock = $stock_new;
                $supplier_stock->update();
            }
            $update->status = $request->status;
            $update->update();
            if ($request->status == "Reject")
            {
                $notification = [
                    'type'=> 'return_accept',
                    'invoice'=> $update->invoice_no,
                    'link_id'=> $id,
                ];
                $supplier = User::find($update->supplier_id);
                $supplier->notify(new MyNotification($notification));
            }
            Session::flash('success', 'Status and Stock has been successfully updated.');
            return redirect()->back();
        }
        if ($request->status == "Complete")
        {
            $return = CompanyReturn::find($id);
            $order = CompanyOrder::where('invoice_no',$return->invoice_no)->first();
            $type = false;
            if ($return->type == "Full Return")
            {
                $type = "Full";
            }
            if ($order->remaining > 0)
            {
                $amount = $return->total;
                if ($order->remaining < $amount)
                {
                    $remaining_refund = $amount-$order->remaining;
                    /*payment less in supplier account*/
                    $wallet = DB::table('wallets')->where('user_id',$return->supplier_id)->first();
                    $old_balance = $wallet->balance;
                    $balance = Crypt::decrypt($old_balance);
                    $new_balance = $balance-$order->remaining-$remaining_refund;
                    $final_balance = Crypt::encrypt($new_balance);
                    DB::table('wallets')->where('user_id',$return->supplier_id)->update(['balance' => $final_balance]);

                    /*add return*/
                    $add = new CompanyReturn();
                    $add->invoice_no = $return->invoice_no;
                    $add->user_id = $return->user_id;
                    $add->supplier_id = $return->supplier_id;
                    $add->products = $return->products;
                    $add->total = $order->remaining;
                    $add->status = "Complete";
                    $add->type = $return->type;
                    $add->payment = "Return";
                    $add->remaining = 0;
                    $add->save();
                    /*less in order*/
                    $order->remaining = 0;
                    $order->payment = "Paid";
                    $order->return = $type;
                    $order->update();

                    /*less in return*/
                    $return->total = $remaining_refund;
                    $return->remaining = $remaining_refund;
                    $return->status = $request->status;
                    $return->update();

                    $notification = [
                        'type'=> 'return_complete',
                        'invoice'=> $return->invoice_no,
                        'link_id'=> $id,
                    ];
                    $supplier = User::find($return->supplier_id);
                    $supplier->notify(new MyNotification($notification));
                    $total_return = CompanyReturn::where('invoice_no',$order->invoice_no)->where('status',"Complete")->sum('total');
                    if ($total_return == $order->total)
                    {
                        $order->return = "Partial";
                        $order->save();
                    }
                    Session::flash('success', 'Status is Completed and stock is updated.');
                    return redirect()->back();
                }
                if ($order->remaining >= $amount)
                {
                    $remaining_paid = $order->remaining-$amount;
                    /*payment less in supplier account*/
                    $wallet = DB::table('wallets')->where('user_id',$return->supplier_id)->first();
                    $old_balance = $wallet->balance;
                    $balance = Crypt::decrypt($old_balance);
                    $new_balance = $balance-$amount;
                    $final_balance = Crypt::encrypt($new_balance);
                    DB::table('wallets')->where('user_id',$return->supplier_id)->update(['balance' => $final_balance]);

                    /*less in order*/
                    $status = null;
                    if ($remaining_paid == 0)
                    {
                        $status = "Paid";
                    }
                    else{
                        $status = "Pending";
                    }
                    $order->remaining = $remaining_paid;
                    $order->payment = $status;
                    $order->return = $type;
                    $order->update();

                    /*less in return*/
                    $return->remaining = 0;
                    $return->payment = "Return";
                    $return->status = $request->status;
                    $return->update();

                    $notification = [
                        'type'=> 'return_complete',
                        'invoice'=> $return->invoice_no,
                        'link_id'=> $id,
                    ];
                    $supplier = User::find($return->supplier_id);
                    $supplier->notify(new MyNotification($notification));
                    $total_return = CompanyReturn::where('invoice_no',$order->invoice_no)->where('status',"Complete")->sum('total');
                    if ($total_return == $order->total)
                    {
                        $order->return = "Partial";
                        $order->save();
                    }
                    Session::flash('success', 'Status is Completed and stock is updated.');
                    return redirect()->back();
                }
            }
            else {
                /*payment less in supplier account*/
                $amount = $return->total;
                $wallet = DB::table('wallets')->where('user_id', $return->supplier_id)->first();
                $old_balance = $wallet->balance;
                $balance = Crypt::decrypt($old_balance);
                $new_balance = $balance - $amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id', $return->supplier_id)->update(['balance' => $final_balance]);

                $new_amount = Crypt::encrypt($amount);
                $tr = null;
                $old_tr = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr)) {
                    $tr = $old_tr->transaction_id + 1;
                } else {
                    $tr = '20211000';
                }
                $trs = new Transactions();
                $trs->transaction_id = $tr;
                $trs->user_id = $return->supplier_id;
                $trs->cash_out = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->type = "Invoice Return Payment";
                $trs->status = "Completed";
                $trs->transfar_from = "Company";
                $trs->note = "You will Pay this amount from company for Invoice Return payment. Inovoice No is #" . $return->invoice_no;
                $trs->save();

                $order->return = $type;
                $order->update();

                $return->status = $request->status;
                $return->update();
                $notification = [
                    'type' => 'return_complete',
                    'invoice' => $return->invoice_no,
                    'link_id'=> $id,
                ];
                $supplier = User::find($return->supplier_id);
                $supplier->notify(new MyNotification($notification));
                $total_return = CompanyReturn::where('invoice_no',$order->invoice_no)->where('status',"Complete")->sum('total');
                if ($total_return == $order->total)
                {
                    $order->return = "Partial";
                    $order->save();
                }
                Session::flash('success', 'Status is Completed and stock is updated.');
                return redirect()->back();
            }
        }else{
        $update = CompanyReturn::find($id);
        $update->status = $request->status;
        $update->update();
        if ($request->status == "Resended")
        {
            $notification = [
                'type'=> 'return_resend',
                'invoice'=> $update->invoice_no,
                'link_id'=> $id,
            ];
            $supplier = User::find($update->supplier_id);
            $supplier->notify(new MyNotification($notification));
        }
        if ($request->status == "Process")
        {
            $notification = [
                'type'=> 'return_process',
                'invoice'=> $update->invoice_no,
                'link_id'=> $id,
            ];
            $supplier = User::find($update->supplier_id);
            $supplier->notify(new MyNotification($notification));
        }
        if ($request->status == "Onway")
        {
                $notification = [
                    'type'=> 'return_onway',
                    'invoice'=> $update->invoice_no,
                    'link_id'=> $id,
                ];
                $supplier = User::find($update->supplier_id);
                $supplier->notify(new MyNotification($notification));
        }
        Session::flash('success', 'Status has been successfully updated.');
        return redirect()->back();
        }
    }
    public function invoice_return_pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=> ['required','numeric'],
            'option'=> ['required','string'],
            'proof'=> ['required','image','mimes:jpg,jpeg,png','max:1024'],
        ]);
        $validator2 = Validator::make($request->all(), [
            'amount' =>['required','numeric'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            if ($request->option == "Custom")
            {
                if ($validator2->fails())
                {
                    return response()->json([
                        'status'=>400,
                        'errors'=>$validator2->errors(),
                    ]);
                }
                else{
                    $invoice = CompanyReturn::find($request->id);
                    $amount = $request->amount;
                    $from = Auth::user()->id;
                    $user = User::find($invoice->supplier_id);
                    $to = $user->email;
                    $note = "This is Company Invoice Return Payment. Inovoice No is #".$invoice->invoice_no;
                    $file_name = date('YmdHis').rand(1,10000).".".$request->file('proof')->extension();
     // for admin
                    $wallet = DB::table('wallets')->where('user_id',$from)->first();
                    $old_balance = $wallet->balance;
                    $balance = Crypt::decrypt($old_balance);
                    if($amount <= 0)
                    {
                        return response()->json([
                            'status'=>400,
                            'errors'=>["Please put valid amount"],
                        ]);
                    }
                    if ($amount > $invoice->remaining)
                    {
                        return response()->json([
                            'status'=>400,
                            'errors'=>["This amount is greater than remaining amount."],
                        ]);
                    }
                    $new_balance = $balance+$amount;
                    $final_balance = Crypt::encrypt($new_balance);
                    DB::table('wallets')->where('user_id',$from)->update(['balance' => $final_balance]);
                    $new_amount =  Crypt::encrypt($amount);
                    $tr = null;
                    $old_tr = Transactions::select('transaction_id')->latest()->first();
                    if (!empty($old_tr))
                    {
                        $tr = $old_tr->transaction_id+1;
                    }
                    else{
                        $tr = '20211000';
                    }
                    $trs = new Transactions();
                    $trs->transaction_id = $tr;
                    $trs->user_id = $from;
                    $trs->cash_in = $new_amount;
                    $trs->previous_balance = $old_balance;
                    $trs->type = "Invoice Return Payment";
                    $trs->status = "Completed";
                    $trs->transfar_to = $to;
                    $trs->attach = $file_name;
                    $trs->note = $note;
                    $trs->save();

     // for supplier
                    $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
                    $old_balance = $wallet->balance;
                    $balance = Crypt::decrypt($old_balance);
                    $new_balance = $balance+$amount;
                    $final_balance = Crypt::encrypt($new_balance);
                    DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

                    $new_amount =  Crypt::encrypt($amount);
                    $tr1 = null;
                    $old_tr1 = Transactions::select('transaction_id')->latest()->first();
                    if (!empty($old_tr1))
                    {
                        $tr1 = $old_tr1->transaction_id+1;
                    }
                    else{
                        $tr1 = '20211000';
                    }
                    $trs = new Transactions();
                    $trs->transaction_id = $tr1;
                    $trs->user_id = $user->id;
                    $trs->cash_in = $new_amount;
                    $trs->previous_balance = $old_balance;
                    $trs->type = "Invoice Return Payment";
                    $trs->status = "Completed";
                    $trs->transfar_from = "Company";
                    $trs->attach = $file_name;
                    $trs->note = $note;
                    $trs->save();

                    $old_remaining = $invoice->remaining;
                    $total_rem = $old_remaining-$amount;
                    $status = null;
                    if ($total_rem == 0)
                    {
                        $status = "Paid";
                    }
                    else{
                        $status = "Pending";
                    }
                    $old_paid = $invoice->paid;
                    $invoice->payment = $status;
                    $invoice->remaining = $old_remaining-$amount;
                    $invoice->update();
                    $request->file('proof')->move(public_path('uploads/proof_slips'),$file_name);
                    $notification = [
                        'type'=> 'return_custom_pay',
                        'trs'=> $tr1,
                    ];
                    $supplier = User::find($invoice->supplier_id);
                    $supplier->notify(new MyNotification($notification));
                    return response()->json([
                        'status'=>200,
                        'message'=>"Custom Payment has been successfully Paid.",
                    ]);
                }
            }
            else{
                $invoice = CompanyReturn::find($request->id);
                $amount = $invoice->remaining;
                $from = Auth::user()->id;
                $user = User::find($invoice->supplier_id);
                $to = $user->email;
                $note = "This is Company Invoice Return Payment. Inovoice No is #".$invoice->invoice_no;
                $file_name = date('YmdHis').rand(1,10000).".".$request->file('proof')->extension();
     // for admin
                $wallet = DB::table('wallets')->where('user_id',$from)->first();
                $old_balance = $wallet->balance;
                $balance = Crypt::decrypt($old_balance);

                $new_balance = $balance+$amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id',$from)->update(['balance' => $final_balance]);
                $new_amount =  Crypt::encrypt($amount);
                $tr = null;
                $old_tr = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr))
                {
                    $tr = $old_tr->transaction_id+1;
                }
                else{
                    $tr = '20211000';
                }
                $trs = new Transactions();
                $trs->transaction_id = $tr;
                $trs->user_id = $from;
                $trs->cash_in = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->type = "Invoice Return Payment";
                $trs->status = "Completed";
                $trs->transfar_to = $to;
                $trs->attach = $file_name;
                $trs->note = $note;
                $trs->save();

     // for supplier
                $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
                $old_balance = $wallet->balance;
                $balance = Crypt::decrypt($old_balance);
                $new_balance = $balance+$amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

                $new_amount =  Crypt::encrypt($amount);
                $tr1 = null;
                $old_tr1 = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr1))
                {
                    $tr1 = $old_tr1->transaction_id+1;
                }
                else{
                    $tr1 = '20211000';
                }
                $trs = new Transactions();
                $trs->transaction_id = $tr1;
                $trs->user_id = $user->id;
                $trs->cash_in = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->transfar_from = "Company";
                $trs->type = "Invoice Return Payment";
                $trs->status = "Completed";
                $trs->attach = $file_name;
                $trs->note = $note;
                $trs->save();

                $old_paid = $invoice->paid;
                $invoice->payment = "Paid";
                $invoice->remaining = 0;
                $invoice->update();
                $request->file('proof')->move(public_path('uploads/proof_slips'),$file_name);
                $notification = [
                    'type'=> 'return_full_pay',
                    'trs'=> $tr1,
                ];
                $supplier = User::find($invoice->supplier_id);
                $supplier->notify(new MyNotification($notification));
                return response()->json([
                    'status'=>200,
                    'message'=>"Payment has been successfully Paid.",
                ]);
            }
        }

    }
}
