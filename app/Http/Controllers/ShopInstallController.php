<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\App;
use App\Models\PaymentHandler;
use App\Models\InstallHandler;
use PHPShopify\ShopifySDK;
use PHPShopify\AuthHelper;
use Exception;
use Log;


class ShopInstallController extends Controller
{
    function auth(App $app, Request $request)
    {
        $config = [
            "ShopUrl"      => $request->input("shop"),
            "ApiKey"       => $app->app_key,
            "SharedSecret" => $app->app_secret,
        ];
        ShopifySDK::config($config);

        /*
         * The redirect url must be white listed from your app admin
         * as one of Application Redirect URLs.
         */
        $redirect_url = route("auth.callback");
        $auth_callback_url = AuthHelper::createAuthRequest($app->app_scopes, $redirect_url);

        /*
         * Redirect to the auth_callback_url witch will redirect to the auth.callback route
         * Pass the app_slug as the session
         */
        return redirect($auth_callback_url)
                ->with("app_slug", $app->app_slug);
    }

    function authCallback(Request $request)
    {
        /*
         * Get back the app_slug from session and find the app object
         */
        $app = App::where("app_slug", $request->session()->get("app_slug"))->first();
        if($app)
        {
            /*
             * Create a new config and pass it to AuthHelper
             * Then request the token with callback
             */
            $config = [
                "ShopUrl"      => $request->input("shop"),
                "ApiKey"       => $app->app_key,
                "SharedSecret" => $app->app_secret,
            ];
            ShopifySDK::config($config);
            $access_token = AuthHelper::getAccessToken();

            // TODO Check if the app is already installed by this store and if true then update
            // Check if the store already bought the app
            // $store = Store::where("store_domain", $request->input("shop"))->first();
            // if($store !== null && $store->hasApp($app) == true)
            // {
            //     throw new Exception("This store already has this app", 1);
            //     die();
            // }

            /*
             * Create a new config and pass it to AuthHelper
             * This time use the token from the database
             */
            $config = [
                "ShopUrl"     => $request->input("shop"),
                "AccessToken" => $access_token,
            ];
            $shopify = new ShopifySDK($config);

            /*
             * Use the shopify API to store info about the store
             * who installed the app in the database
             */
            $shop_details = $shopify->Shop->get();
            if($shop_details)
            {
                $store = Store::create([
                    "store_token"  => $access_token,
                    "store_domain" => $request->input("shop"),
                    "store_name"   => $shop_details["name"],
                    "store_plan"   => $shop_details["plan_name"],
                    "store_owner"  => $shop_details["shop_owner"],
                    "store_email"  => $shop_details["email"],
                    "store_phone"  => $shop_details["phone"]
                ]);
                $store->updateAppStoreTable($app);
            }

            /*
             * Store is all set up by now.
             * Time to start the payment setup flow
             */
            $payment = $app->payment;
            $payment_handler = new PaymentHandler($shopify);
            $payment_callback_url = $payment_handler->createCharge($payment);

            /*
             * Trigger AuthSetupCompletedEvent and send notifications
             */
            Log::info("Auth process finalized between app " . $app->app_name . " and store " . $store->store_name);

            /*
             * Use the callback to obtain the users accept on the payment
             * Send the app_slug and the store_domain with the request throught the session
             */
            return redirect($payment_callback_url)
                    ->with("app_slug", $app->app_slug)
                    ->with("store_domain", $store->store_domain);
        } else {
            throw new Exception("Requested app not found in database, check the session", 1);
        }
    }

    function paymentCallback(Request $request)
    {
        /*
         * Get the app_slug, store_domain and charge_id from session
         * Get the App and Store objects so they can be used in the payment flow
         */
        $app_slug     = $request->session()->get("app_slug");
        $store_domain = $request->session()->get("store_domain");
        $charge_id    = $request->input("charge_id");

        $app = App::where("app_slug", $app_slug)->first();
        $store = Store::where("store_domain", $store_domain)->first();

        // Check if App, Store and charge_id are not null
        if($app !== null && $store !== null && $charge_id !== null)
        {
            $shopify = new ShopifySDK([
                "ShopUrl"     => $store->store_domain,
                "AccessToken" => $store->store_token
            ]);
            $payment_handler = new PaymentHandler($shopify);
            $response = $payment_handler->activateCharge($app, $charge_id);

            // Check if payment flow result is TRUE
            if($response == true)
            {
                /*
                 * Trigger AuthSetupCompletedEvent and send notifications
                 */
                Log::info("Payment process finalized between app " . $app->app_name . " and store " . $store->store_name);

                $install_callback = route("install.callback", [
                    "app"   => $app->app_slug,
                    "store" => $store->id
                ]);

                return redirect($install_callback);
            } else {
                throw new Exception("The install process failed, please retry later", 1);
            }
        }
    }

    function installCallback(Store $store, App $app)
    {
        $shopify = new ShopifySDK([
            "ShopUrl"     => $store->store_domain,
            "AccessToken" => $store->store_token
        ]);

        $install_handler = new InstallHandler($shopify);
        $response = $install_handler->installApp($app);

        if($response == true)
        {
            /*
             * Trigger AuthSetupCompletedEvent and send notifications
             */
            Log::info("Install assets process finalized between app " . $app->app_name . " and store " . $store->store_name);

            /*
             * Return user to the admin section when all flow is completed
             */
            // return redirect();
        } else {
            throw new Exception("Assets could not be installed at this time", 1);
        }
    }
}
