<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\App;
use App\Models\Store;
use App\Models\ShopifyApi;
use App\Models\InstallHandler;

class TestController extends Controller
{
    function index(Request $request, App $app)
    {
        $store = Store::where("store_domain", $request->input("shop"))->first();
        $api = new ShopifyApi($store);
        
        $install_handler = new InstallHandler($api);
        $install_handler->installApp($app);
    }
}
