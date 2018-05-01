<?php

namespace App\Http\Controllers;

use App\Models\Billing\Payouts;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-payouts',['only'=>['payouts']]);
    }
    function payouts(){
        $payouts = Payouts::get();
        return view('billing.payouts',compact('payouts'));
    }
}
