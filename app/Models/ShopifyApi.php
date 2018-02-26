<?php
namespace App\Models;

use PHPShopify;
use App\Interfaces\ApiInterface;
use PHPShopify\ShopifySDK;
use App\Models\Store;
use App\Traits\GetApi;
use Exception;

class ShopifyApi implements ApiInterface
{
    use GetApi;

    public $configure = [];

    public function __construct(Store $store)
    {
        $this->configure["ShopUrl"]      = $store->store_domain;
        $this->configure["AccessToken"]  = $store->store_token;
    }
}
