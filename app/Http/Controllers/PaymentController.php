<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PaymentSetupCompletedEvent;
use App\Models\ShopifyApi;
use App\Models\PaymentHandler;
use App\Models\App;
use App\Models\Store;
use App\Models\Asset;

class PaymentController extends Controller
{
    function deleteAsset(App $app, Request $request)
    {
        $store = Store::where("store_domain", $request->input("store"))->first();
        $api = new ShopifyApi([
            "store" => $store,
            "app"   => $app
        ]);

        $asset = new Asset();
        $response = Asset::deleteAssets($api, "locales/pt-PT.json");
        dd($response);
    }

    function getAllPayments(App $app, Request $request)
    {
        $store = Store::where("store_domain", $request->input("store"))->first();
        $api = new ShopifyApi([
            "store" => $store,
            "app"   => $app
        ]);
        $payment_handler = new PaymentHandler($api);
        $charges = $payment_handler->getAllRecurringCharges();

        // foreach($charges as $charge)
        // {
        //     $payment_handler->removePayment($charge["id"]);
        // }
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
