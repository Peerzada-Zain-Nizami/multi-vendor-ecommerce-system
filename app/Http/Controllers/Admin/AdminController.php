<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyOrder;
use App\Models\Orders;
use App\Models\Plan;
use App\Models\PlanSubscriber;
use App\Models\Transactions;
use App\Models\TrsReason;
use App\Models\SMSACredential;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\MyNotification;
use Illuminate\Support\Facades\Validator;
use Session;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $new_orders = Orders::where(['admin_id'=>$user,'status'=>'New Order'])->orwhere('status','Pending')->count();
        $completed = Orders::where(['admin_id'=>$user,'status'=>'Complete'])->count();
        $refund_completed = Orders::where(['admin_id'=>$user,'refund_status'=>'Complete'])->count();
        $cancel_reject = Orders::where(['admin_id'=>$user,'status'=>['Cancel','Reject']])->orwhere('refund_status','Complete')->count();
        $process = Orders::where('admin_id',$user)->whereNotIn('status',['New Order','Complete','Cancel','Cancelled','Reject','Order Returned'])->count();
        $results = Orders::where('admin_id',$user)->where('status','New Order')->orwhere('status','Pending')->orderBy('id','desc')->limit(10)->get();
        $companyorder = CompanyOrder::where('user_id',$user)->orderBy('id','desc')->limit(10)->get();

        return view('Admin.index',compact('refund_completed','companyorder','results','new_orders','completed','cancel_reject','process'));

    }
    public function usersindex()
    {
        $users = User::all();
        return view('Admin.users' ,['users'=> $users]);
    }
    public function subindex()
    {
        return view('Admin.addSubadmin');
    }
    public function Addsubadmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ], [], [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $data = new User();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);
            $data->role = 'Subadmin';
            $data->save();

            $id = User::select('id')->latest()->first();
            Wallet::create([
                'user_id' => $id->id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully inserted record!'
            ]);
        }
    }
    public function supplierindex()
    {
        return view('Admin.addSupplier');
    }
    public function addsupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ], [], [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $data = new User();

            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);
            $data->role = 'Supplier';
            $data->save();

            $id = User::select('id')->latest()->first();
            Wallet::create([
                'user_id' => $id->id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully inserted record!'
            ]);
        }
    }
    public function sellerindex()
    {
        return view('Admin.addSeller');
    }
    public function addseller(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ], [], [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $data = new User();

            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);
            $data->role = 'Seller';
            $data->save();

            $id = User::select('id')->latest()->first();
            Wallet::create([
                'user_id' => $id->id,
            ]);
            $plan = Plan::where('id', '1')->Orwhere('name', 'Free')->first();
            // dd($id->id,$plan->id);
            if ($plan) {
                PlanSubscriber::create([
                    'user_id' => $id->id,
                    'plan_id' => $plan->id,
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully inserted record!'
            ]);
        }
    }
    public function Wadminindex()
    {
        return view('Admin.add_Wadmin');
    }
    public function add_Wadmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ], [], [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $data = new User();

            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);
            $data->role = 'Supplier';
            $data->save();

            $id = User::select('id')->latest()->first();
            Wallet::create([
                'user_id' => $id->id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully inserted record!'
            ]);
        }
    }
    public function users_wallets()
    {
        $record = Wallet::join('users','users.id','=','wallets.user_id')
            ->get(['users.id','users.name','users.email','users.role','wallets.balance','wallets.created_at']);
        $data = json_decode(json_encode($record), true);
        return view('Admin.users_wallets', ['trs'=>$data]);
    }
    public function users_transaction()
    {
        $transactions = Transactions::join('users','users.id','=','transactions.user_id')->orderBy('transactions.id','desc')->get();
        $trs = json_decode(json_encode($transactions), true);
        return view('Admin.users_transactions',compact('trs'));
    }
    public function view_user_trans($id)
    {
        $record = Transactions::where('transaction_id',$id)->first();
        $trs = json_decode(json_encode($record), true);
        return view('Admin.view_transaction',compact('trs'));
    }
    public function reject_payment_request(Request $request)
    {
        $transaction = Transactions::find($request->id);
        $transaction->status = "Rejected";
        $transaction->update();

        $old_trs_reason = TrsReason::where('trs_id',$request->id)->first();
        if (!empty($old_trs_reason))
        {
            $old_trs_reason->reason = $request->reason;
            $old_trs_reason->update();
        }
        else{
            $trs_reason = new TrsReason();
            $trs_reason->trs_id = $request->id;
            $trs_reason->reason = $request->reason;
            $trs_reason->save();
        }

        $notification = [
            'type'=> 'Balance Request Rejected',
            'trs_id'=> $transaction->transaction_id,
        ];
        $seller = User::find($transaction->user_id);
        $seller->notify(new MyNotification($notification));

        return response()->json([
            'status' => 200,
            'message' => "Payment request has been successfully rejected.",
        ]);
    }
    public function approve_user_balance($id)
    {
        $admin = Auth::user();
        $transaction = Transactions::find($id);
        $amount = Crypt::decrypt($transaction->cash_in);
        $seller_wallet = DB::table('wallets')->where('user_id',$transaction->user_id)->first();

        $admin_wallet = DB::table('wallets')->where('user_id',$admin->id)->first();
        $admin_balance = Crypt::decrypt($admin_wallet->balance);
        if ($amount > $admin_balance)
        {
            Session::flash('danger', 'Not Enough Balance. Please Recharge your wallet!');
            return back();
        }
        $admin_new_balance = $admin_balance-$amount;
        $admin_final_balance = Crypt::encrypt($admin_new_balance);
        DB::table('wallets')->where('user_id',$admin->id)->update(['balance' => $admin_final_balance]);
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
        $trs->user_id = $admin->id;
        $trs->cash_out = $transaction->cash_in;
        $trs->previous_balance = $admin_wallet->balance;
        $trs->type = "Transfered";
        $trs->status = "Completed";
        $trs->transfar_to = $transaction->user_id;
        $trs->save();

        $transaction->status = "Completed";
        $transaction->deposit_amount = $transaction->cash_in;
        $transaction->update();

        $seller_balance = Crypt::decrypt($seller_wallet->balance);
        $seller_final_balance = Crypt::encrypt($seller_balance + $amount);
        DB::table('wallets')->where('user_id',$transaction->user_id)->update(['balance' => $seller_final_balance]);

        $notification = [
            'type'=> 'Balance Updated',
            'trs_id'=> $transaction->transaction_id,
        ];
        $seller = User::find($transaction->user_id);
        $seller->notify(new MyNotification($notification));

        Session::flash('success', 'Payment has been successfully Deposit.');
        return back();
    }
    public function user_view($id)
    {
        $data = User::find($id);
        return view('Admin.user_view',['result'=>$data]);
    }

}
