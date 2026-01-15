<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Paypal;
use App\Models\Transactions;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Omnipay\Omnipay;
use App\Payment;
use Session;

class SupplierWalletController extends Controller
{
    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }

    public function mywallet()
    {
        $id = Auth::user()->id;
        $wallet = Wallet::where('user_id', $id)->first();
        $balance = Crypt::decrypt($wallet->balance);
        $usd_to_sar = DB::table('settings')->where('option_name','usd_to_sar')->first();

        return view('Supplier.wallet', compact('balance','usd_to_sar'));
    }
    public function trans()
    {
        $id = Auth::user()->id;
        $transactions = Transactions::where('user_id',$id)->orderBy('id','desc')->get();
        $trs = json_decode(json_encode($transactions), true);
        return view('Supplier.transactions',compact('trs'));
    }
    public function view_trans($id)
    {
        $user_id = Auth::user()->id;
        $record = Transactions::where('transaction_id',$id)->first();

        if ($record->user_id == $user_id)
        {
            $trs = json_decode(json_encode($record), true);
            return view('Supplier.view_transaction',compact('trs'));
        }
    else{
            return redirect()->route('supplier.dashboard');
        }
    }
}
