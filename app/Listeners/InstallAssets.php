<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\PaymentSetupCompletedEvent;
use App\Models\ShopifyApi;

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
        $assets = $event->app->assets;

        foreach($assets as $index => $asset)
        {
            switch($asset->asset_type)
            {
                case "sections":
                    $response = $asset->install($api);
                    break;
                case "snippets":
                    $response = $asset->install($api);
                    break;
                case "assets":
                    $response = $asset->install($api);
                    break;
                default:
                    throw new Exception("Type of asset is not known", 1);
            }
        }
    }
}
