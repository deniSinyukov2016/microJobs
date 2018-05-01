<?php

namespace App\Http\Controllers;

use App\Models\Billing\Payouts;
use App\Models\Jobs\Applications;
use App\User;
use Exception;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Payer can initiate a charge from self to user
     * User acts as the merchant
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function charge(Request $request)
    {
        $app = Applications::findOrFail($request->application_id);
        $user = User::findOrFail($app->user_id);

        if(empty($user->stripe_secret_key)){
            flash()->error(__('payments.stripe-key-error'));
            return redirect()->back();
        }
        \Stripe\Stripe::setApiKey($user->stripe_secret_key);

        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => bcmul($app->job_price,100),
                "currency" => env('CURRENCY'),
                "description" => $app->job_name,
                "source" => $request->stripeToken,
            ));

        } catch (Exception $e) {
            flash()->error($e->getMessage());
            return redirect()->back();
        }

        //save transaction
        $pay = new Payouts();
        $pay->pay_method = 'Stripe';
        $pay->txn_id = $charge['id'];
        $pay->amount = $app->job_id;
        $pay->currency = env('CURRENCY');
        $pay->item_name = $app->job->name;
        $pay->item_number=
        $pay->user_id = $app->user_id;
        $pay->application_id = $app->id;
        $pay->save();

        $app->status ='close';
        $app->save();

        flash()->success(__("Payment processed successfully"));
        return redirect()->back();

    }

    /**
     * Payee can initiate a refund
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function refund(Request $request)
    {
        $app = Applications::findOrFail($request->application_id);
        $user = User::findOrFail($app->user_id);

        if(empty($user->stripe_secret_key)){
            flash()->error(__('payments.stripe-key-error'));
            return redirect()->back();
        }
        \Stripe\Stripe::setApiKey($user->stripe_secret_key);

        try {
            //todo
        } catch (Exception $e) {
            flash()->error(__('Error processing refund'));
            return redirect()->back();
        }
    }
}
