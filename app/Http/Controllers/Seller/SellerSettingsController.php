<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\CronJob;
use Illuminate\Support\Facades\Http;
use App\Models\Bank;
use App\Models\Paypal;
use App\Models\Plan;
use App\Models\PlanSubscriber;
use App\Models\SellerApi;
use App\Models\SMSACredential;
use App\MyClasses\Helpers;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use SmsaSDK\Smsa;
use Illuminate\Notifications\Notifiable;
use Session;

class SellerSettingsController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::user()->id;
        $update = User::find($id);
        $update->theme = $request->layout;
        $update->update();
        return response()->json([
            'status' => 200,
        ]);
    }
    public function api_integration()
    {
        $user = Auth::user()->id;
        $data = SellerApi::where('user_id', $user)->first();
        return view('Seller.api_integration', compact('data'));
    }
    public function woo_integrate(Request $request)
    {
        $request->validate([
            'domain_url' => 'required|url',
            'consumer_key' => 'required',
            'consumer_secret' => 'required',
        ]);
        $simple = Crypt::encrypt($request->consumer_key);
        $secret = Crypt::encrypt($request->consumer_secret);
        $details = json_encode([
            'domain_url' => $request->domain_url,
            'consumer_key' => $simple,
            'consumer_secret' => $secret
        ]);
        $user = Auth::user()->id;
        $plan_subscriber = PlanSubscriber::where('user_id', $user)->first();
        $plan = Plan::find($plan_subscriber->plan_id);
        $check_length = CronJob::where('user_id', $user)->get();

        if ($plan->plateform_sync > count($check_length)) {
            $data = SellerApi::where('user_id', $user)->first();
            $data->woo_details = $details;
            $data->update();

            $add_job = new CronJob();
            $add_job->user_id = $user;
            $add_job->job_type = "woo_orders";
            $add_job->job_data = "nothing";
            $add_job->job_status = "Pending";
            $add_job->save();
            Session::flash('success', 'WooCommerce API is updated successful.');
            return back();
        } else {
            Session::flash('danger', 'Plateform API Sync limit is full.');
            return back();
        }
    }
    public function shopify_integrate(Request $request)
    {
        $request->validate([
            'api_key' => 'required',
            'password' => 'required',
            'hostname' => 'required|url',
            'access_token' => 'required',
        ]);
        $semi_hostname = '';
        $disallowed = array('http://', 'https://');
        foreach ($disallowed as $d) {
            if (strpos($request->hostname, $d) === 0) {
                $semi_hostname = str_replace($d, '', $request->hostname);
            }
        }

        $final_hostname = str_replace('/', '', $semi_hostname);
        $api = Crypt::encrypt($request->api_key);
        $password = Crypt::encrypt($request->password);
        $hostname = Crypt::encrypt($final_hostname);
        $token = Crypt::encrypt($request->access_token);
        $details = json_encode([
            'api_key' => $api,
            'password' => $password,
            'hostname' => $hostname,
            'access_token' => $token,
        ]);
        $user = Auth::user()->id;
        $data = SellerApi::where('user_id', $user)->first();
        $data->shopify_details = $details;
        $data->update();
        Session::flash('success', 'Shopify API is updated successful.');
        return back();
    }
    public function api_delete($type)
    {
        $user = Auth::user()->id;
        if ($type == "woo") {
            $data = SellerApi::where('user_id', $user)->first();
            $data->woo_details = Null;
            $data->woo = Null;
            $data->update();

            $plateform = CronJob::where('user_id', $user)->where('job_type', 'woo_orders')->first();
            $plateform->delete();

            Session::flash('success', 'WooCommerce API is deleted successful.');
            return back();
        }
        if ($type == "shopify") {
            $data = SellerApi::where('user_id', $user)->first();
            $data->shopify_details = Null;
            $data->shopify = Null;
            $data->update();
            Session::flash('success', 'Shopify API is deleted successful.');
            return back();
        }
    }
    public function on_off(Request $request)
    {
        $tab = $request->tab;
        $value = $request->value;
        if ($tab == "woo_check") {
            if ($value == "true") {
                $user = Auth::user()->id;
                $data = SellerApi::where('user_id', $user)->first();
                $data->woo = $value;
                $data->update();
            }
            if ($value == "null") {
                $user = Auth::user()->id;
                $data = SellerApi::where('user_id', $user)->first();
                $data->woo = Null;
                $data->update();
            }
        }
        if ($tab == "shopify_check") {
            if ($value == "true") {
                $user = Auth::user()->id;
                $data = SellerApi::where('user_id', $user)->first();
                $data->shopify = $value;
                $data->update();
            }
            if ($value == "null") {
                $user = Auth::user()->id;
                $data = SellerApi::where('user_id', $user)->first();
                $data->shopify = Null;
                $data->update();
            }
        }
    }

    public function smsa_credentials()
    {
        $user = Auth::user()->id;
        $passkey = SMSACredential::where('user_id', $user)->first();
        return view("Seller.smsa_credentials", ['passkey' => $passkey]);
    }

    public function store_credentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passkey' => 'required',
        ], [], [
            'passkey' => 'Passkey',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $user = Auth::user()->id;

            try {
                // Get status from SMSA
                $status = Smsa::getRTLCities($request->passkey);
                $results = $status->getGetRTLCitiesResult();
                $result = explode('</xs:schema>', $results->getAny());
                $array = json_decode(json_encode((array) simplexml_load_string($result[0])), true);
                if (isset($array['NewDataSet']['RetailCities'])) {

                    // Save the passkey in the database
                    $smsa = new SMSACredential();
                    $smsa->passkey = $request->passkey;
                    $smsa->user_id = $user;
                    $smsa->save();

                    // Session::flash('success', 'SMSA Credentials Saved successfully.');
                    // return back();
                    return response()->json([
                        'status' => 'success',
                        'message' => "SMSA Credentials Saved successfully.",
                    ]);
                } else {
                    // Session::flash('error', 'Incorrect passkey. Please try again.');
                    // return back()->withErrors(['passkey' => 'Invalid passkey provided']);
                    return response()->json([
                        'status' => 'danger',
                        'message' => "Invalid passkey provided",
                    ]);
                }
            } catch (\Exception $e) {
                // Session::flash('error', 'An error occurred while validating the passkey.');
                // return back()->withErrors(['passkey' => 'Error: ' . $e->getMessage()]);
                return response()->json([
                    'status' => 'danger',
                    'data' => $e->getMessage(),
                    'message' => "An error occurred while validating the passkey.",
                ]);
            }
        }
    }

    public function edit_credentials($id)
    {
        $data = SMSACredential::find($id);
        return response()->json($data);
    }
    public function update_credentials(Request $request)
    {
        $update = SMSACredential::find($request->id);
        $request->validate([
            'passkey' => 'required',
        ]);
        $update->passkey = $request->passkey;
        $update->update();
        return response()->json([
            'status' => 200,
            'message' => "SMSA Credentials Updated Successful.",
        ]);
    }
    public function bank()
    {
        $user = Auth::user()->id;
        $records = Bank::where('user_id', $user)->get();
        return view('Seller.bank', compact('records'));
    }
    public function bank_add(Request $request)
    {
        $request->validate([
            'bank_name' => ['required', 'string'],
            'account_name' => ['required', 'string'],
            'iban_no' => ['required', 'alphanum', 'unique:banks']
        ]);
        $user = Auth::user()->id;
        $db = new Bank();
        $db->user_id = $user;
        $db->bank_name = $request->bank_name;
        $db->account_name = $request->account_name;
        $db->iban_no = $request->iban_no;
        $db->save();
        Session::flash('success', 'New Bank has been successfully added.');
        return redirect()->route('seller.bank');
    }
    public function bank_delete(Request $request)
    {
        $record = Bank::find($request->id);
        $user_id = Auth::user()->id;
        if ($user_id == $record->user_id) {
            $record->delete();
        }
    }
    public function paypal_delete(Request $request)
    {
        $record = Paypal::find($request->id);
        $user_id = Auth::user()->id;
        if ($user_id == $record->user_id) {
            $record->delete();
        }
    }
    public function paypal()
    {
        $user = Auth::user()->id;
        $records = Paypal::where('user_id', $user)->get();
        return view('Seller.paypal', compact('records'));
    }
    public function paypal_add(Request $request)
    {
        $request->validate([
            'paypal_email' => ['required', 'email', 'unique:paypals']
        ]);
        $user = Auth::user()->id;
        $db = new Paypal();
        $db->user_id = $user;
        $db->paypal_email = $request->paypal_email;
        $db->save();
        Session::flash('success', 'New Paypal has been successfully added.');
        return redirect()->route('seller.paypal');
    }
    public function bank_add_model(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => ['required', 'string'],
            'account_name' => ['required', 'string'],
            'iban_no' => ['required', 'alphanum', 'unique:banks']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $user = Auth::user()->id;
            $db = new Bank();
            $db->user_id = $user;
            $db->bank_name = $request->bank_name;
            $db->account_name = $request->account_name;
            $db->iban_no = $request->iban_no;
            $db->save();
            return response()->json([
                'status' => 200,
                'message' => 'New Bank has been successfully added.',
            ]);
        }
    }
    public function paypal_add_model(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paypal_email' => ['required', 'email', 'unique:paypals'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $user = Auth::user()->id;
            $db = new Paypal();
            $db->user_id = $user;
            $db->paypal_email = $request->paypal_email;
            $db->save();
            return response()->json([
                'status' => 200,
                'message' => 'New Paypal has been successfully added.',
            ]);
        }
    }

    public function get_noti()
    {
        if (session()->missing('noti')) {
            session()->put('noti', 0);
        }
        $user = Auth::user();
        $notifications = $user->unreadNotifications()->limit(10)->orderBy('created_at', 'desc')->get();
        $view = view('Seller.temp.notification', compact('notifications'))->render();
        $count = auth()->user()->unreadNotifications()->limit(10)->orderBy('created_at', 'desc')->count();
        $new = null;
        $body = null;
        $arri = null;
        $session = Session::get('noti');
        if ($session < $count) {
            $arri = "true";
            session()->put('noti', $count);
        } else {
            $arri = "false";
            session()->put('noti', $count);
        }
        if ($count > 0) {
            $new = "true";
            $body = $view;
        } else {
            $new = "false";
            $body = "<p class=' pt-3 text-muted text-center'>No Notification</p>";
        }
        $result = ['new' => $new, 'body' => $body, 'arrival' => $arri];
        return json_encode($result);
    }
    public function get_noti_see(Request $request)
    {
        auth()->user()->unreadNotifications->where('id', $request->id)->markAsRead();
    }
    public function get_noti_see_by_id($id)
    {
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    }
    public function notification()
    {
        $user = Auth::user();
        $notifications = $user->Notifications()->orderBy('created_at', 'desc')->paginate(25);
        return view('Seller.notifications', compact('notifications'));
    }
    public function notification_read()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function profile()
    {
        return view('Seller.profile');
    }
    public function profile_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'phone_number' => ['required', 'numeric'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'numeric'],
            'country' => ['required', 'string'],
            'company_name' => ['required', 'string']
        ], [], [
            'name' => 'Name',
            'phone_number' => 'Phone Number',
            'address' => 'Address',
            'city' => 'City',
            'postal_code' => 'Postal Code',
            'country' => 'Country',
            'company_name' => 'Company Name'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $id = Auth::user()->id;

            $update = User::find($id);
            $update->name = $request->name;
            $update->mobile_no = $request->phone_number;
            $update->company_name = $request->company_name;
            $update->address = $request->address;
            $update->city = $request->city;
            $update->postal_code = $request->postal_code;
            $update->country = $request->country;
            $update->update();
            // Session::flash('success', 'Profile Updated Successful.');
            // return redirect()->route('seller.profile');
            return response()->json([
                'status' => 'success',
                'message' => 'Profile Updated Successful.'
            ]);
        }
    }
    public function profile_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:500'],
        ], [], [
            'image' => 'Image',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $file_name = date('YmdHis') . rand(1, 10000) . "." . $request->file('image')->extension();
            $id = Auth::user()->id;
            $update = User::find($id);
            $old_file = Auth::user()->profile_img;
            if (!empty($old_file)) {
                $path = public_path('uploads/profiles/' . $old_file);
                unlink($path);
            }
            $update->profile_img = $file_name;
            $update->update();
            $request->file('image')->move(public_path('uploads/profiles'), $file_name);
            // Session::flash('image', 'Profile Image Updated Successful.');
            // return redirect()->route('seller.profile');
            return response()->json([
                'status' => 'success',
                'message' => 'Profile Image Updated Successful.'
            ]);
        }
    }
    public function profile_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', Rules\Password::defaults()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required'],
        ], [], [
            'old_password' => 'Old Password',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $old = Auth::user()->password;
            if (Hash::check($request->old_password, $old)) {
                $id = Auth::user()->id;
                $update = User::find($id);
                $update->password = Hash::make($request->password);
                $update->update();
                // Session::flash('password', 'Password Updated Successful.');
                // return redirect()->route('seller.profile');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password Updated Successful.'
                ]);
            } else {
                // Session::flash('old password', 'Old Password does not correct');
                // return redirect()->route('seller.profile');
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Old Password does not correct.'
                ]);
            }
        }
    }
    public function get_languages(Request $request)
    {
        return Helpers::rem_lang($request->ref_id, $request->ref_type);
    }
}
