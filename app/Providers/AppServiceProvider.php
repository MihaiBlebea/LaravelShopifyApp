<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PHPShopify\ShopifySDK;
use PHPShopify\AuthHelper;
use PHPShopify\Interfaces\ShopifySDKInterface;
use PHPShopify\Interfaces\AuthHelperInterface;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        ShopifySDKInterface::class => ShopifySDK::class,
    ];

    public $singletons = [
        AuthHelperInterface::class => AuthHelper::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
