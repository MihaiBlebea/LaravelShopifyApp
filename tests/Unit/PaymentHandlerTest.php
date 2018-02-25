<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\Traits\FreshAndSeeded;
use App\Models\ShopifyApi;
use App\Models\App;
use App\Models\Store;
use App\Models\PaymentHandler;
use App\Models\AuthHandler;

class PaymentHandlerTest extends TestCase
{
    use FreshAndSeeded;

    private $store_domain    = "mihaidev.myshopify.com";

    private $app_slug        = "discount-hero";

    private $ShopUrl         = "mihaidev.myshopify.com";

    private $token           = "20c2490ba31418dad49085f9517add33";

    private $scopes          = "read_products,write_products,read_script_tags,write_script_tags";

    private $api             = null;

    private $payment_handler = null;

    public function setUp()
    {
        parent::setUp();

        $this->refreshAndSeedDatabase();

        $app   = App::where("app_slug", $this->app_slug)->first();
        $store = Store::where("store_domain", $this->store_domain)->first();

        $api = new ShopifyApi([
            "store" => $store,
            "app"   => $app
        ]);
        $this->api             = $api;
        $this->payment_handler = new PaymentHandler($api);
    }

    public function testConstructPaymentHandlerWithApi()
    {
        $payment_handler = new PaymentHandler($this->api);
        $this->assertInstanceOf(PaymentHandler::class, $payment_handler);
    }

    public function testConstructPaymentHandlerWithAuth()
    {
        $auth_handler = new AuthHandler([
            "ShopUrl"     => $this->ShopUrl,
            "AccessToken" => $this->token,
            "scopes"      => $this->scopes
        ]);

        $payment_handler = new PaymentHandler($auth_handler);
        $this->assertInstanceOf(PaymentHandler::class, $payment_handler);
    }

    // public function testCreateChargeMethod()
    // {
    //     $payment = $app->payment;
    //     $url = $this->payment_handler->createCharge($payment);
    //     // dd($url);
    // }
}
