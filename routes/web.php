<?php

Route::middleware("frame")->prefix("auth")->group(function () {
    Route::get("/shopify/{app}", "ShopAuthController@auth");
    Route::get("/callback", "ShopAuthController@authCallback")->name("auth.callback");
});

Route::prefix("payment")->group(function () {
    Route::get("/callback", "PaymentController@paymentCallback");
    Route::get("/all/{app}", "PaymentController@getAllPayments");
});

Route::get("/index", "PaymentController@index");
Route::get("/test", "ShopAuthController@test");
Route::get("/delete/{app}", "PaymentController@deleteApp");
Route::get("/install/{app}", "PaymentController@installApp");
