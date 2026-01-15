<?php

namespace App\Http\Middleware;

use App\Models\FreePlan;
use App\Models\Orders;
use App\Models\PlanPayment;
use App\Models\PlanSubscriber;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Wallet;
use App\Models\SellerApi;
use App\Notifications\MyNotification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Session;

class SellerPlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ldate = date('Y-m-d H:i:s');
        $today = date_create($ldate);
        $date = date_create($request->user()->created_at->format('Y-m-d H:i:s'));
        // Now find the difference in days.
        $difference = date_diff( $today,$date,true );
        $days = $difference->days;
        if ($days > 30)
        {
            $free_plan_check = FreePlan::where('user_id',$request->user()->id)->first();
            if ($free_plan_check == null)
            {
                $free_plan = new FreePlan();
                $free_plan->user_id = $request->user()->id;
                $free_plan->save();
            }
        }
        $plansubscriber = PlanSubscriber::where('user_id',$request->user()->id)->with('plan_get')->first();
        if($plansubscriber)
        {
            if ($plansubscriber->plan_get->name == "Free")
            {
                $ldate = date('Y-m-d H:i:s');
                $today = date_create($ldate);
                $date = date_create($plansubscriber->created_at->format('Y-m-d H:i:s'));
                // Now find the difference in days.
                $difference = date_diff( $today,$date,true );
                $days = $difference->days;
                if ($days > 30)
                {
                    $plansubscriber->delete();
                    return redirect(route('seller.plan.not.subscribe.index'));
                }
                else{
                    return $next($request);
                }
            }
            else{
                if ($plansubscriber->plan_type == "Monthly")
                {
                    $ldate = date('Y-m-d H:i:s');
                    $today = date_create($ldate);
                    $to = \Carbon\Carbon::parse($plansubscriber->updated_at);
                    $from = \Carbon\Carbon::parse($today);
                    // Now find the difference in months.
                    $months = $to->diffInMonths($from);
                    // Now find the difference in days.
                    $days = $to->diffInDays($from);
                    $month_days = cal_days_in_month(CAL_GREGORIAN,$to->format('m'),$to->format('y'));
                    $remaing_days = $days - $month_days;

                    if ($months > 1)
                    {
                        $plansubscriber->delete();
                        return redirect(route('seller.plan.not.subscribe.index'));
                    }
                    elseif ($months == 1)
                    {
                        // update payment
                        $plansubscriber->payment = "Unpaid";
                        $plansubscriber->update();

                        if (abs($remaing_days) > 30)
                        {
                            $plansubscriber->delete();
                            return redirect(route('seller.plan.not.subscribe.index'));
                        }
                        else{
                            $amount_data = json_decode($plansubscriber->plan_get['plan_price']);
                            $amount = $amount_data->Monthly;
                            $from = $request->user();
                            $user = User::where('role','SuperAdmin')->first();
                            // for seller
                            $wallet = DB::table('wallets')->where('user_id',$from->id)->first();
                            $old_balance = $wallet->balance;
                            $balance = Crypt::decrypt($old_balance);
                            if ($amount > $balance)
                            {
                                Session::flash('danger', 'Please Update your Balance.');
                                return $next($request);
                            }
                            $new_balance = $balance-$amount;
                            $final_balance = Crypt::encrypt($new_balance);
                            DB::table('wallets')->where('user_id',$from->id)->update(['balance' => $final_balance]);
                            $amount_out =  Crypt::encrypt($amount);
                            $payment_data = [
                                'previous_balance' => $old_balance,
                                'cash_out' => $amount_out,
                                'remaining_balance' => $final_balance,
                            ];
                            $plan_data = [
                                'plan_id' => $plansubscriber->plan_id,
                                'type' => $plansubscriber->plan_type,
                            ];
                            $plan_payment = new PlanPayment();
                            $plan_payment->user = "Seller";
                            $plan_payment->transfer_to = $user->id;
                            $plan_payment->transfer_from = $from->id;
                            $plan_payment->plan_data = json_encode($plan_data);
                            $plan_payment->payment_data = json_encode($payment_data);
                            $plan_payment->status = "Completed";
                            $plan_payment->save();

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
                            $trs->user_id = $from->id;
                            $trs->cash_out = $amount_out;
                            $trs->previous_balance = $old_balance;
                            $trs->type = "Plan Payment";
                            $trs->status = "Completed";
                            $trs->transfar_to = $to;
                            $trs->note = "This is Plan Payment";
                            $trs->save();

                            // for admin
                            $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
                            $old_balance = $wallet->balance;
                            $balance = Crypt::decrypt($old_balance);
                            $new_balance = $balance+$amount;
                            $final_balance = Crypt::encrypt($new_balance);
                            DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

                            $amount_in =  Crypt::encrypt($amount);
                            $payment_data = [
                                'previous_balance' => $old_balance,
                                'cash_in' => $amount_in,
                                'remaining_balance' => $final_balance,
                            ];
                            $plan_data = [
                                'plan_id' => $plansubscriber->plan_id,
                                'type' => $plansubscriber->plan_type,
                            ];
                            $plan_payment = new PlanPayment();
                            $plan_payment->user = "Admin";
                            $plan_payment->transfer_to = $user->id;
                            $plan_payment->transfer_from = $from->id;
                            $plan_payment->plan_data = json_encode($plan_data);
                            $plan_payment->payment_data = json_encode($payment_data);
                            $plan_payment->status = "Completed";
                            $plan_payment->save();

                            $plansubscriber->payment = "Paid";
                            $plansubscriber->update();

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
                            $trs->cash_in = $amount_in;
                            $trs->previous_balance = $old_balance;
                            $trs->type = "Plan Payment";
                            $trs->status = "Completed";
                            $trs->transfar_from = $from->email;
                            $trs->note = "This is plan payment";
                            $trs->save();

                            $notification = [
                                'type'=> 'Plan Payment',
                            ];
                            $user1 = User::where('id',$request->user()->id)->first();
                            $user1->notify(new MyNotification($notification));

                            $notification = [
                                'type'=> 'Plan Payment',
                            ];
                            $user->notify(new MyNotification($notification));

                            return $next($request);
                        }
                    }
                    else{
                        if ($plansubscriber->payment == "Paid")
                        {
                            return $next($request);
                        }
                        else{
                            $amount_data = json_decode($plansubscriber->plan_get['plan_price']);
                            $amount = $amount_data->Monthly;
                            $from = $request->user();
                            $user = User::where('role','SuperAdmin')->first();
                            // for seller
                            $wallet = DB::table('wallets')->where('user_id',$from->id)->first();
                            $old_balance = $wallet->balance;
                            $balance = Crypt::decrypt($old_balance);
                            if ($amount > $balance)
                            {
                                $ldate = date('Y-m-d H:i:s');
                                $today = date_create($ldate);
                                $date = date_create($plansubscriber->updated_at->format('Y-m-d H:i:s'));
                                // Now find the difference in days.
                                $difference = date_diff( $today,$date,true );
                                $days = $difference->days;
                                if ($days > 30)
                                {
                                    $plansubscriber->delete();
                                    return redirect(route('seller.plan.not.subscribe.index'));
                                }
                                else{
                                    Session::flash('danger', 'Please Update your Balance.');
                                    return $next($request);
                                }
                            }
                            $new_balance = $balance-$amount;
                            $final_balance = Crypt::encrypt($new_balance);
                            DB::table('wallets')->where('user_id',$from->id)->update(['balance' => $final_balance]);
                            $amount_out =  Crypt::encrypt($amount);
                            $payment_data = [
                                'previous_balance' => $old_balance,
                                'cash_out' => $amount_out,
                                'remaining_balance' => $final_balance,
                            ];
                            $plan_data = [
                                'plan_id' => $plansubscriber->plan_id,
                                'type' => $plansubscriber->plan_type,
                            ];
                            $trs = new PlanPayment();
                            $trs->user = "Seller";
                            $trs->transfer_to = $user->id;
                            $trs->transfer_from = $from->id;
                            $trs->plan_data = json_encode($plan_data);
                            $trs->payment_data = json_encode($payment_data);
                            $trs->status = "Completed";
                            $trs->save();

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
                            $trs->user_id = $from->id;
                            $trs->cash_out = $amount_out;
                            $trs->previous_balance = $old_balance;
                            $trs->type = "Plan Payment";
                            $trs->status = "Completed";
                            $trs->transfar_to = $to;
                            $trs->note = "This is Plan Payment";
                            $trs->save();
                            // for admin
                            $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
                            $old_balance = $wallet->balance;
                            $balance = Crypt::decrypt($old_balance);
                            $new_balance = $balance+$amount;
                            $final_balance = Crypt::encrypt($new_balance);
                            DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

                            $amount_in =  Crypt::encrypt($amount);
                            $payment_data = [
                                'previous_balance' => $old_balance,
                                'cash_in' => $amount_in,
                                'remaining_balance' => $final_balance,
                            ];
                            $plan_data = [
                                'plan_id' => $plansubscriber->plan_id,
                                'type' => $plansubscriber->plan_type,
                            ];
                            $trs = new PlanPayment();
                            $trs->user = "Admin";
                            $trs->transfer_to = $user->id;
                            $trs->transfer_from = $from->id;
                            $trs->plan_data = json_encode($plan_data);
                            $trs->payment_data = json_encode($payment_data);
                            $trs->status = "Completed";
                            $trs->save();

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
                            $trs->cash_in = $amount_in;
                            $trs->previous_balance = $old_balance;
                            $trs->type = "Plan Payment";
                            $trs->status = "Completed";
                            $trs->transfar_from = $from->email;
                            $trs->note = "This is plan payment";
                            $trs->save();

                            $plansubscriber->payment = "Paid";
                            $plansubscriber->update();

                            $notification = [
                                'type'=> 'Plan Payment',
                            ];
                            $user1 = User::where('id',$request->user()->id)->first();
                            $user1->notify(new MyNotification($notification));

                            $notification = [
                                'type'=> 'Plan Payment',
                            ];
                            $user->notify(new MyNotification($notification));

                            return $next($request);
                        }
                    }
                }
                else{
                    $ldate = date('Y-m-d H:i:s');
                    $today = date_create($ldate);
                    $to = \Carbon\Carbon::parse($plansubscriber->updated_at);
                    $from = \Carbon\Carbon::parse($today);
                    // Now find the difference in months.
                    $years = $to->diffInYears($from);
                    // Now find the difference in days.
                    $days = $to->diffInDays($from);
                    $remaing_days = $days - 365;

                    if ($years > 1)
                    {
                        $plansubscriber->delete();
                        return redirect(route('seller.plan.not.subscribe.index'));
                    }
                    elseif ($years == 1)
                    {
                        // update payment
                        $plansubscriber->payment = "Unpaid";
                        $plansubscriber->update();

                        if (abs($remaing_days) > 30)
                        {
                            $plansubscriber->delete();
                            return redirect(route('seller.plan.not.subscribe.index'));
                        }
                        else{
                            $amount_data = json_decode($plansubscriber->plan_get['plan_price']);
                            $amount = $amount_data->Yearly;
                            $from = $request->user();
                            $user = User::where('role','SuperAdmin')->first();
                            // for seller
                            $wallet = DB::table('wallets')->where('user_id',$from->id)->first();
                            $old_balance = $wallet->balance;
                            $balance = Crypt::decrypt($old_balance);
                            if ($amount > $balance)
                            {
                                Session::flash('danger', 'Please Update your Balance.');
                                return $next($request);
                            }
                            $new_balance = $balance-$amount;
                            $final_balance = Crypt::encrypt($new_balance);
                            DB::table('wallets')->where('user_id',$from->id)->update(['balance' => $final_balance]);
                            $amount_out =  Crypt::encrypt($amount);
                            $payment_data = [
                                'previous_balance' => $old_balance,
                                'cash_out' => $amount_out,
                                'remaining_balance' => $final_balance,
                            ];
                            $plan_data = [
                                'plan_id' => $plansubscriber->plan_id,
                                'type' => $plansubscriber->plan_type,
                            ];
                            $trs = new PlanPayment();
                            $trs->user = "Seller";
                            $trs->transfer_to = $user->id;
                            $trs->transfer_from = $from->id;
                            $trs->plan_data = json_encode($plan_data);
                            $trs->payment_data = json_encode($payment_data);
                            $trs->status = "Completed";
                            $trs->save();

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
                            $trs->user_id = $from->id;
                            $trs->cash_out = $amount_out;
                            $trs->previous_balance = $old_balance;
                            $trs->type = "Plan Payment";
                            $trs->status = "Completed";
                            $trs->transfar_to = $to;
                            $trs->note = "This is Plan Payment";
                            $trs->save();
                            // for admin
                            $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
                            $old_balance = $wallet->balance;
                            $balance = Crypt::decrypt($old_balance);
                            $new_balance = $balance+$amount;
                            $final_balance = Crypt::encrypt($new_balance);
                            DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

                            $amount_in =  Crypt::encrypt($amount);
                            $payment_data = [
                                'previous_balance' => $old_balance,
                                'cash_in' => $amount_in,
                                'remaining_balance' => $final_balance,
                            ];
                            $plan_data = [
                                'plan_id' => $plansubscriber->plan_id,
                                'type' => $plansubscriber->plan_type,
                            ];
                            $trs = new PlanPayment();
                            $trs->user = "Admin";
                            $trs->transfer_to = $user->id;
                            $trs->transfer_from = $from->id;
                            $trs->plan_data = json_encode($plan_data);
                            $trs->payment_data = json_encode($payment_data);
                            $trs->status = "Completed";
                            $trs->save();

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
                            $trs->cash_in = $amount_in;
                            $trs->previous_balance = $old_balance;
                            $trs->type = "Plan Payment";
                            $trs->status = "Completed";
                            $trs->transfar_from = $from->email;
                            $trs->note = "This is plan payment";
                            $trs->save();

                            $plansubscriber->payment = "Paid";
                            $plansubscriber->update();

                            $notification = [
                                'type'=> 'Plan Payment',
                            ];
                            $user1 = User::where('id',$request->user()->id)->first();
                            $user1->notify(new MyNotification($notification));

                            $notification = [
                                'type'=> 'Plan Payment',
                            ];
                            $user->notify(new MyNotification($notification));

                            return $next($request);
                        }
                    }
                    else{
                        if ($plansubscriber->payment == "Paid")
                        {
                            return $next($request);
                        }
                        else{
                            $amount_data = json_decode($plansubscriber->plan_get['plan_price']);
                            $amount = $amount_data->Yearly;
                            $from = $request->user();
                            $user = User::where('role','SuperAdmin')->first();
                            // for seller
                            $wallet = DB::table('wallets')->where('user_id',$from->id)->first();
                            $old_balance = $wallet->balance;
                            $balance = Crypt::decrypt($old_balance);
                            if ($amount > $balance)
                            {
                                $ldate = date('Y-m-d H:i:s');
                                $today = date_create($ldate);
                                $date = date_create($plansubscriber->updated_at->format('Y-m-d H:i:s'));
                                // Now find the difference in days.
                                $difference = date_diff( $today,$date,true );
                                $days = $difference->days;
                                if ($days > 10)
                                {
                                    $plansubscriber->delete();
                                    return redirect(route('seller.plan.not.subscribe.index'));
                                }
                                else{
                                    Session::flash('danger', 'Please Update your Balance.');
                                    return $next($request);
                                }
                            }
                            $new_balance = $balance-$amount;
                            $final_balance = Crypt::encrypt($new_balance);
                            DB::table('wallets')->where('user_id',$from->id)->update(['balance' => $final_balance]);
                            $amount_out =  Crypt::encrypt($amount);
                            $payment_data = [
                                'previous_balance' => $old_balance,
                                'cash_out' => $amount_out,
                                'remaining_balance' => $final_balance,
                            ];
                            $plan_data = [
                                'plan_id' => $plansubscriber->plan_id,
                                'type' => $plansubscriber->plan_type,
                            ];
                            $trs = new PlanPayment();
                            $trs->user = "Seller";
                            $trs->transfer_to = $user->id;
                            $trs->transfer_from = $from->id;
                            $trs->plan_data = json_encode($plan_data);
                            $trs->payment_data = json_encode($payment_data);
                            $trs->status = "Completed";
                            $trs->save();

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
                            $trs->user_id = $from->id;
                            $trs->cash_out = $amount_out;
                            $trs->previous_balance = $old_balance;
                            $trs->type = "Plan Payment";
                            $trs->status = "Completed";
                            $trs->transfar_to = $to;
                            $trs->note = "This is Plan Payment";
                            $trs->save();
                            // for admin
                            $wallet = DB::table('wallets')->where('user_id',$user->id)->first();
                            $old_balance = $wallet->balance;
                            $balance = Crypt::decrypt($old_balance);
                            $new_balance = $balance+$amount;
                            $final_balance = Crypt::encrypt($new_balance);
                            DB::table('wallets')->where('user_id',$user->id)->update(['balance' => $final_balance]);

                            $amount_in =  Crypt::encrypt($amount);
                            $payment_data = [
                                'previous_balance' => $old_balance,
                                'cash_in' => $amount_in,
                                'remaining_balance' => $final_balance,
                            ];
                            $plan_data = [
                                'plan_id' => $plansubscriber->plan_id,
                                'type' => $plansubscriber->plan_type,
                            ];
                            $trs = new PlanPayment();
                            $trs->user = "Admin";
                            $trs->transfer_to = $user->id;
                            $trs->transfer_from = $from->id;
                            $trs->plan_data = json_encode($plan_data);
                            $trs->payment_data = json_encode($payment_data);
                            $trs->status = "Completed";
                            $trs->save();

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
                            $trs->cash_in = $amount_in;
                            $trs->previous_balance = $old_balance;
                            $trs->type = "Plan Payment";
                            $trs->status = "Completed";
                            $trs->transfar_from = $from->email;
                            $trs->note = "This is plan payment";
                            $trs->save();

                            $plansubscriber->payment = "Paid";
                            $plansubscriber->update();

                            $notification = [
                                'type'=> 'Plan Payment',
                            ];
                            $user1 = User::where('id',$request->user()->id)->first();
                            $user1->notify(new MyNotification($notification));

                            $notification = [
                                'type'=> 'Plan Payment',
                            ];
                            $user->notify(new MyNotification($notification));

                            return $next($request);
                        }
                    }
                }
            }
        }
        else{
            return redirect(route('seller.plan.not.subscribe.index'));
        }
    }
}
