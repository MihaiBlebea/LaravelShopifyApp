<?php

Route::prefix("install")->group(function () {
    Route::get("/{app}", "ShopInstallController@auth");
    Route::get("/auth/callback", "ShopInstallController@authCallback")->name("auth.callback");
    Route::get("/payment/callback", "ShopInstallController@paymentCallback")->name("payment.callback");
    Route::get("/{store}/{app}", "ShopInstallController@installCallback")->name("install.callback");
});

Route::prefix("payment")->group(function () {
    Route::get("/all/{app}", "PaymentController@getAllPayments");
});

Route::get("/index", "TestController@index");
