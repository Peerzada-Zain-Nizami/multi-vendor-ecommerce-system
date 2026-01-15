<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Octw\Aramex\Aramex;
use App\Models\SMSAorder;
use SmsaSDK\Smsa;



class TestController extends Controller
{
    public function testRoute()
    {
        $passkey = "Sah@9640";
        dd($passkey);
        $AWB_no = 293011757961;
        $status = Smsa::getStatus($AWB_no, $passkey);
        $getstatus = $status->getGetStatusResult();
    }
}
