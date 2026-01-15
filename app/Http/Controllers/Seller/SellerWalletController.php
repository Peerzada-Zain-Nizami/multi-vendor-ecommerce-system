<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Paypal;
use App\Models\Transactions;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Notifications\MyNotification;
use Illuminate\Support\Facades\Validator;
use Omnipay\Omnipay;
use App\Payment;
use Session;

class SellerWalletController extends Controller
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
        $usd_to_sar = DB::table('settings')->where('option_name', 'usd_to_sar')->first();

        return view('Seller.wallet', compact('balance', 'usd_to_sar'));
    }
    public function trans()
    {
        $id = Auth::user()->id;
        $transactions = Transactions::where('user_id', $id)->orderBy('id', 'desc')->get();
        $trs = json_decode(json_encode($transactions), true);
        return view('Seller.transactions', compact('trs'));
    }
    public function view_trans($id)
    {
        $user_id = Auth::user()->id;
        $record = Transactions::where('transaction_id', $id)->first();

        if ($record->user_id == $user_id) {
            $trs = json_decode(json_encode($record), true);
            return view('Seller.view_transaction', compact('trs'));
        } else {
            return redirect()->route('seller.dashboard');
        }
    }
    public function trans_get(Request $request)
    {
        $record = Transactions::find($request->id);

        return response()->json([
            'record' => $record,
        ]);
    }
    public function decrypt_value(Request $request)
    {
        $value =  Crypt::decrypt($request->val);

        return response()->json([
            'data' => $value,
        ]);
    }
    public function add_deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'radio' => 'required',
            'amount' => ['required', 'numeric'],
        ], [], [
            'radio' => 'Payment Method',
            'amount' => 'Amount',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $method = $request->radio;
            if ($method == "PayTabs") {
                $amount = $request->amount;
                $data = array(
                    "profile_id" => env('profile_id'),
                    "tran_type" => "sale",
                    "tran_class" => "ecom",
                    "cart_description" => "Description of the items/services",
                    "cart_id" => "Deposit",
                    "cart_currency" => "SAR",
                    "cart_amount" => $amount,
                    "return" => route('seller.paytabs.response')
                );
                $response = Http::withHeaders([
                    'authorization' => env('PAYTAB_KEY'),
                    'content-type' => 'application/json'
                ])->post('https://secure.paytabs.sa/payment/request', $data);

                $result = json_decode($response);
                $session = array('tran_ref' => $result->tran_ref);
                session()->put('deposit', $session);
                return redirect($result->redirect_url);
            }
            if ($method == "Paypal") {
                $my_amount = $request->amount;
                try {
                    $response = $this->gateway->purchase(array(
                        'amount' => $my_amount,
                        'currency' => env('PAYPAL_CURRENCY'),
                        'returnUrl' => route('seller.paypal.response'),
                        'cancelUrl' => route('seller.wallet'),
                    ))->send();

                    if ($response->isRedirect()) {
                        $response->redirect(); // this will automatically forward the customer
                    } else {
                        // not successful
                        return $response->getMessage();
                    }
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            }
            if ($method == "BankTransfar") {
                $request->validate([
                    'proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
                    'transaction_id' => ['required', 'alphanum']
                ]);
                $amount = (int) $request->amount;
                $ref_id = $request->transaction_id;
                $id = Auth::user()->id;
                $wallet = DB::table('wallets')->where('user_id', $id)->first();
                $old_balance = $wallet->balance;

                $new_amount = Crypt::encrypt($amount);
                $tr = null;
                $old_tr = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr)) {
                    $tr = $old_tr->transaction_id + 1;
                } else {
                    $tr = '20211000';
                }
                $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('proof')->extension();
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
                $request->file('proof')->move(public_path('uploads/deposit_proof'), $file_name);

                $notification = [
                    'type' => 'Deposit Approval Request',
                    'trs_id' => $trs->transaction_id,
                ];
                $admin = User::where('role', 'SuperAdmin')->first();
                $admin->notify(new MyNotification($notification));

                // Session::flash('success', 'Bank Deposit has been successfully submit.');
                // return redirect()->route('seller.wallet');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Bank Deposit has been successfully submit.',
                ]);
            }
        }
    }
    public function update_deposit(Request $request)
    {
        if ($request->hasFile('myfile')) {
            $file = $request->file('myfile');

            $file_name = date('YmdHis') . rand(1, 10000) . "." . $file->extension();
            $trs = Transactions::where('transaction_id', $request->id)->first();

            $path = public_path('uploads/deposit_proof/' . $trs->attach);

            if (file_exists($path)) {
                unlink($path);
            }
            $wallet = DB::table('wallets')->where('user_id', $trs->user_id)->first();
            $old_balance = $wallet->balance;

            $trs->previous_balance = $old_balance;
            $trs->status = "Review";
            $trs->attach = $file_name;
            $trs->update();
            $file->move(public_path('uploads/deposit_proof'), $file_name);

            $notification = [
                'type' => 'Deposit Approval Request',
                'trs_id' => $trs->transaction_id,
            ];
            $admin = User::where('role', 'SuperAdmin')->first();
            $admin->notify(new MyNotification($notification));

            return response()->json([
                'status' => 200,
                'message' => 'Bank Deposit has been successfully submit.',
            ]);
        }
    }
    public function paytabs_response()
    {
        $value = session()->get('deposit');
        if (!empty($value)) {
            $ref_id = $value['tran_ref'];
            $data = array(
                "profile_id" => env('profile_id'),
                "tran_ref" => $ref_id
            );
            $response = Http::withHeaders([
                'authorization' => env('PAYTAB_KEY'),
                'content-type' => 'application/json'
            ])->post('https://secure.paytabs.sa/payment/query', $data);
            $result = json_decode($response);
            $verification = $result->payment_result->response_message;
            $amount = (int)$result->cart_amount;
            if ($verification == "Authorised") {
                $id = Auth::user()->id;
                $wallet = DB::table('wallets')->where('user_id', $id)->first();
                $old_balance = $wallet->balance;

                $new_amount =  Crypt::encrypt($amount);
                $tr = null;
                $old_tr = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr)) {
                    $tr = $old_tr->transaction_id + 1;
                } else {
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
                $new_balance = $balance + $amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id', $id)->update(['balance' => $final_balance]);
                session()->forget('deposit');
                Session::flash('success', 'Payment has been successfully Deposit.');
                return redirect()->route('seller.wallet');
            } else {
                session()->forget('deposit');
                Session::flash('danger', $verification);
                return redirect()->route('seller.wallet');
            }
        } else {
            return redirect('/');
        }
    }
    public function paypal_response(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                // The customer has successfully paid.

                $result = $response->getData();
                $verification = $result['state'];
                $ref_id = $result['id'];
                $amount = (int)$result['transactions'][0]['amount']['total'];

                $usd_to_sar = DB::table('settings')->where('option_name', 'usd_to_sar')->first();
                $paypal_deposit_fees = DB::table('settings')->where('option_name', 'paypal_deposit_fees')->first();
                $rate = $usd_to_sar->option_value;
                $fees = $paypal_deposit_fees->option_value;
                $total_fees = $amount / 100 * $fees;
                $fees_cut = $amount - $total_fees;
                $recive_amount = $fees_cut * $rate;
                if ($verification == "approved") {
                    $id = Auth::user()->id;
                    $wallet = DB::table('wallets')->where('user_id', $id)->first();
                    $old_balance = $wallet->balance;

                    $new_amount =  Crypt::encrypt((int)$recive_amount);
                    $crypt_amount =  Crypt::encrypt($amount);
                    $tr = null;
                    $old_tr = Transactions::select('transaction_id')->latest()->first();
                    if (!empty($old_tr)) {
                        $tr = $old_tr->transaction_id + 1;
                    } else {
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
                    $new_balance = $balance + (int)$recive_amount;
                    $final_balance = Crypt::encrypt($new_balance);
                    DB::table('wallets')->where('user_id', $id)->update(['balance' => $final_balance]);
                    session()->forget('deposit');
                    Session::flash('success', 'Payment has been successfully Deposit.');
                    return redirect()->route('seller.wallet');
                } else {
                    Session::flash('danger', $verification);
                    return redirect()->route('seller.wallet');
                }
            } else {
                Session::flash('danger', 'Transaction Failed');
                return redirect()->route('seller.wallet');
            }
        } else {
            Session::flash('danger', 'Invalid URL');
            return redirect()->route('seller.wallet');
        }
    }
    public function add_transfar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transfar_amount' => ['required', 'numeric'],
            'email' => ['required', 'email'],
            'note' => ['required', 'min:10', 'max:256', 'string'],
        ], [], [
            'transfar_amount' => 'Transfer Amount',
            'email' => 'Email',
            'note' => 'Note',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $from = Auth::user()->id;
            $mymail = Auth::user()->email;
            $to = $request->email;
            $note = $request->note;
            $amount = $request->transfar_amount;
            $user = DB::table('users')->where('email', $to)->first();
            if ($to == $mymail) {
                // Session::flash('danger', 'Provided email is your own email.');
                // return redirect()->route('seller.wallet');
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Provided email is your own email.',
                ]);
            }
            if (empty($user)) {
                // Session::flash('danger', 'Provided email is not found.');
                // return redirect()->route('seller.wallet');
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Provided email is not found.',
                ]);
            }
            // for deduct
            $wallet = DB::table('wallets')->where('user_id', $from)->first();
            $old_balance = $wallet->balance;
            $balance = Crypt::decrypt($old_balance);
            if ($amount > $balance) {
                // Session::flash('danger', 'Not Enough Balance');
                // return redirect()->route('seller.wallet');
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Not Enough Balance.',
                ]);
            }
            if ($amount <= 0) {
                // Session::flash('danger', 'Please put valid amount');
                // return redirect()->route('seller.wallet');
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Please put valid amount.',
                ]);
            }
            $new_balance = $balance - $amount;
            $final_balance = Crypt::encrypt($new_balance);
            DB::table('wallets')->where('user_id', $from)->update(['balance' => $final_balance]);

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
            $trs->user_id = $from;
            $trs->cash_out = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Transfered";
            $trs->status = "Completed";
            $trs->transfar_to = $to;
            $trs->note = $note;
            $trs->save();

            // for add
            $wallet = DB::table('wallets')->where('user_id', $user->id)->first();
            $old_balance = $wallet->balance;
            $balance = Crypt::decrypt($old_balance);
            $new_balance = $balance + $amount;
            $final_balance = Crypt::encrypt($new_balance);
            DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $final_balance]);

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
            $trs->user_id = $user->id;
            $trs->cash_in = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Transfered";
            $trs->status = "Completed";
            $trs->transfar_from = $mymail;
            $trs->note = $note;
            $trs->save();
            // Session::flash('success', 'Payment has been successfully Transfar.');
            // return redirect()->route('seller.wallet');

            return response()->json([
                'status' => 'success',
                'message' => 'Payment has been successfully Transfar.',
            ]);
        }
    }
    public function add_withdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdraw_method' => ['required', 'alpha'],
            'withdraw_amount' => ['required', 'numeric'],
            'withdraw_note' => ['required', 'min:10', 'max:256', 'string']
        ], [], [
            'withdraw_method' => 'Withdraw Method',
            'withdraw_amount' => 'Withdraw Amount',
            'withdraw_note' => 'Withdraw Note'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $method = $request->withdraw_method;
            if ($method == "Bank") {
                $request->validate([
                    'transfar_bank' => ['required'],
                ]);
                $user = Auth::user()->id;
                $amount = $request->withdraw_amount;
                $new_amount = Crypt::encrypt($amount);
                $wallet = DB::table('wallets')->where('user_id', $user)->first();
                $old_balance = $wallet->balance;
                $balance = Crypt::decrypt($old_balance);
                if ($balance >= $amount) {
                    $tr = null;
                    $old_tr = Transactions::select('transaction_id')->latest()->first();
                    if (!empty($old_tr)) {
                        $tr = $old_tr->transaction_id + 1;
                    } else {
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
                    // Session::flash('success', 'Withdraw Request has been successfully submit.');
                    // return redirect()->route('seller.wallet');
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Withdraw Request has been successfully submit.',
                    ]);
                } else {
                    // Session::flash('danger', 'Not Enough Balance or invalid amount.');
                    // return redirect()->route('seller.wallet');
                    return response()->json([
                        'status' => 'danger',
                        'message' => 'Not Enough Balance or invalid amount.',
                    ]);
                }
            }
            if ($method == "Paypal") {
                $request->validate([
                    'paypal_account' => ['required']
                ]);
                $user = Auth::user()->id;
                $amount = $request->withdraw_amount;
                $new_amount = Crypt::encrypt($amount);
                $wallet = DB::table('wallets')->where('user_id', $user)->first();
                $old_balance = $wallet->balance;
                $balance = Crypt::decrypt($old_balance);
                if ($balance >= $amount) {
                    $tr = null;
                    $old_tr = Transactions::select('transaction_id')->latest()->first();
                    if (!empty($old_tr)) {
                        $tr = $old_tr->transaction_id + 1;
                    } else {
                        $tr = '20211000';
                    }
                    $email = Paypal::find($request->paypal_account);
                    $sar_to_usd = DB::table('settings')->where('option_name', 'sar_to_usd')->first();
                    $paypal_withdraw_fee = DB::table('settings')->where('option_name', 'paypal_withdraw_fee')->first();
                    $rate = $sar_to_usd->option_value;
                    $fees = $paypal_withdraw_fee->option_value;
                    $total = $amount / $rate;
                    $total_fees = $total / 100 * $fees;
                    $recive_amount = $total - $total_fees;

                    $trs = new Transactions();
                    $trs->transaction_id = $tr;
                    $trs->user_id = $user;
                    $trs->cash_out = $new_amount;
                    $trs->previous_balance = $old_balance;
                    $trs->type = "Withdraw";
                    $trs->status = "Process";
                    $trs->note = $request->withdraw_note;
                    $trs->method = "PayPal";
                    $trs->withdraw_amount = Crypt::encrypt((int) $total);
                    $trs->fees = (int) $total_fees;
                    $trs->exchange_rate = $rate;
                    $trs->total_recive = Crypt::encrypt((int) $recive_amount);
                    $trs->paypal_email = $email->paypal_email;
                    $trs->save();
                    // Session::flash('success', 'Withdraw Request has been successfully submit.');
                    // return redirect()->route('seller.wallet');
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Withdraw Request has been successfully submit.',
                    ]);
                } else {
                    // Session::flash('danger', 'Not Enough Balance or invalid amount.');
                    // return redirect()->route('seller.wallet');
                    return response()->json([
                        'status' => 'danger',
                        'message' => 'Not Enough Balance or invalid amount.',
                    ]);
                }
            }
        }
    }
    public function bank_list()
    {
        $user = Auth::user()->id;
        $results = DB::table('banks')->where('user_id', $user)->get();
        return response()->json([
            'record' => $results,
        ]);
    }
    public function paypal_list()
    {
        $user = Auth::user()->id;
        $results = DB::table('paypals')->where('user_id', $user)->get();
        return response()->json([
            'record' => $results,
        ]);
    }
}
