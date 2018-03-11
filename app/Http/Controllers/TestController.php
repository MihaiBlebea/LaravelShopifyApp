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
        dd("ceva");
    }
}
