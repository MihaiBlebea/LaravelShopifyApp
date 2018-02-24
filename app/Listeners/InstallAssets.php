<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\PaymentSetupCompletedEvent;
use App\Models\ShopifyApi;
use App\Models\InstallHandler;

class InstallAssets
{
    public function __construct()
    {
        //
    }

    public function handle(PaymentSetupCompletedEvent $event)
    {
        $api = new ShopifyApi([
            "app"   => $event->app,
            "store" => $event->store
        ]);

        $install_handler = new InstallHandler($api);
        $response = $install_handler->installApp($event->app);
    }
}
