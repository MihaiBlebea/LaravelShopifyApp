<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopifyApi;
use App\Models\PaymentHandler;
use App\Models\App;
use App\Models\Store;

class PaymentController extends Controller
{
    function callback(Request $request)
    {
        // Get variables from request
        $app_slug = $request->session()->get("app_slug");
        $store_id = $request->session()->get("store_id");
        $charge_id = $request->input("charge_id");

        $app = App::where("app_slug", $app_slug)->first();
        $store = Store::where("id", $store_id)->first();

        if($app !== null && $store !== null)
        {
            $api = new ShopifyApi([
                "app"   => $app,
                "store" => $store
            ]);
            $payment_handler = new PaymentHandler($api);
            $response = $payment_handler->activateRecurringCharge($charge_id);
            dd($response);
        }
    }
}
