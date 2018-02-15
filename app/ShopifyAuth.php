<?php
namespace App;

use PHPShopify;
use PHPShopify\AuthHelper;
use PHPShopify\ShopifySDK;
use Exception;

class ShopifyAuth
{
    public $configure = [];

    public $store_name = null;

    public $callback_url = null;

    public $scopes = [];

    public function __construct()
    {
        $this->addCallbackUrl();
    }

    public function forApp(App $app)
    {
        $this->configure["ApiKey"]       = $app->app_key;
        $this->configure["SharedSecret"] = $app->app_secret;
        $this->scopes = $this->parseScopeString($app->app_scopes);
        return $this;
    }

    public function withStoreUrl(String $store_url)
    {
        if(strpos($store_url, ".myshopify.com") == true)
        {
            $this->store_url = $store_url;
        }
        $this->configure['ShopUrl'] = $this->store_url;
        return $this;
    }

    public function addStoreName(String $store_name)
    {
        if(strpos($store_name, ".myshopify.com") == true)
        {
            $this->store_name = explode(".", $store_name)[0];
        } else {
            $this->store_name = $store_name;
        }
        return $this;
    }

    private function parseScopeString(String $scopes = null)
    {
        $result = [];
        if($scopes !== null)
        {
            $scopes = explode(",", $scopes);
            foreach($scopes as $scope)
            {
                array_push($result, trim($scope));
            }
        }
        return $result;
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

    public function getShopifyApi()
    {
        return ShopifySDK::config($this->configure);
    }

    public function api($data)
    {
        return ShopifySDK::config([
            "ShopUrl"     => $data["store_url"],
            "AccessToken" => $data["store_token"]
        ]);
    }

    public function getConfig()
    {
        return $this->configure;
    }

    private function constructCallbackUrl()
    {
        $config = ShopifySDK::$config;

        if(count($this->scopes) > 0)
        {
            $scopes = join(',', $this->scopes);
        } else {
            throw new Exception("The scopes are not set yet. Please provide some scopes for this request", 1);
        }

        if($config['AdminUrl'] == null)
        {
            throw new Exception("Admin url is not set in config", 1);
        }

        if($config['ApiKey'] == null)
        {
            throw new Exception("Api key is not set in config", 1);
        }

        if($this->callback_url == null)
        {
            throw new Exception("This callback url is not set in config", 1);
        }

        return $config['AdminUrl'] . 'oauth/authorize?client_id=' . $config['ApiKey'] . '&redirect_uri=' . $this->callback_url . "&scope=$scopes";
    }

    public function getCallbackUrl()
    {
        $api = $this->getShopifyApi();
        return $this->constructCallbackUrl();
    }

    public function retriveToken()
    {
        $api = $this->getShopifyApi();
        return AuthHelper::getAccessToken();
    }
}







    // public function receiveCallback()
    // {
    //     $this->getConfigWithoutToken();
    //     $token = PHPShopify\AuthHelper::getAccessToken();
    //     $this->saveNewShop($token);
    // }
    //
    // private function saveNewShop(String $token)
    // {
    //     if($this->shopIsUnique() == true)
    //     {
    //         $this->create([
    //             "shop_name"  => $this->shop_name,
    //             "shop_token" => $token
    //         ]);
    //     } else {
    //         $this->where("shop_name", "=", $this->shop_name)->update([
    //             "shop_token" => $token
    //         ]);
    //     }
    // }
    //
    // private function shopIsUnique()
    // {
    //     $shop = $this->getShopByName($this->shop_name);
    //     return ($shop == null) ? true : false;
    // }
    //
    // public function shops()
    // {
    //     return $this->selectAll();
    // }
    //
    // public function getShopByName(String $shop_name)
    // {
    //     return $this->where("shop_name", "=", $shop_name)->selectOne();
    // }
    //
    // public function getApi(String $shop_name)
    // {
    //     $shop = $this->getShopByName($shop_name);
    //     return $this->getConfigWithToken($shop->shop_token, $shop->shop_name);
    // }
    //
    // public function getMainThemeId(String $shop_name)
    // {
    //     $api = $this->getApi($shop_name);
    //     $themes = $api->Theme->get();
    //     foreach($themes as $theme)
    //     {
    //         if($theme["role"] == "main")
    //         {
    //             return $theme["id"];
    //         }
    //     }
    // }
