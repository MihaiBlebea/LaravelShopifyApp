<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShopifyApi;

class ShopAuthController extends Controller
{
    function auth()
    {
        header("Location: http://www.slabestecuserban.ro");
        $api = new ShopifyApi();
        $api->addStoreName("mihaidev")
            ->addStoreUrl()
            ->addCallbackUrl()
            ->addScopes()
            ->navigateToCallback();
        dd($api);
    }

    function callback()
    {
        dd("ceva");
    }
}
