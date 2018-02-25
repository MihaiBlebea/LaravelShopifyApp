<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\Traits\FreshAndSeeded;
use App\Models\AuthHandler;
use PHPShopify\ShopifySDK;

class AuthHandlerTest extends TestCase
{
    use FreshAndSeeded;

    private $ApiKey       = "138d8cedf09b6e8befd4f6492be75691";

    private $SharedSecret = "9de14452d4803e25bb54a9dae0820831";

    private $ShopUrl      = "mihaidev.myshopify.com";

    private $scopes       = "read_products,write_products,read_script_tags,write_script_tags";

    private $token        = "20c2490ba31418dad49085f9517add33";

    private $callback_url = "http://localhost:8070/ShopifyApps/public/auth/callback";

    private $auth_handler = null;

    public function setUp()
    {
        parent::setUp();

        $this->refreshAndSeedDatabase();

        $auth_handler = new AuthHandler([
            "ApiKey"       => $this->ApiKey,
            "SharedSecret" => $this->SharedSecret,
            "ShopUrl"      => $this->ShopUrl,
            "scopes"       => $this->scopes
        ]);
        $this->auth_handler = $auth_handler;
    }

    public function testConstructWithoutToken()
    {
        $auth_handler = new AuthHandler([
            "ApiKey"       => $this->ApiKey,
            "SharedSecret" => $this->SharedSecret,
            "ShopUrl"      => $this->ShopUrl,
            "scopes"       => $this->scopes
        ]);
        $this->assertInstanceOf(AuthHandler::class, $auth_handler);
    }

    public function testConstructWithToken()
    {
        $auth_handler = new AuthHandler([
            "ShopUrl"     => $this->ShopUrl,
            "AccessToken" => $this->token,
            "scopes"      => $this->scopes
        ]);
        $this->assertInstanceOf(AuthHandler::class, $auth_handler);
    }

    public function testGettingTheCallBackUrl()
    {
        $url = $this->auth_handler->getCallbackUrl();
        $expected_url = "https://" . $this->ShopUrl .
                        "/admin/oauth/authorize?client_id=" . $this->ApiKey .
                        "&redirect_uri=" . $this->callback_url . "&scope=" . $this->scopes;
        $this->assertEquals($url, $expected_url);
    }

    public function testGetApiMethod()
    {
        $api = $this->auth_handler->getApi();
        $this->assertInstanceOf(ShopifySDK::class, $api);
    }

}
