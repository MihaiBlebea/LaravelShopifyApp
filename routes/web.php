<?php

Route::prefix("install")->group(function () {
    Route::get("/{app}", "ShopInstallController@auth");
    Route::get("/auth/callback", "ShopInstallController@authCallback")->name("auth.callback");
    Route::get("/payment/callback", "ShopInstallController@paymentCallback")->name("payment.callback");
    Route::get("/{store}/{app}", "ShopInstallController@installCallback")->name("install.callback");
});

Route::prefix("proxy")->group(function () {
    Route::get("index", "ProxyController@index");
    Route::get("files/{file_type}/{file_name}", "ProxyController@getFile");

    Route::post("settings", "ProxyController@storeSettings");
    Route::get("settings", "ProxyController@getSettings");
});

Route::prefix("payment")->group(function () {
    Route::get("/all/{app}", "PaymentController@getAllPayments");
});

Route::get("/index", function() {
    dd("works");
});
