<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\CompanyOrder;
use App\Models\CompanyReturn;
use App\Models\Stock;
use App\Models\SupplierProduct;
use App\Models\User;
use App\Notifications\MyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class Myorder extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $data = CompanyOrder::where('supplier_id',$user)->orderBy('id','desc')->get();
        return view('Supplier.my_orders',['results'=>$data]);
    }
    public function order_view($id)
    {
        $user = Auth::user()->id;
        $data = CompanyOrder::where('invoice_no',$id)->first();
        $data2 = CompanyReturn::where('invoice_no',$id)->orderBy('id','desc')->get();
        if ($data->supplier_id == $user)
        {
            return view('Supplier.order_view',['result'=>$data,'invoices'=>$data2]);
        }else{
            return redirect()->back();
        }
    }
    public function invoice_status(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ], [], [
            'status' => 'Status',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $update = CompanyOrder::find($id);
            $update->status = $request->status;
            $update->update();
            if ($request->status == "Accepted") {
                $notification = [
                    'type' => 'invoice_accepted',
                    'invoice' => $update->invoice_no,
                ];
                foreach (User::whereIn('role', ['SuperAdmin', 'Subadmin'])->get() as $admin) {
                    $admin->notify(new MyNotification($notification));
                }
            }
            if ($request->status == "Reject") {
                $notification = [
                    'type' => 'invoice_reject',
                    'invoice' => $update->invoice_no,
                ];
                foreach (User::whereIn('role', ['SuperAdmin', 'Subadmin'])->get() as $admin) {
                    $admin->notify(new MyNotification($notification));
                }
            }
            if ($request->status == "Process") {
                $notification = [
                    'type' => 'invoice_process',
                    'invoice' => $update->invoice_no,
                ];
                foreach (User::whereIn('role', ['SuperAdmin', 'Subadmin'])->get() as $admin) {
                    $admin->notify(new MyNotification($notification));
                }
            }
            if ($request->status == "Onway") {
                $notification = [
                    'type' => 'invoice_onway',
                    'invoice' => $update->invoice_no,
                ];
                foreach (User::whereIn('role', ['SuperAdmin', 'Subadmin', 'Warehouse Admin'])->get() as $admin) {
                    $admin->notify(new MyNotification($notification));
                }
            }
            // Session::flash('success', 'Status has been successfully updated.');
            // return redirect()->back();

            return response()->json([
                'status' => '200',
                'message' => 'Status has been successfully updated.'
            ]);
        }
    }
    public function mangage_return()
    {
        $user = Auth::user()->id;
        $data = CompanyReturn::where('supplier_id',$user)->orderBy('id','desc')->get();
        return view('Supplier.return',['results'=>$data]);
    }
    public function return_view($id)
    {
        $user = Auth::user()->id;
        $data = CompanyReturn::find($id);
        if ($data->supplier_id == $user)
        {
            return view('Supplier.return_view',['result'=>$data]);
        }else{
            return redirect()->back();
        }
    }
    public function invoice_return_status(Request $request,$id)
    {
        $request->validate([
            'status' => 'required',
        ]);
        $update = CompanyReturn::find($id);
        $update->status = $request->status;
        $update->update();
        if ($request->status == "Accepted")
        {
            $notification = [
                'type'=> 'return_accept',
                'invoice'=> $update->invoice_no,
                'link_id'=> $id,
            ];
            $warehouses = User::where('role','Warehouse Admin')->get();
            foreach ($warehouses as $warehouse)
            {
                $warehouse->notify(new MyNotification($notification));
            }
        }
        if ($request->status == "Received")
        {
            $notification = [
                'type'=> 'return_received',
                'invoice'=> $update->invoice_no,
                'link_id'=> $id,
            ];
            foreach (User::whereIn('role',['SuperAdmin','Subadmin'])->get() as $admin) {
                $admin->notify(new MyNotification($notification));
            }
        }
        if ($request->status == "Reject Request")
        {
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
