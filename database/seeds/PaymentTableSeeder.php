<?php

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentTableSeeder extends Seeder
{
    public $data = [
        [
            "app_id"           => 1,
            "payment_name"     => "Discount App Charge",
            "payment_type"     => "recurring_charge",
            "payment_callback" => "/payment/callback",
            "payment_price"    => 10.00,
            "payment_trial"    => 7
        ],
        [
            "app_id"           => 2,
            "payment_name"     => "Other App Charge",
            "payment_type"     => "recurring_charge",
            "payment_callback" => "/payment/callback",
            "payment_price"    => 10.00,
            "payment_trial"    => 2
        ],
        [
            "app_id"           => 3,
            "payment_name"     => "Third App Charge",
            "payment_type"     => "recurring_charge",
            "payment_callback" => "/payment/callback",
            "payment_price"    => 10.00,
            "payment_trial"    => 5
        ]
    ];

    public function run()
    {
        foreach($this->data as $data)
        {
            Payment::create([
                "app_id"           => $data["app_id"],
                "payment_name"     => $data["payment_name"],
                "payment_type"     => $data["payment_type"],
                "payment_callback" => $data["payment_callback"],
                "payment_price"    => $data["payment_price"],
                "payment_trial"    => $data["payment_trial"]
            ]);
        }
    }
}
