<?php

namespace App\Http\Controllers\W_admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
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
                /*$check = Stock::where('product_id',$product->product_id)->first();*/
                $where = [
                    'supplier_id'=> $update->supplier_id,
                    'product_id'=> $product->product_id,
                ];
                $check = StockIn::where($where)->first();
                if (!empty($check))
                {
                    $where1 = [
                        'warehouse_id'=> $request->warehouse_id,
                        'product_id'=> $product->product_id,
                    ];
                    $check1 = Stockins_list::where($where1)->first();
                    if (!empty($check1))
                    {
                        $check1->stock = $check1->stock + $product->quantity;
                        $check1->warehouse_id = $request->warehouse_id;
                        $check1->update();
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
                    }
                }
                else{
                    $add = new StockIn();
                    $add->supplier_id = $update->supplier_id;
                    $add->product_id = $product->product_id;
                    $add->save();

                    $where1 = [
                        'warehouse_id'=> $request->warehouse_id,
                        'product_id'=> $product->product_id,
                    ];
                    $check1 = Stockins_list::where($where1)->first();
                    if (!empty($check1))
                    {
                        $check1->stock = $check1->stock + $product->quantity;
                        $check1->warehouse_id = $request->warehouse_id;
                        $check1->update();
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
        $data = CompanyReturn::find($id);
        return view('W_admin.return_view',['result'=>$data]);
    }
    public function invoice_return()
    {
        $data = CompanyReturn::with('suppliers_name')->orderBy('id','desc')->get();
        return view('W_admin.manage_invoice_return',['results'=>$data]);
    }



    public function stock_by_warehouse()
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
        $results = Warehouse::whereIn('id',$lists)->get();
        $warehouses = Warehouse::whereIn('id',$lists)->get();
        $filter = 0;
        return view('W_admin.stock_by_warehouse',compact('results','warehouses','filter'));
    }
    public function stock_by_warehouse_filter(Request $request)
    {
        $results = Warehouse::where('id',$request->warehouse)->where('status','Active')->first();
        $warehouses = Warehouse::where('status','Active')->get();
        $filter = 1;
        session()->flashInput($request->input());
        return view('W_admin.stock_by_warehouse',compact('results','warehouses','filter'));
    }
}
