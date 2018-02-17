<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\AuthIsCompletedEvent;
use App\Models\ShopifyApi;
use App\Models\Store;
use App\Models\App;
use App\Models\Asset;
use App\Models\PaymentHandler;
use Exception;

class ShopAuthController extends Controller
{
    function auth(App $app, Request $request)
    {
        // Get auth callback url
        $api = new ShopifyApi();
        $callback_url = $api->forApp($app)
                            ->withStoreUrl($request->input("store"))
                            ->getCallbackUrl();

        // Store app slug in session and retrive it in callback
        session(["app_slug" => $app->app_slug]);

        // Redirect to callback
        return redirect($callback_url);
    }

    function callback(Request $request)
    {
        // Retrive the app by app slug
        $app = App::where("app_slug", $request->session()->get("app_slug"))->first();

        // Retrive the token from the callback
        $api = new ShopifyApi();
        $token = $api->forApp($app)
                     ->withStoreUrl($request->input("shop"))
                     ->retriveToken();

        // CHeck if the store already bought the app
        $store = Store::where("store_domain", $request->input("shop"))->first();
        if($store !== null && $store->hasApp($app) == true)
        {
            throw new Exception("This store already has this app", 1);
            die();
        }

        $store = Store::storeNewToken($api, [
            "store_url" => $request->input("shop"),
            "store_token" => $token
        ]);
        $store->updatePivotTable($app);

        // Set up payment flow
        $payment = $app->payment;
        $payment_handler = new PaymentHandler($api);
        $payment_callback_url = $payment_handler->figureType($payment);

        //
        // Dispach app installed event
        //TODO Move event trigger to the end of the auth flow
        // event(new AuthIsCompletedEvent($app, $store));

        // Return user to the admin panel of his store
        return redirect($payment_callback_url)->with("app_slug", $app->app_slug);
        // return redirect("https://" . $request->input("shop") . "/admin/apps");
    }
}
