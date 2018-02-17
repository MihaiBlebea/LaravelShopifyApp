<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\AuthIsCompletedEvent;
use App\Models\ShopifyApi;

class InstallAssets
{
    public function __construct()
    {
        //
    }

    public function handle(AuthIsCompletedEvent $event)
    {
        $api = new ShopifyApi([
            "app"   => $app,
            "store" => $store
        ]);
        $assets = $event->app->assets;

        foreach($assets as $index => $asset)
        {
            switch($asset->asset_type)
            {
                case "sections":
                    $response = $asset->install("sections", $api);
                    break;
                case "snippets":
                    $response = $asset->install("snippets", $api);
                    break;
                case "assets":
                    $response = $asset->install("assets", $api);
                    break;
                default:
                    throw new Exception("Type of asset is not known", 1);
            }
        }
    }
}
