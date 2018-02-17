<?php
namespace App\Models;

use PHPShopify;
use App\Interfaces\AuthInterface;
use PHPShopify\AuthHelper;
use PHPShopify\ShopifySDK;
use Exception;

class ShopifyApi implements AuthInterface
{
    public $configure = [];

    public $callback_url = null;

    public $scopes = [];

    public function __construct(Array $data)
    {
        if(isset($data["app"]) && isset($data["store"]))
        {
            $this->configure["ApiKey"]       = $data["app"]->app_key;
            $this->configure["SharedSecret"] = $data["app"]->app_secret;
            $this->configure["ShopUrl"]      = $data["store"]->store_domain;
            $this->configure["AccessToken"]  = $data["store"]->store_token;
            $this->scopes = $this->parseScopeString($data["app"]->app_scopes);

        } elseif(isset($data["ApiKey"]) &&
                 isset($data["SharedSecret"]) &&
                 isset($data["ShopUrl"]) &&
                 isset($data["scopes"])) {

            $this->configure["ApiKey"]       = $data["ApiKey"];
            $this->configure["SharedSecret"] = $data["SharedSecret"];
            $this->configure["ShopUrl"]      = $data["ShopUrl"];
            $this->scopes = $this->parseScopeString($data["scopes"]);

        } elseif(isset($data["ShopUrl"]) && isset($data["AccessToken"]) && isset($data["scopes"])) {

            $this->configure["ShopUrl"]     = $data["ShopUrl"];
            $this->configure["AccessToken"] = $data["AccessToken"];
            $this->scopes = $this->parseScopeString($data["scopes"]);
        } else {
            throw new Exception("This object could not be constructed, check config values", 1);
        }
        $this->addCallbackUrl();
    }

    public function parseScopeString(String $scopes = null)
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

    public function getApi()
    {
        return ShopifySDK::config($this->configure);
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
