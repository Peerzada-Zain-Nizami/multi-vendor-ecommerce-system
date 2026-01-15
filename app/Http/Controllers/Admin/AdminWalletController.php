<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Session;

class AdminWalletController extends Controller
{
    public function mywallet()
    {
        $id =  Auth::user()->id;
        $wallet = Wallet::where('user_id', $id)->first();
        $balance = Crypt::decrypt($wallet->balance);
        $subadmins = User::where('role',"Subadmin")->get();

        return view('Admin.wallet', compact('balance','subadmins'));
    }
    public function trans()
    {
        $id = Auth::user()->id;
        $transactions = Transactions::where('user_id',$id)->orderBy('id','desc')->get();
        $trs = json_decode(json_encode($transactions), true);
        return view('Admin.transactions',compact('trs'));
    }
    public function view_trans($id)
    {
        $user_id = Auth::user()->id;
        $record = Transactions::where('transaction_id',$id)->first();
        if ($record->user_id == $user_id)
        {
            $trs = json_decode(json_encode($record), true);
            return view('Admin.view_transaction',compact('trs'));
        }
        else{
            return redirect()->route('admin.dashboard');
        }
    }
    public function add_deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric'],
            'deposit_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            'deposit_note' => ['required', 'min:10', 'max:256', 'string']
        ], [], [
            'amount' => 'Amount',
            'deposit_proof' => 'Deposit Proof',
            'deposit_note' => 'Deposit Note',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('deposit_proof')->extension();
            $amount = (int) $request->amount;
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
            $trs = new Transactions();
            $trs->transaction_id = $tr;
            $trs->user_id = $id;
            $trs->cash_in = $new_amount;
            $trs->previous_balance = $old_balance;
            $trs->type = "Cash Deposit";
            $trs->status = "Complete";
            $trs->method = "Manual";
            $trs->attach = $file_name;
            $trs->save();
            $request->file('deposit_proof')->move(public_path('uploads/proof_slips'), $file_name);
            $balance = Crypt::decrypt($old_balance);
            $new_balance = $balance + $amount;
            $final_balance = Crypt::encrypt($new_balance);
            DB::table('wallets')->where('user_id', $id)->update(['balance' => $final_balance]);
            return response()->json([
                'status' => 'success',
                'message' => 'Payment Deposit has been successfully submit.'
            ]);
            // Session::flash('success', 'Payment Deposit has been successfully submit.');
            // return redirect()->route('admin.wallet');
        }
    }
    public function add_transfar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transfar_amount' => ['required', 'numeric'],
            'email' => ['required', 'email'],
            'transfar_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            'note' => ['required', 'min:10', 'max:256', 'string'],
        ], [], [
            'transfar_amount' => 'Transfar Amount',
            'email' => 'Email',
            'transfar_proof' => 'Transfar Proof',
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
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Provided email is your own email.'
                ]);
                // Session::flash('danger', 'Provided email is your own email.');
                // return redirect()->route('admin.wallet');
            }
            if (empty($user)) {
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Provided email is not found.'
                ]);
                // Session::flash('danger', 'Provided email is not found.');
                // return redirect()->route('admin.wallet');
            }
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('transfar_proof')->extension();
            // for deduct
            $wallet = DB::table('wallets')->where('user_id', $from)->first();
            $old_balance = $wallet->balance;
            $balance = Crypt::decrypt($old_balance);
            if ($amount > $balance) {
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Not Enough Balance'
                ]);
                // Session::flash('danger', 'Not Enough Balance');
                // return redirect()->route('admin.wallet');
            }
            if ($amount <= 0) {
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Please put valid amount'
                ]);
                // Session::flash('danger', 'Please put valid amount');
                // return redirect()->route('admin.wallet');
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
            $trs->attach = $file_name;
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
            $trs->attach = $file_name;
            $trs->note = $note;
            $trs->save();
            $request->file('transfar_proof')->move(public_path('uploads/proof_slips'), $file_name);
            return response()->json([
                'status' => 'success',
                'message' => 'Payment has been successfully Transfar.'
            ]);
            // Session::flash('success', 'Payment has been successfully Transfar.');
            // return redirect()->route('admin.wallet');

        }
    }
    public function add_withdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdraw_amount' => ['required', 'numeric'],
            'withdraw_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            'withdraw_note' => ['required', 'min:10', 'max:256', 'string']
        ], [], [
            'withdraw_amount' => 'Withdraw Amount',
            'withdraw_proof' => 'Withdraw Proof',
            'withdraw_note' => 'Withdraw Note',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $user = Auth::user()->id;
            $amount = $request->withdraw_amount;
            $new_amount = Crypt::encrypt($amount);
            $wallet = DB::table('wallets')->where('user_id', $user)->first();
            $old_balance = $wallet->balance;
            $balance = Crypt::decrypt($old_balance);
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('withdraw_proof')->extension();
            if ($balance >= $amount) {
                $tr = null;
                $old_tr = Transactions::select('transaction_id')->latest()->first();
                if (!empty($old_tr)) {
                    $tr = $old_tr->transaction_id + 1;
                } else {
                    $tr = '20211000';
                }
                $trs = new Transactions();
                $trs->transaction_id = $tr;
                $trs->user_id = $user;
                $trs->cash_out = $new_amount;
                $trs->previous_balance = $old_balance;
                $trs->type = "Withdraw";
                $trs->status = "Complete";
                $trs->method = "Manual";
                $trs->attach = $file_name;
                $trs->note = $request->withdraw_note;
                $trs->save();
                $request->file('withdraw_proof')->move(public_path('uploads/proof_slips'), $file_name);
                $balance = Crypt::decrypt($old_balance);
                $new_balance = $balance - $amount;
                $final_balance = Crypt::encrypt($new_balance);
                DB::table('wallets')->where('user_id', $user)->update(['balance' => $final_balance]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment Withdraw has been successful.'
                ]);
                // Session::flash('success', 'Payment Withdraw has been successful.');
                // return redirect()->route('admin.wallet');
            } else {
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Not Enough Balance or invalid amount.'
                ]);
                // Session::flash('danger', 'Not Enough Balance or invalid amount.');
                // return redirect()->route('admin.wallet');
            }
        }
    }
}
