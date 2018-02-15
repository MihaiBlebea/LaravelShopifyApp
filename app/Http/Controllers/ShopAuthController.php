<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShopifyAuth;
use App\Store;
use App\App;
use App\Asset;

class ShopAuthController extends Controller
{
    function test()
    {
        // $apps = Store::find(1)->apps()->get();
        // $assets = App::find(1)->assets()->get();
        $app = Asset::find(1)->app()->get();
        dd($app);
    }

    function auth()
    {
        $api = new ShopifyAuth();
        $callback_url = $api->addStoreName("mihaidev")
                            ->addStoreUrl()
                            ->addCallbackUrl()
                            ->addScopes()
                            ->getCallbackUrl();
        return redirect($callback_url);
    }

    function callback(Request $request)
    {
        $api = new ShopifyAuth();
        $token = $api->addStoreName($request->input('shop'))
                     ->addStoreUrl()
                     ->addCallbackUrl()
                     ->addScopes()
                     ->retriveToken();

        Store::storeToken([
            "app_id"      => 2,
            "store_name"  => "Serban",
            "store_email" => "slabestecuserban@gmail.com",
            "store_token" => $token
        ]);
    }
}
