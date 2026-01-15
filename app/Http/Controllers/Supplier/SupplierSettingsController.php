<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Paypal;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Session;

class SupplierSettingsController extends Controller
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
        $notifications = $user->unreadNotifications()->take(10)->orderBy('created_at','desc')->get();
        $view = view('Supplier.temp.notification',compact('notifications'))->render();
        $count = auth()->user()->unreadNotifications()->count();
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
        return view('Supplier.notifications',compact('notifications'));
    }
    public function notification_read()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
    public function profile()
    {
        return view('Supplier.profile');
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
            // Session::flash('success', 'Profile Updated Successful.');
            // return redirect()->route('supplier.profile');
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
        }
        else {
        $file_name = date('YmdHis').rand(1,10000).".".$request->file('image')->extension();
        $id = Auth::user()->id;
        $update = User::find($id);
        $old_file = Auth::user()->profile_img;
        if (!empty($old_file))
        {
            $path = public_path('uploads/profiles/'.$old_file);
            unlink($path);
        }
        $update->profile_img = $file_name;
        $update->update();
        $request->file('image')->move(public_path('uploads/profiles'),$file_name);
        // Session::flash('image', 'Profile Image Updated Successful.');
        // return redirect()->route('supplier.profile');
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
                // return redirect()->route('supplier.profile');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password Updated Successful.'
                ]);
            } else {
                // Session::flash('old password', 'Old Password does not correct');
                // return redirect()->route('supplier.profile');

                return response()->json([
                    'status' => 'danger',
                    'message' => 'Old Password does not correct.'
                ]);
            }
        }
    }

}
