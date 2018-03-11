<?php

namespace App\Interfaces;

use PHPShopify\Interfaces\ShopifySDKInterface;
use App\Models\App;

interface InstallInterface
{
    public function __construct(ShopifySDKInterface $api);

    public function installApp(App $app);

    public function getAsset(String $theme_id, String $key);

    public function uninstallApp(App $app);
}
