<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\Traits\FreshAndSeeded;
use App\Models\InstallHandler;
use App\Models\ShopifyApi;
use App\Models\App;
use App\Models\Store;


class InstallHandlerTest extends TestCase
{
    use FreshAndSeeded;

    private $store_domain    = "mihaidev.myshopify.com";

    private $app_slug        = "discount-hero";

    private $theme_id        = "21699100714";

    private $install_handler = null;

    private $shopify_app     = null;


    public function setUp()
    {
        parent::setUp();

        $this->refreshAndSeedDatabase();

        $app   = App::where("app_slug", $this->app_slug)->first();
        $store = Store::where("store_domain", $this->store_domain)->first();

        $api = new ShopifyApi($store);

        $this->shopify_app     = $app;
        $this->api             = $api;
        $this->install_handler = new InstallHandler($api);
    }

    // Tests start here

    public function testConstructInstallHandler()
    {
        $install_handler = new InstallHandler($this->api);
        $this->assertInstanceOf(InstallHandler::class, $this->install_handler);
    }

    public function testInstallAllAppAssets()
    {
        $response = $this->install_handler->installApp($this->shopify_app);
        $this->assertTrue($response);
    }

    public function testUninstallAllAppAssets()
    {
        $response = $this->install_handler->uninstallApp($this->shopify_app);
        $this->assertTrue($response);
    }
}
