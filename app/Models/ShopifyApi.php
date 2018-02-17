<?php
namespace App\Models;

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
        $this->addCallbackUrl();
    }

    public function forApp(App $app)
    {
        $this->configure["ApiKey"]       = $app->app_key;
        $this->configure["SharedSecret"] = $app->app_secret;
        $this->scopes = $this->parseScopeString($app->app_scopes);
        return $this;
    }

    public function forStore(Store $store)
    {
        $this->configure['ShopUrl'] = $store->store_domain;
        $this->configure["AccessToken"] = $store->store_token;
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

    public function getApi()
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
        $api = $this->getApi();
        return $this->constructCallbackUrl();
    }

    public function retriveToken()
    {
        $api = $this->getApi();
        return AuthHelper::getAccessToken();
    }
}
