<?php

Route::middleware("frame")->prefix("auth")->group(function () {
    Route::get("/shopify/{app}", "ShopAuthController@auth");
    Route::get("/callback", "ShopAuthController@callback")->name("auth.callback");
});

Route::prefix("payment")->group(function () {
    Route::get("/callback", "PaymentController@callback");
    Route::get("/all/{app}", "PaymentController@getAllPayments");
});

Route::get("/test", "ShopAuthController@test");
Route::get("/delete/{app}", "PaymentController@deleteApp");
Route::get("/install/{app}", "PaymentController@installApp");
