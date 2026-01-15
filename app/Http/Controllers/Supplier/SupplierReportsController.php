<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\CompanyOrder;
use App\Models\CompanyReturn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierReportsController extends Controller
{
    public function sale_report(){

        $user = Auth::user()->id;
        $results = CompanyOrder::where(['status'=>'Complete','supplier_id'=>$user])->orderBy('id','desc')->get();
        $filter = 0;
        return view('Supplier.sale_report',compact('results','filter'));
    }
    public function sale_report_filter(Request $request)
    {
        $where = array();
        $user = Auth::user()->id;
        $supplier = $request->supplier;
        $createdby = $request->createdby;
        $payment = $request->payment;
        if (!empty($supplier))
        {
            $where[] = ['supplier_id', '=', $supplier];
        }
        if (!empty($createdby))
        {
            $where[] = ['user_id', '=',$createdby];
        }
        if (!empty($payment))
        {
            $where[] = ['payment', '=', $payment];
        }
        if ($request->date != null)
        {
            $date_seprate = explode('/',$request->date);
            $from = date('Y-m-d',strtotime(trim($date_seprate[0])));
            $to = date('Y-m-d',strtotime(trim($date_seprate[1])));
            $results = CompanyOrder::where('status','Complete')
                ->where($where)
                ->where('supplier_id',$user)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->orderBy('id','desc')
                ->get();
        }
        else{
            $results = CompanyOrder::where('status','Complete')
                ->where($where)
                ->where('supplier_id',$user)
                ->orderBy('id','desc')
                ->get();
        }
        session()->flashInput($request->input());
        $filter = 1;
        return view('Supplier.sale_report',compact('results','filter'));

    }
    public function return_report(){
        $user = Auth::user()->id;
        $results = CompanyReturn::where(['status'=>'Complete','supplier_id'=>$user])->orderBy('id','desc')->get();
        $filter = 0;
        return view('Supplier.return_reports',compact('results','filter'));
    }
    public function return_report_filter(Request $request)
    {
        $where = array();
        $user = Auth::user()->id;
        $supplier = $request->supplier;
        $createdby = $request->createdby;
        $payment = $request->payment;
        if (!empty($supplier))
        {
            $where[] = ['supplier_id', '=', $supplier];
        }
        if (!empty($createdby))
        {
            $where[] = ['user_id', '=',$createdby];
        }
        if (!empty($payment))
        {
            $where[] = ['payment', '=', $payment];
        }
        if ($request->date != null)
        {
            $date_seprate = explode('/',$request->date);
            $from = date('Y-m-d',strtotime(trim($date_seprate[0])));
            $to = date('Y-m-d',strtotime(trim($date_seprate[1])));
            $results = CompanyReturn::where('status','Complete')
                ->where($where)
                ->where('supplier_id',$user)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->orderBy('id','desc')
                ->get();
        }
        else{
            $results = CompanyReturn::where('status','Complete')
                ->where($where)
                ->where('supplier_id',$user)
                ->orderBy('id','desc')
                ->get();
        }
        session()->flashInput($request->input());
        $filter = 1;
        return view('Supplier.return_reports',compact('results','filter'));

    }
}
