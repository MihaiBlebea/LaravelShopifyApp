<?php

namespace App\Models;

use App\Models\ShopifyApi;
use App\Models\Payment;
use Exception;
use App;

class PaymentHandler
{
    private $api = null;

    public function __construct(ShopifyApi $api)
    {
        $this->api = $api->getApi();
    }

    public function figureType(Payment $payment)
    {
        switch($payment->payment_type)
        {
            case "recurring_charge":
                return $this->recurringCharge($payment);
                break;
            case "one_time_charge":

            case "usage_charge":

            default:
                throw new Exception("Type of charge was not found", 1);
        }
    }

    public function recurringCharge(Payment $payment)
    {
        $charge = [
            "name"       => $payment->payment_name,
            "price"      => $payment->payment_price,
            "return_url" => config('app.url') . $payment->payment_callback,
            "test"       => (App::environment("local")) ? true : false
        ];
        $response = $this->api->RecurringApplicationCharge->post($charge);

        if($response["status"] == "pending")
        {
            return $response["confirmation_url"];
        } else {
            throw new Exception("Charge didn't go throught", 1);
        }
    }

    public function activateRecurringCharge(String $id)
    {
        return $this->api->RecurringApplicationCharge($id)->activate();
    }
}
