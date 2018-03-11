<?php

namespace App\Interfaces;

use App\Models\Payment;
use PHPShopify\ShopifySDK;
use App\Interfaces\ShopifySDKInterface;

interface PaymentInterface
{
    public function __construct(ShopifySDKInterface $api);

    public function charge(Payment $payment);

    public function activateOneTimePayment(String $id);

    public function activateRecurringPayment(String $id);

    public function getAllRecurringCharges();

    public function removePayment(String $id);
}
