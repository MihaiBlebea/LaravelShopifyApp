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
        $api = new ShopifyApi([
            "ApiKey"       => $app->app_key,
            "SharedSecret" => $app->app_secret,
            "ShopUrl"      => $request->input("store"),
            "scopes"       => $app->app_scopes
        ]);
        $callback_url = $api->getCallbackUrl();

        // Redirect to callback
        return redirect($callback_url)->with("app_slug", $app->app_slug);
    }

    function callback(Request $request)
    {
        // Retrive the app by app slug
        $app = App::where("app_slug", $request->session()->get("app_slug"))->first();

        if($app !== null)
        {
            // Retrive the token from the callback
            $api = new ShopifyApi([
                "ApiKey"       => $app->app_key,
                "SharedSecret" => $app->app_secret,
                "ShopUrl"      => $request->input("shop"),
                "scopes"       => $app->app_scopes
            ]);
            $token = $api->retriveToken();

            // CHeck if the store already bought the app
            $store = Store::where("store_domain", $request->input("shop"))->first();
            if($store !== null && $store->hasApp($app) == true)
            {
                throw new Exception("This store already has this app", 1);
                die();
            }

            // Constrct new api object with token
            $api = new ShopifyApi([
                "ShopUrl"     => $request->input("shop"),
                "AccessToken" => $token,
                "scopes"      => $app->app_scopes
            ]);
            $store = Store::storeNewToken($api, [
                "store_url" => $request->input("shop"),
                "store_token" => $token
            ]);
            $store->updatePivotTable($app);

            // Set up payment flow
            $payment = $app->payment;
            $payment_handler = new PaymentHandler($api);
            $payment_callback_url = $payment_handler->charge($payment);

            //
            // Dispach app installed event
            //TODO Move event trigger to the end of the auth flow
            // event(new AuthIsCompletedEvent($app, $store));

            // Return user to the admin panel of his store
            return redirect($payment_callback_url)
                    ->with("app_slug", $app->app_slug)
                    ->with("store_id", $store->id);
            // return redirect("https://" . $request->input("shop") . "/admin/apps");
        } else {
            throw new Exception("requested app not found in database", 1);
        }
    }
}
