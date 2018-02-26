<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\Traits\FreshAndSeeded;
use App\Models\ShopifyApi;
use App\Models\App;
use App\Models\Store;
use PHPShopify\ShopifySDK;

class ShopifyApiTest extends TestCase
{
    use FreshAndSeeded;

    private $store_domain = "mihaidev.myshopify.com";

    private $app_slug     = "discount-hero";

    private $api          = null;

    private $store        = null;


    public function setUp()
    {
        parent::setUp();

        $this->refreshAndSeedDatabase();

        $store = Store::where("store_domain", $this->store_domain)->first();

        $api = new ShopifyApi($store);

        $this->api         = $api;
        $this->store       = $store;
    }

    public function testConstructShopifyApi()
    {
        $api = new ShopifyApi($this->store);
        $this->assertInstanceOf(ShopifyApi::class, $api);
    }

    public function testGetApiMethod()
    {
        $api = $this->api->getApi();
        $this->assertInstanceOf(ShopifySDK::class, $api);
    }
}
