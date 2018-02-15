<?php

use Illuminate\Database\Seeder;
use App\Asset;

class AssetTableSeeder extends Seeder
{
    public $data =[
        [
            "app_id" => 1,
            "asset_name" => "stylesheet",
            "asset_type" => "asset",
            "asset_path" => "/assets/stylesheet.css"
        ],
        [
            "app_id" => 1,
            "asset_name" => "timer-controller",
            "asset_type" => "snippet",
            "asset_path" => "/snippet/timer.css"
        ],
        [
            "app_id" => 2,
            "asset_name" => "js-controller",
            "asset_type" => "asset",
            "asset_path" => "/assets/controller.js"
        ]
    ];

    public function run()
    {
        foreach($this->data as $data)
        {
            Asset::create([
                "app_id" => $data["app_id"],
                "asset_name" => $data["asset_name"],
                "asset_type" => $data["asset_type"],
                "asset_path" => $data["asset_path"]
            ]);
        }
    }
}
