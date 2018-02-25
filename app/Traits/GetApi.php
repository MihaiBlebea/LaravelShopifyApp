<?php

namespace App\Traits;

use PHPShopify\ShopifySDK;

trait GetApi
{
    public function getApi()
    {
        return ShopifySDK::config($this->configure);
    }
}
