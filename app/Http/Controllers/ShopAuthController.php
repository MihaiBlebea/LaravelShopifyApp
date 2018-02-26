<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopifyApi;
use App\Models\AuthHandler;
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
        $api = new AuthHandler([
            "ApiKey"       => $app->app_key,
            "SharedSecret" => $app->app_secret,
            "ShopUrl"      => $request->input("shop"),
            "scopes"       => $app->app_scopes
        ]);
        $callback_url = $api->getCallbackUrl();

        // Redirect to callback
        return redirect($callback_url)
                ->with("app_slug", $app->app_slug);
    }

    function authCallback(Request $request)
    {
        // Retrive the app by app slug
        $app = App::where("app_slug", $request->session()->get("app_slug"))->first();

        if($app !== null)
        {
            // Retrive the token from the callback
            $api = new AuthHandler([
                "ApiKey"       => $app->app_key,
                "SharedSecret" => $app->app_secret,
                "ShopUrl"      => $request->input("shop"),
                "scopes"       => $app->app_scopes
            ]);
            $token = $api->retriveToken();
            
            // Check if the store already bought the app
            $store = Store::where("store_domain", $request->input("shop"))->first();
            if($store !== null && $store->hasApp($app) == true)
            {
                throw new Exception("This store already has this app", 1);
                die();
            }

            // Constrct new api object with token
            $api = new AuthHandler([
                "ShopUrl"     => $request->input("shop"),
                "AccessToken" => $token,
                "scopes"      => $app->app_scopes
            ]);

            //Store token in database and update the pivot table
            $store = Store::storeNewToken($api, [
                "store_url" => $request->input("shop"),
                "store_token" => $token
            ]);
            $store->updatePivotTable($app);

            // Set up payment flow
            $payment = $app->payment;
            $payment_handler = new PaymentHandler($api);
            $payment_callback_url = $payment_handler->createCharge($payment);

            // Redirect user to the payment activation
            return redirect($payment_callback_url)
                    ->with("app_slug", $app->app_slug)
                    ->with("store_domain", $store->store_domain);
        } else {
            throw new Exception("Requested app not found in database", 1);
        }
    }
}
