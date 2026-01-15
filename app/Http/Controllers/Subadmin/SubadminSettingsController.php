<?php

namespace App\Http\Controllers\Subadmin;

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

class SubadminSettingsController extends Controller
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
    public function bank()
    {
        $user = Auth::user()->id;
        $records = Bank::where('user_id',$user)->get();
        return view('Subadmin.bank', compact('records'));
    }
    public function bank_add(Request $request)
    {
        $request->validate([
            'bank_name'=> ['required','string'],
            'account_name'=> ['required','string'],
            'iban_no'=> ['required','alphanum','unique:banks']
        ]);
        $user = Auth::user()->id;
        $db = new Bank();
        $db->user_id = $user;
        $db->bank_name = $request->bank_name;
        $db->account_name = $request->account_name;
        $db->iban_no = $request->iban_no;
        $db->save();
        Session::flash('success', 'New Bank has been successfully added.');
        return redirect()->route('Subadmin.bank');
    }
    public function bank_delete(Request $request)
    {
        $record = Bank::find($request->id);
        $user_id = Auth::user()->id;
        if ($user_id == $record->user_id)
        {
            $record->delete();
        }
    }
    public function paypal_delete(Request $request)
    {
        $record = Paypal::find($request->id);
        $user_id = Auth::user()->id;
        if ($user_id == $record->user_id)
        {
            $record->delete();
        }
    }
    public function paypal()
    {
        $user = Auth::user()->id;
        $records = Paypal::where('user_id',$user)->get();
        return view('Subadmin.paypal', compact('records'));
    }
    public function paypal_add(Request $request)
    {
        $request->validate([
            'paypal_email'=> ['required','email','unique:paypals']
        ]);
        $user = Auth::user()->id;
        $db = new Paypal();
        $db->user_id = $user;
        $db->paypal_email = $request->paypal_email;
        $db->save();
        Session::flash('success', 'New Paypal has been successfully added.');
        return redirect()->route('Subadmin.paypal');
    }
    public function bank_add_model(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name'=> ['required','string'],
            'account_name'=> ['required','string'],
            'iban_no'=> ['required','alphanum','unique:banks']
        ]);
        if ($validator->fails())
        {
            return response()->json([
               'status'=>400,
               'errors'=>$validator->errors(),
            ]);
        }
        else{
            $user = Auth::user()->id;
            $db = new Bank();
            $db->user_id = $user;
            $db->bank_name = $request->bank_name;
            $db->account_name = $request->account_name;
            $db->iban_no = $request->iban_no;
            $db->save();
            return response()->json([
                'status'=>200,
                'message'=>'New Bank has been successfully added.',
            ]);
        }
    }
    public function paypal_add_model(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paypal_email'=> ['required','email','unique:paypals'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $user = Auth::user()->id;
            $db = new Paypal();
            $db->user_id = $user;
            $db->paypal_email = $request->paypal_email;
            $db->save();
            return response()->json([
                'status'=>200,
                'message'=>'New Paypal has been successfully added.',
            ]);
        }
    }
    public function profile()
    {
        return view('Subadmin.profile');
    }
    public function profile_update(Request $request)
    {
        $request->validate([
            'name'=>['required','string'],
            'phone_number'=>['required','numeric'],
            'address'=>['required','string'],
            'city'=>['required','string'],
            'postal_code'=>['required','numeric'],
            'country'=>['required','string']
        ]);
        $id = Auth::user()->id;

        $update = User::find($id);
        $update->name = $request->name;
        $update->mobile_no = $request->phone_number;
        $update->address = $request->address;
        $update->city = $request->city;
        $update->postal_code = $request->postal_code;
        $update->country = $request->country;
        $update->update();
        Session::flash('success', 'Profile Updated Successful.');
        return redirect()->route('subadmin.profile');
    }
    public function profile_image(Request $request)
    {
        $request->validate([
            'image'=> ['required','image','mimes:jpg,jpeg,png','max:500']
        ]);
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
        Session::flash('success', 'Profile Image Updated Successful.');
        return redirect()->route('subadmin.profile');
    }
    public function profile_password(Request $request)
    {
        $request->validate([
           'old_password'=>['required',Rules\Password::defaults()],
           'password'=>['required','confirmed',Rules\Password::defaults()],
           'password_confirmation'=>['required'],
        ]);
        $old = Auth::user()->password;
        if (Hash::check($request->old_password,$old))
        {
            $id = Auth::user()->id;
            $update = User::find($id);
            $update->password = Hash::make($request->password);
            $update->update();
            Session::flash('success', 'Password Updated Successful.');
            return redirect()->route('subadmin.profile');
        }
        else{
            Session::flash('danger', 'Old Password does not correct');
            return redirect()->route('subadmin.profile');
        }
    }

}
