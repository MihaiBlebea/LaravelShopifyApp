<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShopifyAuth;
use App\Store;
use App\App;
use App\Asset;

class ShopAuthController extends Controller
{
    // function test()
    // {
    //     $apps = Store::find(1)->apps()->get();
    //     // $assets = App::find(1)->assets()->get();
    //     // $app = Asset::find(1)->app()->get();
    //     dd($apps);
    // }

    function auth(App $app, Request $request)
    {
        // Get auth callback url
        $api = new ShopifyAuth();
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
        $app = App::where('app_slug', $request->session()->get("app_slug"))->first();

        // Retrive the token from the callback
        $api = new ShopifyAuth();
        $token = $api->forApp($app)
                     ->withStoreUrl($request->input("shop"))
                     ->retriveToken();

        // Save data to Store model
        $store = new Store();
        $store->storeNewToken($api, [
            "store_url" => $request->input("shop"),
            "store_token" => $token
        ]);

        // TODO check if data was really stored in the database
        return redirect("https://" . $request->input("shop") . "/admin/apps");
    }
}
