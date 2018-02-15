<?php

use Illuminate\Database\Seeder;
use App\Payment;

class PaymentTableSeeder extends Seeder
{
    public $data = [
        [
            "store_id" => 1,
            "app_id" => 1,
            "price" => 20.00
        ],
        [
            "store_id" => 2,
            "app_id" => 1,
            "price" => 30.00
        ],
        [
            "store_id" => 1,
            "app_id" => 1,
            "price" => 5.00
        ]
    ];

    public function run()
    {
        foreach($this->data as $data)
        {
            Payment::create([
                "store_id" => $data["store_id"],
                "app_id" => $data["app_id"],
                "price" => $data["price"]
            ]);
        }
    }
}
