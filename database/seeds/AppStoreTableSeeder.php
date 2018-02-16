<?php

use Illuminate\Database\Seeder;

class AppStoreTableSeeder extends Seeder
{
    public $data = [
        [
            "app_id" => 1,
            "store_id" => 3
        ],
        [
            "app_id" => 1,
            "store_id" => 2
        ],
        [
            "app_id" => 2,
            "store_id" => 3
        ],
    ];

    public function run()
    {
        foreach($this->data as $data)
        {
            DB::table('app_store')->insert([
                'app_id' => $data["app_id"],
                'store_id' => $data["store_id"],
            ]);
        }
    }
}
