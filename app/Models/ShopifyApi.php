<?php
namespace App\Models;

use PHPShopify;
use App\Interfaces\ApiInterface;
use PHPShopify\ShopifySDK;
use App\Traits\GetApi;
use Exception;

class ShopifyApi implements ApiInterface
{
    use GetApi;

    public $configure = [];

    public function __construct(Array $data)
    {
        if(isset($data["app"]) && isset($data["store"]))
        {
            $this->configure["ApiKey"]       = $data["app"]->app_key;
            $this->configure["SharedSecret"] = $data["app"]->app_secret;
            $this->configure["ShopUrl"]      = $data["store"]->store_domain;
            $this->configure["AccessToken"]  = $data["store"]->store_token;

        } else {
            throw new Exception("This object could not be constructed, check config values", 1);
        }
    }
}
