<?php

use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetTableSeeder extends Seeder
{
    public $data =[
        [
            "app_id" => 1,
            "asset_name" => "stylesheet",
            "asset_type" => "sections",
            "asset_path" => "/files/sections/stylesheet.liquid"
        ],
        [
            "app_id" => 1,
            "asset_name" => "timer-controller",
            "asset_type" => "snippets",
            "asset_path" => "/files/snippets/stylesheet.liquid"
        ],
        [
            "app_id" => 2,
            "asset_name" => "js-controller",
            "asset_type" => "assets",
            "asset_path" => "/files/assets/stylesheet.js"
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
