<?php

Route::name("")->group(function () {
});

Route::prefix("payment")->name("payment")->group(function () {
    Route::get("/callback", "PaymentController@callback");
});

Route::get("/test", "ShopAuthController@test");
Route::get("/shopify/{app}", "ShopAuthController@auth");
Route::get("/callback", "ShopAuthController@callback");
