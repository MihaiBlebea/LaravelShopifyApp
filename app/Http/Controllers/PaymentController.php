<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopifyApi;

class PaymentController extends Controller
{
    function callback(Request $request)
    {
        dd($request);
    }
}
