<?php

namespace App\Interfaces;

use App\Interfaces\AuthInterface;
use App\Models\Payment;

interface PaymentInterface
{
    public function __construct(AuthInterface $api);

    public function charge(Payment $payment);

    public function activateOneTimePayment(String $id);

    public function activateRecurringPayment(String $id);

    public function getAllRecurringCharges();

    public function removePayment(String $id);
}
