<?php

namespace App\Http\Controllers\Subadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SubadminController extends Controller
{
    public function index()
    {
        return view('Subadmin.index');
    }

}
