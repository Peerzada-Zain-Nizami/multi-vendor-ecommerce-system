<?php

namespace App\Http\Controllers\W_admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyOrder;
use App\Models\CompanyReturn;
use App\Models\Final_Stock;
use App\Models\Stock;
use App\Models\StockIn;
use App\Models\Stockins_list;
use App\Models\SupplierProduct;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\MyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use Session;

class InvoiceController extends Controller
{
    public function company_invoice()
    {
        $data = CompanyOrder::with('suppliers_name')->orderBy('id', 'desc')->get();
        return  view('W_admin.company_invoice',['results'=>$data]);
    }
    public function invoice_view($id)
    {
        $user =Auth::user()->id;
        $lists = array();
        $warehouses0 = Warehouse::all();
        foreach ($warehouses0 as $warehouse)
        {
            if (in_array($user,json_decode($warehouse->responsible)))
            {
                $lists[] = $warehouse->id;
            }
        }
        $warehouses = Warehouse::whereIn('id',$lists)->get();
        $data = CompanyOrder::where('invoice_no',$id)->first();
        $data2 = CompanyReturn::where('invoice_no',$id)->orderBy('id','desc')->get();
        $data3 = Stockins_list::where('invoice_no',$id)->get();
        return view('W_admin.invoice_view',['result'=>$data,'invoices'=>$data2,'warehouses'=>$warehouses,'results'=>$data3]);
    }
    public function invoice_status(Request $request,$id)
    {
        $request->validate([
            'status' => 'required',
            'warehouse_id' => 'required',
        ]);
            $update = CompanyOrder::find($id);
            $update->status = $request->status;
            $update->receiver_admin = Auth::user()->id;
            $update->warehouse_id = $request->warehouse_id;
            $update->update();
            if ($request->status == "Received")
            {
                $update = CompanyOrder::find($id);
                $products = json_decode($update->products);
                foreach ($products as $product)
                {
                    $where = [
                        'supplier_id'=> $update->supplier_id,
                        'product_id'=> $product->product_id,
                    ];
                    $check = StockIn::where($where)->first();


                    if (!empty($check))
                    {
                        $check1 = Stockins_list::where('stock_ins_id',$check->id)->where('warehouse_id',$request->warehouse_id)->first();

                            if (!empty($check1))
                            {
                            $check2 = Final_Stock::where('stock_ins_id',$check1->stock_ins_id)->where('warehouse_id',$check1->warehouse_id)->first();
                            $check2->stock = $check2->stock + $product->quantity;
                            $check2->warehouse_id = $request->warehouse_id;
                            $check2->update();

                            $add = new Stockins_list();
                            $add->stock_ins_id = $check->id;
                            $add->invoice_no = $update->invoice_no;
                            $add->stock = $product->quantity;
                            $add->available = $product->quantity;
                            $add->product_id = $check->product_id;
                            $add->warehouse_id = $request->warehouse_id;
                            $add->save();
                        }
                        else{
                            $add = new Stockins_list();
                            $add->stock_ins_id = $check->id;
                            $add->invoice_no = $update->invoice_no;
                            $add->stock = $product->quantity;
                            $add->available = $product->quantity;
                            $add->product_id = $check->product_id;
                            $add->warehouse_id = $request->warehouse_id;
                            $add->save();

                            $new = new Final_Stock();
                            $new->stock_ins_id = $check->id;
                            $new->stock = $add->stock;
                            $new->display = 0;
                            $new->selected_stock = 0;
                            $new->warehouse_id = $request->warehouse_id;
                            $new->save();
                        }
                    }
                    else{
                        $add1 = new StockIn();
                        $add1->supplier_id = $update->supplier_id;
                        $add1->product_id = $product->product_id;
                        $add1->save();

                            $check1 = Stockins_list::where('stock_ins_id',$add1->id)->where('warehouse_id',$request->warehouse_id)->first();
                        if (!empty($check1))
                        {
                            $check2 = Final_Stock::where('stock_ins_id',$check1->stock_ins_id)->where('warehouse_id',$check1->warehouse_id)->first();
                            $check2->stock = $check2->stock + $product->quantity;
                            $check2->warehouse_id = $request->warehouse_id;
                            $check2->update();

                            $add = new Stockins_list();
                            $add->stock_ins_id = $add1->id;
                            $add->invoice_no = $update->invoice_no;
                            $add->stock = $product->quantity;
                            $add->available = $product->quantity;
                            $add->product_id = $add1->product_id;
                            $add->warehouse_id = $request->warehouse_id;
                            $add->save();
                        }
                        else{
                            $add = new Stockins_list();
                            $add->stock_ins_id = $add1->id;
                            $add->invoice_no = $update->invoice_no;
                            $add->stock = $product->quantity;
                            $add->available = $product->quantity;
                            $add->product_id = $add1->product_id;
                            $add->warehouse_id = $request->warehouse_id;
                            $add->save();

                            $new = new Final_Stock();
                            $new->stock_ins_id = $add1->id;
                            $new->stock = $add->stock;
                            $new->display = 0;
                            $new->selected_stock = 0;
                            $new->warehouse_id = $request->warehouse_id;
                            $new->save();
                        }

                    }
                    $supplier_stock = SupplierProduct::where(['user_id'=>$update->supplier_id,'product_id'=>$product->product_id])->first();
                    $stock_old = $supplier_stock->stock;
                    $stock_new = $stock_old-$product->quantity;
                    $supplier_stock->stock = $stock_new;
                    $supplier_stock->update();
                }
                /*payment add in supplier account*/
                $amount = $update->total;
                $wallet = DB::table('wallets')->where('user_id',$update->supplier_id)->first();
                $old_balance = $wallet->balance;
                $balance = Crypt::decrypt($old_balance);
                $new_balance = $balance+$amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id',$update->supplier_id)->update(['balance' => $final_balance]);

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
                $trs->user_id = $update->supplier_id;
                $trs->cash_in = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->type = "Invoice Payment";
                $trs->status = "Completed";
                $trs->transfar_from = "Company";
                $trs->note = "You will Receive this amount from company for Invoice payment. Inovoice No is #".$update->invoice_no ;
                $trs->save();

                $update->status = $request->status;
                $update->update();
                $notification = [
                    'type'=> 'received_order',
                    'invoice'=> $update->invoice_no,
                ];
                $supplier = User::find($update->supplier_id);
                $supplier->notify(new MyNotification($notification));
                foreach (User::whereIn('role',['SuperAdmin','Subadmin','Supplier'])->get() as $admin) {
                    $admin->notify(new MyNotification($notification));
                }
            }
            Session::flash('success', 'Status and Stock has been successfully updated.');
            return redirect()->back();
    }
    public function return_view($id)
    {
        $data = CompanyReturn::where('invoice_no',$id)->first();
        // $data = CompanyReturn::find($id);
        return view('W_admin.return_view',['result'=>$data]);
    }
    public function invoice_return()
    {
        $data = CompanyReturn::with('suppliers_name')->orderBy('id','desc')->get();
        return view('W_admin.manage_invoice_return',['results'=>$data]);
    }

    public function invoice_return_status(Request $request,$id)
    {
        $request->validate([
            'status' => 'required',
        ]);
        $update = CompanyReturn::find($id);
        $products = json_decode($update->products);
        foreach($products as $product)
        {
            if ($request->status == "Process") {
                $check = Stockins_list::where('invoice_no',$update->invoice_no)->first();
                if (!empty($check))
                {
                    $check1 = Final_Stock::where('stock_ins_id',$check->stock_ins_id)->where('warehouse_id',$check->warehouse_id)->first();
                    if ($check1->stock > 0 && $product->return_quantity > $check1->stock && $check1->display > 0) {
                        Session::flash('danger', 'Stock out first limited product on shelf!');
                        return redirect()->back();
                    }
                    elseif ($check1->stock == 0 && $check1->display > 0) {
                        Session::flash('danger', 'Product on shelf now. Stock out first. Act quickly!');
                        return redirect()->back();

                    }

                    else {

                        $check1->stock = $check1->stock-$product->return_quantity;
                        $check1->update();

                        $check->stock = $check->stock-$product->return_quantity;
                        $check->available = $check->available-$product->return_quantity;
                        $check->update();

                        $supplier_stock = SupplierProduct::where(['user_id'=>$update->supplier_id,'product_id'=>$product->product_id])->first();
                        $supplier_stock->stock = $supplier_stock->stock+$product->return_quantity;
                        $supplier_stock->update();

                        $update->status = $request->status;
                        $update->update();
                                $notification = [
                                    'type'=> 'return_process',
                                    'invoice'=> $update->invoice_no,
                                    'link_id'=> $id,
                                ];
                                foreach (User::whereIn('role',['SuperAdmin','Subadmin'])->get() as $admin) {
                                    $admin->notify(new MyNotification($notification));
                                }


                    }

                }

        }

        elseif ($request->status == "Onway") {

            $update->status = $request->status;
            $update->update();

            $notification = [
                'type'=> 'return_onway',
                'invoice'=> $update->invoice_no,
                'link_id'=> $id,
            ];
            $supplier = User::find($update->supplier_id);
            $supplier->notify(new MyNotification($notification));
        }
        elseif ($request->status  == "Cancel") {

            $update->status = $request->status;
            $update->update();

            $notification = [
                'type'=> 'return_reject',
                'invoice'=> $update->invoice_no,
                'link_id'=> $id,
            ];
            foreach (User::whereIn('role',['SuperAdmin','Subadmin'])->get() as $admin) {
                $admin->notify(new MyNotification($notification));


            }
        }

        Session::flash('success', 'Status has been successfully updated.');
        return redirect()->back();



    }

    }
}
