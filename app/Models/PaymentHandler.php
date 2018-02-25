<?php

namespace App\Models;

use App\Interfaces\ApiInterface;
use App\Interfaces\AuthInterface;
use App\Interfaces\PaymentInterface;
use App\Models\ShopifyApi;
use App\Models\Payment;
use Exception;
use App;

class PaymentHandler implements PaymentInterface
{
    private $api = null;

    private $charge = null;

    public function __construct($api)
    {
        if($api instanceof ApiInterface || $api instanceof AuthInterface)
        {
            $this->api = $api->getApi();
            dd($this->api);
        }
    }

    public function createCharge(Payment $payment)
    {
        $charge = [
            "name"       => $payment->payment_name,
            "price"      => $payment->payment_price,
            "return_url" => config('app.url') . $payment->payment_callback,
            "trial_days" => $payment->payment_trial,
            "test"       => (App::environment("local")) ? true : false
        ];
        $this->charge = $charge;

        if($this->isChargeSet() == true)
        {
            return $confirmation_url = $this->setCharge($payment);
        }
    }

    public function isChargeSet()
    {
        return ($this->charge !== null) ? true : false;
    }

    public function getCharge()
    {
        return $this->charge;
    }

    // Send charge to store, not activated yet
    public function setCharge(Payment $payment)
    {
        switch($payment->payment_type)
        {
            case "recurring_charge":
                $response = $this->api->RecurringApplicationCharge->post($this->charge);
                break;
            case "one_time_charge":
                $response = $this->api->ApplicationCharge->post($this->charge);
                break;
            case "usage_charge":
                // TODO build usage charge
                break;
            default:
                throw new Exception("Type of charge was not found", 1);
        }

        if($response["status"] == "pending")
        {
            return $response["confirmation_url"];
        } else {
            throw new Exception("Charge didn't go throught", 1);
        }
    }

    public function activateCharge(App $app, String $charge_id)
    {
        $payment_type = $app->payment->payment_type;

        switch($payment_type)
        {
            case "recurring_charge":
                return $this->oneTimePayment($charge_id);
                break;
            case "one_time_charge":
                return $this->recurringPayment($charge_id);
                break;
            case "usage_charge":
                // TODO build usage charge
                break;
            default:
                throw new Exception("Type of charge was not found", 1);
        }
    }

    private function oneTimePayment(String $charge_id)
    {
        $response = $this->api->ApplicationCharge($charge_id)->get();
        if($this->isPaymentAccepted($response) == true)
        {
            $response = $this->activateOneTime($charge_id);
            return $this->isPaymentActive($response)
        }
    }

    public function recurringPayment(String $charge_id)
    {
        $response = $this->api->RecurringApplicationCharge($charge_id)->get();
        if($this->isPaymentAccepted($response) == true)
        {
            $response = $this->activateRecurringCharge($charge_id);
            return $this->isPaymentActive($response);
        }
    }

    private function activateOneTimeCharge(String $id)
    {
        return $this->api->ApplicationCharge($id)->activate();
    }

    private function activateRecurringCharge(String $id)
    {
        return $this->api->RecurringApplicationCharge($id)->activate();
    }

    public function getAllRecurringCharges()
    {
        return $this->api->RecurringApplicationCharge->get();
    }

    public function removePayment(String $id)
    {
        return $this->api->RecurringApplicationCharge($id)->delete();
    }

    // Helper functions that validate response

    private function isPaymentAccepted($response)
    {
        if($response["status"] == "accepted")
        {
            return true;
        } else {
            throw new Exception("Payment was declined", 1);
        }
    }

    private function isPaymentActive($response)
    {
        if($response["status"] == "active")
        {
            return true;
        } else {
            throw new Exception("Payment was not activated", 1);
        }
    }
}
