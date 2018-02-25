<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PaymentSetupCompletedEvent;
use App\Models\ShopifyApi;
use App\Models\PaymentHandler;
use App\Models\InstallHandler;
use App\Models\App;
use App\Models\Store;
use App\Models\Asset;

class PaymentController extends Controller
{
    protected $api = null;

    function __construct(Request $request, App $app)
    {
        if(isset($request) && isset($app))
        {
            $store = Store::where("store_domain", $request->input("shop"))->first();
            $api = new ShopifyApi([
                "store" => $store,
                "app"   => $app
            ]);
            $this->api = $api;
        }
    }

    function deleteApp(App $app, Request $request)
    {
        $api = $this->api;

        $install_handler = new InstallHandler($api);
        $response = $install_handler->uninstallApp($app);
        dd($response);
    }

    function installApp(App $app, Request $request)
    {
        $api = $this->api;

        $install_handler = new InstallHandler($api);
        $response = $install_handler->installApp($app);
        dd($response);
    }

    function getAllPayments(App $app, Request $request)
    {
        $api = $this->api;

        $payment_handler = new PaymentHandler($api);
        $charges = $payment_handler->getAllRecurringCharges();

        dd($charges);
    }

    function callback(Request $request)
    {
        // Get variables from request
        $app_slug = $request->session()->get("app_slug");
        $store_id = $request->session()->get("store_id");
        $charge_id = $request->input("charge_id");

        $app = App::where("app_slug", $app_slug)->first();
        $store = Store::where("id", $store_id)->first();

        // Check if app and store are found
        if($app !== null && $store !== null)
        {
            // Create the ShopifyApi instance
            $api = new ShopifyApi([
                "app"   => $app,
                "store" => $store
            ]);
            $payment_handler = new PaymentHandler($api);

            $response = false;
            if($app->payment->payment_type == "recurring_charge")
            {
                // Check if payment is accepted or declined and activate it
                $response = $payment_handler->activateRecurringPayment($charge_id);
            }

            if($app->payment->payment_type == "one_time_charge")
            {
                // Check if payment is accepted or declined and activate it
                $response = $payment_handler->activateOneTimePayment($charge_id);
            }

            // Check if payment flow result is TRUE
            if($response == true)
            {
                // Finish the auth and payment process
                event(new PaymentSetupCompletedEvent($app, $store));
                // return redirect("https://" . $store->store_domain . "/admin/apps/" . $app->app_key);
                return redirect(config('app.url') . $app->app_admin_path);
            } else {
                throw new Exception("The install process failed, please retry later", 1);
            }
        }
    }
}
