<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShopifyAuth;
use App\Store;

class ShopAuthController extends Controller
{
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
