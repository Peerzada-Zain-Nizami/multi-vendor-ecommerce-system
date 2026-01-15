<?php

namespace App\Http\Controllers\Admin;
use App\Models\CompanyOrder;

use App\Http\Controllers\Controller;
use App\Models\CompanyReturn;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function invoice_report(){

        $results = CompanyOrder::where('status','Complete')->get();
        $suppliers = User::where('role',"Supplier")->get();
        $createdby = User::whereIn('role',['Subadmin','SuperAdmin'])->get();
        $filter = 0;
        return view('Admin.invoice_reports',compact('results','suppliers','createdby','filter'));
    }
    public function invoice_report_filter(Request $request)
    {
        $where = array();
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
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->get();
        }
        else{
            $results = CompanyOrder::where('status','Complete')
                ->where($where)
                ->get();
        }
        $suppliers = User::where('role',"Supplier")->get();
        $createdby = User::whereIn('role',['Subadmin','SuperAdmin'])->get();
        session()->flashInput($request->input());
        $filter = 1;
        return view('Admin.invoice_reports',compact('results','suppliers','createdby','filter'));

    }
    public function return_report(){

        $results = CompanyReturn::where('status','Complete')->get();
        $suppliers = User::where('role',"Supplier")->get();
        $createdby = User::whereIn('role',['Subadmin','SuperAdmin'])->get();
        $filter = 0;
        return view('Admin.return_reports',compact('results','suppliers','createdby','filter'));
    }
    public function return_report_filter(Request $request)
    {
        $where = array();
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
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->get();
        }
        else{
            $results = CompanyReturn::where('status','Complete')
                ->where($where)
                ->get();
        }
        $suppliers = User::where('role',"Supplier")->get();
        $createdby = User::whereIn('role',['Subadmin','SuperAdmin'])->get();
        session()->flashInput($request->input());
        $filter = 1;
        return view('Admin.return_reports',compact('results','suppliers','createdby','filter'));

    }
}
