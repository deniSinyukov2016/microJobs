<?php

namespace App\Http\Controllers;

use App\Models\Billing\Payouts;
use App\Models\Jobs\Applications;
use App\Models\Jobs\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class PaypalController extends Controller
{
    private $paypalURL;
    private $txn_verify_url;
    private $returnURL;
    private $cancelURL;
    private $notifyURL;
    private $paypalContext;

    function __construct()
    {
        $this->middleware('auth', ['only' => ['pay']]);

        if (env('PAYPAL_MODE') == 'sandbox') {
            $this->paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            $this->txn_verify_url = 'ssl://www.sandbox.paypal.com';
        } else {
            $this->paypalURL = 'https://www.paypal.com/cgi-bin/webscr';
            $this->txn_verify_url = 'ssl://www.paypal.com';
        }

        $this->returnURL = url()->to('paypal/success');
        $this->cancelURL = url()->to('paypal/cancelled');
        $this->notifyURL = url()->to('paypal/notify');
        $this->paypalContext = array(
            'lc' => env('PAYPAL_LOCALE'),
            'currency_code' => env('CURRENCY'),
            'no_note' => 1,
            'cmd' => '_xclick',
            'btn' => 'PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest'
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function pay(Request $request)
    {
        $job = Jobs::find($request->job_id);
        $application = Applications::find($request->app_id);

        $item_amount = $application->job_price;
        $paypal_email = $application->user->email;

        $querystring = "?business=" . urlencode($paypal_email) . "&";
        $querystring .= "item_name=" . urlencode($job->name) . "&";
        $querystring .= "item_number=" . urlencode('app_' . $application->id . '_job_' . $job->id) . "&";
        $querystring .= "amount=" . urlencode($item_amount) . "&";
        $querystring .= "payer_email=" . urlencode(Auth::user()->email) . "&";
        $querystring .= "name=" . urlencode(Auth::user()->name) . "&";
        $querystring .= "rm=" . urlencode(2) . "&";

        foreach ($this->paypalContext as $key => $value) {
            $value = urlencode(stripslashes($value));
            $querystring .= "$key=$value&";
        }
        $querystring .= "return=" . urlencode(stripslashes($this->returnURL)) . "&";
        $querystring .= "cancel_return=" . urlencode(stripslashes($this->cancelURL)) . "&";
        $querystring .= "notify_url=" . urlencode($this->notifyURL);
        $querystring .= "&custom=" . $application->user_id . '|' . $application->id;

        return redirect($this->paypalURL . $querystring);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function success(Request $request)
    {
        if ($request->has('custom'))
            $ids = explode('|', $request->custom);
        else
            $ids = explode('|', $request->cm);

        $application = Applications::find($ids[1]);
        $application->status = 'closed';
        $application->save();


        if ($request->has('txn_id'))
            $tx = $request->txn_id;
        else
            $tx = $request->tx;

        if ($request->has('business'))
            $business = $request->business;
        else
            $business = $application->user->email;

        if($request->has('mc_currency'))
            $currency = $request->mc_currency;
        else
            $currency = $request->cc;

        if($request->has('amt'))
            $amount = $request->amt;
        else
            $amount= $request->mc_gross;

        $data = array(
            'pay_method' => 'paypal',
            'txn_id' => $tx,
            'amount' => $amount,
            'currency' => $currency,
            'item_name' => $request->item_name,
            'item_number' => $request->item_number,
            'user_id' => $ids[0],
            'payment_email' => $business,
            'paypal_verify_sign' => $request->verify_sign,
            'application_id' => (int)$ids[1]
        );
        Payouts::create($data);

        $application->notifyOnChangeStatus($application);

        flash()->success('Payment has been processed successfully');
        return redirect('/job/' . $application->job_id . '/' . $application->id . '/work');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function cancelled(Request $request)
    {
        return view('billing.paypal-cancelled');
    }

    /**
     * @param Request $request
     * @return string
     */
    function notify(Request $request)
    {
        $ids = explode('|', $request->custom);

        //use IPN only if user failed to click return url
        $txn = Payouts::where('txn_id', $request->txn_id)->first();
        if (count($txn) == 0) {
            $data = array(
                'pay_method' => 'paypal',
                'txn_id' => $request->txn_id,
                'amount' => $request->mc_gross,
                'currency' => $request->mc_currency,
                'item_name' => $request->item_name,
                'item_number' => $request->item_number,
                'user_id' => $ids[0],
                'payment_email' => $request->business,
                'paypal_verify_sign' => $request->verify_sign,
                'application_id' => $ids[1]
            );
            Payouts::create($data);
        }

        $application = Applications::find($ids[1]);
        $application->status = 'closed';
        $application->save();

        $application->notifyOnChangeStatus($application);
        return 'success';

    }
}
