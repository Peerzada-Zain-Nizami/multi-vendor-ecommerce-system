<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Seller_lang_setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;


class SellerLangSetupController extends Controller
{
    public function language_setup_view()
    {
        $id = Auth::user()->id;
        $data = Seller_lang_setup::where('user_id',$id)->first();
        return view('Seller.lang_setup',compact('data'));
    }
    public function lang_setup_add(Request $request)
    {
        $request->validate([
            'language'=> ['required'],
        ]);
        $user = Auth::user()->id;
        $db = new Seller_lang_setup();
        $db->user_id = $user;
        $db->language = $request->language;
        $db->save();
        Session::flash('success', 'Language has been successfully added.');
        return redirect()->route('seller.language.setup');
    }
    public function lang_setup_update(Request $request,$id)
    {
        $request->validate([
            'language'=> ['required'],
        ]);
        $db = Seller_lang_setup::where('id',$id)->first();
        $db->language = $request->language;
        $db->update();
        Session::flash('success', 'Language has been successfully Updated.');
        return redirect()->route('seller.language.setup');
    }
}
