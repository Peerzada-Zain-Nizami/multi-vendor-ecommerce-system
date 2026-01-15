<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SMSACredential;
use App\Models\User;
use App\MyClasses\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Notifications\Notifiable;
use SmsaSDK\Smsa;
use Illuminate\Support\Facades\Validator;
use Session;
class AdminSettingsController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::user()->id;
        $update = User::find($id);
        $update->theme = $request->layout;
        $update->update();
        return response()->json([
            'status'=>200,
        ]);
    }
    public function get_noti()
    {
        if (session()->missing('noti'))
        {
            session()->put('noti', 0);
        }
        $user = Auth::user();
        $notifications = $user->unreadNotifications()->limit(10)->orderBy('created_at','desc')->get();
        $view = view('Admin.temp.notification',compact('notifications'))->render();
        $count = auth()->user()->unreadNotifications()->limit(10)->orderBy('created_at','desc')->count();
        $new = null;
        $body = null;
        $arri = null;
        $session = Session::get('noti');
        if ($session < $count)
        {
            $arri = "true";
            session()->put('noti', $count);
        }
        else{
            $arri = "false";
            session()->put('noti', $count);
        }
        if ($count > 0)
        {
            $new = "true";
            $body = $view;
        }
        else{
            $new = "false";
            $body = "<p class=' pt-3 text-muted text-center'>No Notification</p>";
        }
        $result = ['new'=>$new,'body'=>$body,'arrival'=>$arri];
        return json_encode($result);
    }
    public function get_noti_see(Request $request)
    {
        auth()->user()->unreadNotifications->where('id', $request->id)->markAsRead();
    }
    public function notification()
    {
        $user = Auth::user();
        $notifications = $user->Notifications()->orderBy('created_at','desc')->paginate(25);
        return view('Admin.notifications',compact('notifications'));
    }
    public function notification_read()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
    public function profile()
    {
        return view('Admin.profile');
    }
    public function profile_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'phone_number' => ['required', 'numeric'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'numeric'],
            'country' => ['required', 'string']
        ], [], [
            'name' => 'Name',
            'phone_number' => 'Phone Number',
            'address' => 'Address',
            'city' => 'City',
            'postal_code' => 'Postal Code',
            'country' => 'Country',
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
            $update->address = $request->address;
            $update->city = $request->city;
            $update->postal_code = $request->postal_code;
            $update->country = $request->country;
            $update->update();
            return response()->json([
                'status' => 'success',
                'message' => 'Profile Updated Successful.'
            ]);
            // Session::flash('success', 'Profile Updated Successful.');
            // return redirect()->route('admin.profile');
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
            return response()->json([
                'status' => 'success',
                'message' => 'Profile Image Updated Successful.'
            ]);
            // Session::flash('image', 'Profile Image Updated Successful.');
            // return redirect()->route('admin.profile');
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
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password Updated Successful.'
                ]);
                // Session::flash('password', 'Password Updated Successful.');
                // return redirect()->route('admin.profile');
            } else {
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Old Password does not correct.'
                ]);
                // Session::flash('password error', 'Old Password does not correct');
                // return redirect()->route('admin.profile');
            }
        }
    }

    public function smsa_credentials()
    {
        $user = Auth::user()->id;
        $passkey = SMSACredential::where('user_id',$user)->first();
        return view("Admin.smsa_credentials",['passkey'=>$passkey]);
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
            $update = SMSACredential::find($request->id);
            $update->passkey = $request->passkey;
            $update->update();
            return response()->json([
                'status' => 'success',
                'message' => "SMSA Credentials Updated Successful.",
            ]);
        }
    }
    public function get_languages(Request $request)
    {
        return Helpers::rem_lang($request->ref_id,$request->ref_type);
    }

}
