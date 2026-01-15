<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocal', $lang);
        }
        $id = Auth::user()->id;
        $lang_add = User::find($id);
        $lang_add->language = $lang;
        $lang_add->update();
        return Redirect::back();
    }
}
