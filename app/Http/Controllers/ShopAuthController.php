<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\AuthIsCompletedEvent;
use App\ShopifyApi;
use App\Store;
use App\App;
use App\Asset;
use Exception;

class ShopAuthController extends Controller
{
    function test()
    {
        $app = App::find(1);
        $store = Store::find(3);
        $api = new ShopifyApi();
        $api = $api->forApp($app)->forStore($store);
        // Asset::installSection($api);

        $assets = $app->assets;

        foreach($assets as $index => $asset)
        {
            switch($asset->asset_type)
            {
                case "sections":
                    $response = $asset->install("sections", $api);
                    break;
                case "snippets":
                    $response = $asset->install("snippets", $api);
                    break;
                case "assets":
                    $response = $asset->install("assets", $api);
                    break;
                default:
                    throw new Exception("Type of asset is not known", 1);
            }
            echo $asset->asset_type;
        }
    }

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

        // Dispach app installed event
        event(new AuthIsCompletedEvent($app, $store));

        // Return user to the admin panel of his store
        return redirect("https://" . $request->input("shop") . "/admin/apps");
    }
}
