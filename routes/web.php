<?php

Route::name("")->group(function () {
});

Route::get("/test", "ShopAuthController@test");
Route::get("/shopify/{app}", "ShopAuthController@auth");
Route::get("/callback", "ShopAuthController@callback");
