<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $role = Auth::user()->role;
        $lang = Auth::user()->language;
        Session::put('applocal', $lang);

        if($role == "SuperAdmin"){
            return redirect()->route('admin.dashboard');
        }
        if($role == "Supplier"){
            return redirect()->route('supplier.dashboard');
        }
        if($role == "Seller"){
            return redirect()->route('seller.dashboard');
        }
        if($role == "Subadmin"){
            return redirect()->route('subadmin.dashboard');
        }
        if($role == "Warehouse Admin"){
            return redirect()->route('wadmin.dashboard');
        }
        return view('User.index');
    }
}
