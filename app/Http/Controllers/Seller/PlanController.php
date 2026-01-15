<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanPayment;
use App\Models\PlanSubscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Session;

class PlanController extends Controller
{
    //plan not subscribe1``````````````````
    public function PlanNotSubscribe()
    {
        return view('Seller.plan_not_found');
    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $plan_subscriber = PlanSubscriber::where('user_id', $user_id)->first();
        $plans = Plan::where('status', 'Active')->get();
        return view('Seller.plan', ['plans' => $plans, 'plan_subscriber' => $plan_subscriber]);
    }

    public function plan_get($id)
    {
        $plan = Plan::find($id);
        $j_data = json_decode($plan->plan_price);
        return response()->json([
            'status' => 200,
            'data' => $j_data,
        ]);
    }

    public function current_plan()
    {
        $user_id = Auth::user()->id;
        $plan_subscriber = PlanSubscriber::where('user_id', $user_id)->first();
        if ($plan_subscriber == null) {
            return view('Seller.plan_not_found');
        }
        else
        {
            $plan = Plan::where('id', $plan_subscriber->plan_id)->first();
            return view('Seller.current_plan', ['plan' => $plan, 'plan_subscriber' => $plan_subscriber]);
        }
    }

    public function view($id)
    {
        $datas = Plan::find($id);
        return view('Seller.plane_view', ['datas' => $datas]);
    }

    public function subscribe($id)
    {
        $user_id = Auth::user()->id;
        $plan = new PlanSubscriber();
        $plan->user_id = $user_id;
        $plan->plan_id = $id;
        $plan->payment = "Unpaid";
        $plan->plan_type = "";
        $plan->save();
        // Session::flash('Success', 'Plan has been Successfully Subscribed.');
        // return back();
        return response()->json([
            'status' => 200,
            'message' => 'Plan has been Successfully Subscribed.',
        ]);
    }

    public function subscribe_type(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'numeric'],
            'type' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $user_id = Auth::user()->id;
            $wallet = DB::table('wallets')->where('user_id',$user_id)->first();
            $balance = Crypt::decrypt($wallet->balance);

            $plan = Plan::find($request->id);
            $plan_prices = json_decode($plan->plan_price);
            if ($request->type == "Monthly")
            {
                $charge_amount = $plan_prices->Monthly;
            }
            elseif ($request->type == "Yearly")
            {
                $charge_amount = $plan_prices->Yearly;
            }

            if ($charge_amount > $balance)
            {
                return response()->json([
                    'status' => 401,
                    'message' => "Please Recharge You Wallet Balance.",
                ]);
            }

            $plan = new PlanSubscriber();
            $plan->user_id = $user_id;
            $plan->plan_id = $request->id;
            $plan->plan_type = $request->type;
            $plan->payment = "Unpaid";
            $plan->save();
            return response()->json([
                'status' => 200,
                'data' => $plan,
                'message' => "Plan has been Successfully Subscribed.",
            ]);
        }
    }

    public function unsubscribe($id)
    {
        $user = Auth::user()->id;
        $plan = PlanSubscriber::where('user_id', $user)->where('plan_id', $id)->first();
        if ($plan) {
            $plan->delete();
            return back();
        }
    }

    //Plan Price
    public function plan_price_index()
    {
        $user = Auth::user()->id;
        $plans = PlanPayment::where('user', 'Seller')->where('transfer_from', $user)->orderBy('id', 'DESC')->get();
        return view('Seller.plan_price', ['plans' => $plans]);
    }
}
