<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShopifyApiTest extends TestCase
{
    private $validation = true;

    public function setUp()
    {

    }

    public function testTrueIsTrue()
    {
        $this->assertTrue($this->validation);
    }
}
