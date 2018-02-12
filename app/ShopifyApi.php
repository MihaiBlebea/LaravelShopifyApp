<?php
namespace App;

use PHPShopify;
use PHPShopify\AuthHelper;
use PHPShopify\ShopifySDK;
use Exception;

class ShopifyApi
{
    public $configure = [];

    public $store_name = null;

    public $callback_url = null;

    public $scopes = [];

    public function __construct()
    {
        $this->configure["ApiKey"] = config('app.api_key');
        $this->configure["SharedSecret"] = config('app.shared_secret');
    }

    public function addStoreName(String $store_name)
    {
        $this->store_name = $store_name;
        return $this;
    }

    public function addStoreUrl(String $store_url = null)
    {
        if($store_url !== null)
        {
            $this->configure["ShopUrl"] = $store_url;
        } elseif($this->store_name !== null) {
            $this->configure["ShopUrl"] = $this->store_name . ".myshopify.com";
        } else {
            throw new Exception("Shop url could not be found", 1);
        }
        return $this;
    }

    public function addCallbackUrl(String $url = null)
    {
        if($url !== null)
        {
            $this->callback_url = $url;
        } elseif(config('app.callback_url') !== null) {
            $this->callback_url = config('app.url') . config('app.callback_url');
        } else {
            throw new Exception("Callback url for auth was not found", 1);
        }
        return $this;
    }

    public function addToken(String $token)
    {
        $this->configure["AccessToken"] = $token;
        return $this;
    }

    public function addScopes(Array $scopes = null)
    {
        if($scopes !== null)
        {
            $this->scopes = $scopes;
        } elseif(config('app.scopes') !== null) {
            $this->scopes = explode("|", config('app.scopes'));
        } else {
            throw new Exception("The scopes for this app were not found", 1);
        }
        return $this;
    }

    public function getShopifyApi()
    {
        return ShopifySDK::config($this->configure);
    }

    public function getConfig()
    {
        return $this->configure;
    }

    public function navigateToCallback()
    {
        $this->getShopifyApi();
        if(count($this->scopes) > 0 && $this->callback_url !== null)
        {
            return AuthHelper::createAuthRequest($this->scopes, $this->callback_url);
        }
    }






    public function receiveCallback()
    {
        $this->getConfigWithoutToken();
        $token = PHPShopify\AuthHelper::getAccessToken();
        $this->saveNewShop($token);
    }

    private function saveNewShop(String $token)
    {
        if($this->shopIsUnique() == true)
        {
            $this->create([
                "shop_name"  => $this->shop_name,
                "shop_token" => $token
            ]);
        } else {
            $this->where("shop_name", "=", $this->shop_name)->update([
                "shop_token" => $token
            ]);
        }
    }

    private function shopIsUnique()
    {
        $shop = $this->getShopByName($this->shop_name);
        return ($shop == null) ? true : false;
    }

    public function shops()
    {
        return $this->selectAll();
    }

    public function getShopByName(String $shop_name)
    {
        return $this->where("shop_name", "=", $shop_name)->selectOne();
    }

    public function getApi(String $shop_name)
    {
        $shop = $this->getShopByName($shop_name);
        return $this->getConfigWithToken($shop->shop_token, $shop->shop_name);
    }

    public function getMainThemeId(String $shop_name)
    {
        $api = $this->getApi($shop_name);
        $themes = $api->Theme->get();
        foreach($themes as $theme)
        {
            if($theme["role"] == "main")
            {
                return $theme["id"];
            }
        }
    }
}
