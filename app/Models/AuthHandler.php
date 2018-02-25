<?php

namespace App\Models;

use App\Interfaces\AuthInterface;
use PHPShopify;
use PHPShopify\AuthHelper;
use PHPShopify\ShopifySDK;
use App\Traits\GetApi;
use Exception;

class AuthHandler implements AuthInterface
{
    use GetApi;

    private $configure = [];

    private $callback_url = null;

    private $scopes = [];

    public function __construct(Array $data)
    {
        if(isset($data["ApiKey"]) && isset($data["SharedSecret"]) &&
           isset($data["ShopUrl"]) && isset($data["scopes"])) {

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

    private function addCallbackUrl(String $url = null)
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
        $this->getApi();
        return $this->constructCallbackUrl();
    }

    public function retriveToken()
    {
        $this->getApi();
        return AuthHelper::getAccessToken();
    }

}
