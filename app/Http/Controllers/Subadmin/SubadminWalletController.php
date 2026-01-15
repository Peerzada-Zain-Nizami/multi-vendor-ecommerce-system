<?php

namespace App\Http\Controllers\Subadmin;

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

class SubadminWalletController extends Controller
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

        return view('Subadmin.wallet', compact('balance','usd_to_sar'));
    }
    public function trans()
    {
        $id = Auth::user()->id;
        $transactions = Transactions::where('user_id',$id)->orderBy('id','desc')->get();
        $trs = json_decode(json_encode($transactions), true);
        return view('Subadmin.transactions',compact('trs'));
    }
    public function view_trans($id)
    {
        $user_id = Auth::user()->id;
        $record = Transactions::where('transaction_id',$id)->first();

        if ($record->user_id == $user_id)
        {
            $trs = json_decode(json_encode($record), true);
            return view('Subadmin.view_transaction',compact('trs'));
        }
    else{
            return redirect()->route('subadmin.dashboard');
        }
    }
    public function add_deposit(Request $request)
    {
        $request->validate([
            'radio' => ['required'],
            'amount' => ['required','numeric']
        ]);
        $method = $request->radio;
        if ($method == "PayTabs")
        {
            $amount = $request->amount;
            $data = array(
                "profile_id"=>env('profile_id'),
                "tran_type"=>"sale",
                "tran_class"=>"ecom",
                "cart_description"=>"Description of the items/services",
                "cart_id"=>"Deposit",
                "cart_currency"=>"SAR",
                "cart_amount"=>$amount,
                "return"=>"http://localhost:8000/Subadmin/paytabs_response"
            );
            $response = Http::withHeaders([
                'authorization' => env('PAYTAB_KEY'),
                'content-type' => 'application/json'
            ])->post('https://secure.paytabs.sa/payment/request', $data);

            $result = json_decode($response);
            $session = array('tran_ref'=>$result->tran_ref);
            session()->put('deposit', $session);
            return redirect($result->redirect_url);
        }
        if ($method == "Paypal")
        {
            $my_amount = $request->amount;
            try {
                $response = $this->gateway->purchase(array(
                    'amount' => $my_amount,
                    'currency' => env('PAYPAL_CURRENCY'),
                    'returnUrl' => route('subadmin.paypal.response'),
                    'cancelUrl' => route('subadmin.wallet'),
                ))->send();

                if ($response->isRedirect()) {
                    $response->redirect(); // this will automatically forward the customer
                } else {
                    // not successful
                    return $response->getMessage();
                }
            } catch(Exception $e) {
                return $e->getMessage();
            }

        }
        if($method == "BankTransfar")
        {
            $request->validate([
                'proof'=> ['required','image','mimes:jpg,jpeg,png','max:1024'],
                'transaction_id'=> ['required','alphanum']
            ]);
            $amount = (int)$request->amount;
            $ref_id = $request->transaction_id;
            $id = Auth::user()->id;
            $wallet = DB::table('wallets')->where('user_id',$id)->first();
            $old_balance = $wallet->balance;

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
            $file_name = date('YmdHis').rand(1,10000).".".$request->file('proof')->extension();
            $trs = new Transactions();
            $trs->transaction_id = $tr;
            $trs->user_id = $id;
            $trs->cash_in = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Cash Deposit";
            $trs->status = "Review";
            $trs->method = "Bank Deposit";
            $trs->method_trs_id = $ref_id;
            $trs->attach = $file_name;
            $trs->save();
            $request->file('proof')->move(public_path('uploads/deposit_proof'),$file_name);
            Session::flash('success', 'Bank Deposit has been successfully submit.');
            return redirect()->route('subadmin.wallet');
        }
    }
    public function paytabs_response()
    {
        $value = session()->get('deposit');
        if (!empty($value))
        {
            $ref_id = $value['tran_ref'];
            $data = array(
                "profile_id"=>env('profile_id'),
                "tran_ref"=> $ref_id);
            $response = Http::withHeaders([
                'authorization' => env('PAYTAB_KEY'),
                'content-type' => 'application/json'
            ])->post('https://secure.paytabs.sa/payment/query', $data);
            $result = json_decode($response);
            $verification = $result->payment_result->response_message;
            $amount = (int)$result->cart_amount;
            if ($verification == "Authorised") {
                $id = Auth::user()->id;
                $wallet = DB::table('wallets')->where('user_id',$id)->first();
                $old_balance = $wallet->balance;

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
                $trs->user_id = $id;
                $trs->cash_in = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->type = "Cash Deposit";
                $trs->status = "Completed";
                $trs->method = "PayTabs";
                $trs->method_trs_id = $ref_id;
                $trs->save();

                $balance = Crypt::decrypt($old_balance);
                $new_balance = $balance+$amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id',$id)->update(['balance' => $final_balance]);
                session()->forget('deposit');
                Session::flash('success', 'Payment has been successfully Deposit.');
                return redirect()->route('subadmin.wallet');
            }
            else{
                session()->forget('deposit');
                Session::flash('danger', $verification);
                return redirect()->route('subadmin.wallet');
            }
        }
        else{
            return redirect('/');
        }
    }
    public function paypal_response(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if ($request->input('paymentId') && $request->input('PayerID'))
        {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();

            if ($response->isSuccessful())
            {
                // The customer has successfully paid.

                $result = $response->getData();
                $verification = $result['state'];
                $ref_id = $result['id'];
                $amount = (int)$result['transactions'][0]['amount']['total'];

                $usd_to_sar = DB::table('settings')->where('option_name','usd_to_sar')->first();
                $paypal_deposit_fees = DB::table('settings')->where('option_name','paypal_deposit_fees')->first();
                $rate = $usd_to_sar->option_value;
                $fees = $paypal_deposit_fees->option_value;
                $total_fees = $amount/100*$fees;
                $fees_cut = $amount-$total_fees;
                $recive_amount = $fees_cut*$rate;
                if ($verification == "approved") {
                    $id = Auth::user()->id;
                    $wallet = DB::table('wallets')->where('user_id',$id)->first();
                    $old_balance = $wallet->balance;

                    $new_amount =  Crypt::encrypt((int)$recive_amount);
                    $crypt_amount =  Crypt::encrypt($amount);
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
                    $trs->user_id = $id;
                    $trs->cash_in = $new_amount;
                    $trs->previous_balance = $old_balance;
                    $trs->type = "Cash Deposit";
                    $trs->status = "Completed";
                    $trs->method = "PayPal";
                    $trs->method_trs_id = $ref_id;
                    $trs->deposit_amount = $crypt_amount;
                    $trs->fees = (int)$total_fees;
                    $trs->exchange_rate = $rate;
                    $trs->total_recive = (int)$recive_amount;
                    $trs->save();

                    $balance = Crypt::decrypt($old_balance);
                    $new_balance = $balance+(int)$recive_amount;
                    $final_balance = Crypt::encrypt($new_balance);
                    DB::table('wallets')->where('user_id',$id)->update(['balance' => $final_balance]);
                    session()->forget('deposit');
                    Session::flash('success', 'Payment has been successfully Deposit.');
                    return redirect()->route('subadmin.wallet');
                }
                else{
                    Session::flash('danger', $verification);
                    return redirect()->route('subadmin.wallet');
                }
            } else {
                Session::flash('danger', 'Transaction Failed');
                return redirect()->route('subadmin.wallet');
            }
        } else {
            Session::flash('danger', 'Invalid URL');
            return redirect()->route('subadmin.wallet');
        }
    }
    public function add_transfar(Request $request)
    {
        $request->validate([
            'transfar_amount' => ['required','numeric'],
            'email' => ['required','email'],
            'note' => ['required','min:10','max:256','string'],
        ]);
        $from = Auth::user()->id;
        $mymail = Auth::user()->email;
        $to = $request->email;
        $note = $request->note;
        $amount = $request->transfar_amount;
        $user = DB::table('users')->where('email',$to)->first();
        if ($to == $mymail)
        {
            Session::flash('danger', 'Provided email is your own email.');
            return redirect()->route('subadmin.wallet');
        }
        if (empty($user))
        {
            Session::flash('danger', 'Provided email is not found.');
            return redirect()->route('subadmin.wallet');
        }
// for deduct
        $wallet = DB::table('wallets')->where('user_id',$from)->first();
        $old_balance = $wallet->balance;
        $balance = Crypt::decrypt($old_balance);
        if ($amount > $balance)
        {
            Session::flash('danger', 'Not Enough Balance');
            return redirect()->route('subadmin.wallet');
        }
        if($amount <= 0)
        {
            Session::flash('danger', 'Please put valid amount');
            return redirect()->route('subadmin.wallet');
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
        $trs->type = "Transfered";
        $trs->status = "Completed";
        $trs->transfar_to = $to;
        $trs->note = $note;
        $trs->save();

// for add
        $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
        $old_balance = $wallet->balance;
        $balance = Crypt::decrypt($old_balance);
        $new_balance = $balance+$amount;
        $final_balance = Crypt::encrypt($new_balance);
        DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

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
        $trs->user_id = $user->id;
        $trs->cash_in = $new_amount;
        $trs->previous_balance = $old_balance;
        $trs->type = "Transfered";
        $trs->status = "Completed";
        $trs->transfar_from = $mymail;
        $trs->note = $note;
        $trs->save();
        Session::flash('success', 'Payment has been successfully Transfar.');
        return redirect()->route('subadmin.wallet');

    }
    public function add_withdraw(Request $request)
    {
        $request->validate([
            'withdraw_method'=> ['required','alpha'],
            'withdraw_amount'=> ['required','numeric'],
            'withdraw_note'=> ['required','min:10','max:256','string']
        ]);
        $method = $request->withdraw_method;
        if ($method == "Bank")
        {
            $request->validate([
                'transfar_bank'=> ['required'],
            ]);
            $user = Auth::user()->id;
            $amount = $request->withdraw_amount;
            $new_amount =  Crypt::encrypt($amount);
            $wallet = DB::table('wallets')->where('user_id',$user)->first();
            $old_balance = $wallet->balance;
            $balance = Crypt::decrypt($old_balance);
            if ($balance >= $amount)
            {
                $tr = null;
                $old_tr = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr))
                {
                    $tr = $old_tr->transaction_id+1;
                }
                else{
                    $tr = '20211000';
                }
                $bank = Bank::find($request->transfar_bank);
                $trs = new Transactions();
                $trs->transaction_id = $tr;
                $trs->user_id = $user;
                $trs->cash_out = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->type = "Withdraw";
                $trs->status = "Process";
                $trs->method = "Bank Transfar";
                $trs->note = $request->withdraw_note;
                $trs->bank_name = $bank->bank_name;
                $trs->account_name = $bank->account_name;
                $trs->iban_no = $bank->iban_no;
                $trs->save();
                Session::flash('success', 'Withdraw Request has been successfully submit.');
                return redirect()->route('subadmin.wallet');
            }else{
                Session::flash('danger', 'Not Enough Balance or invalid amount.');
                return redirect()->route('subadmin.wallet');
            }
        }
        if ($method == "Paypal")
        {
            $request->validate([
                'paypal_account'=> ['required']
            ]);
            $user = Auth::user()->id;
            $amount = $request->withdraw_amount;
            $new_amount =  Crypt::encrypt($amount);
            $wallet = DB::table('wallets')->where('user_id',$user)->first();
            $old_balance = $wallet->balance;
            $balance = Crypt::decrypt($old_balance);
            if ($balance >= $amount)
            {
                $tr = null;
                $old_tr = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr))
                {
                    $tr = $old_tr->transaction_id+1;
                }
                else{
                    $tr = '20211000';
                }
                $email = Paypal::find($request->paypal_account);
                $sar_to_usd = DB::table('settings')->where('option_name','sar_to_usd')->first();
                $paypal_withdraw_fee = DB::table('settings')->where('option_name','paypal_withdraw_fee')->first();
                $rate = $sar_to_usd->option_value;
                $fees = $paypal_withdraw_fee->option_value;
                $total = $amount/$rate;
                $total_fees = $total/100*$fees;
                $recive_amount = $total-$total_fees;

                $trs = new Transactions();
                $trs->transaction_id = $tr;
                $trs->user_id = $user;
                $trs->cash_out = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->type = "Withdraw";
                $trs->status = "Process";
                $trs->note = $request->withdraw_note;
                $trs->method = "PayPal";
                $trs->withdraw_amount = Crypt::encrypt((int)$total);
                $trs->fees = (int)$total_fees;
                $trs->exchange_rate = $rate;
                $trs->total_recive = Crypt::encrypt((int)$recive_amount);
                $trs->paypal_email = $email->paypal_email;
                $trs->save();
                Session::flash('success', 'Withdraw Request has been successfully submit.');
                return redirect()->route('subadmin.wallet');
            }else{
                Session::flash('danger', 'Not Enough Balance or invalid amount.');
                return redirect()->route('subadmin.wallet');
            }
        }
    }
    public function bank_list()
    {
        $user = Auth::user()->id;
        $results = DB::table('banks')->where('user_id',$user)->get();
        return response()->json([
            'record' => $results,
        ]);
    }
    public function paypal_list()
    {
        $user = Auth::user()->id;
        $results = DB::table('paypals')->where('user_id',$user)->get();
        return response()->json([
            'record' => $results,
        ]);
    }
}
