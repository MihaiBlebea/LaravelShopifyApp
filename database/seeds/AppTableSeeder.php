<?php

use Illuminate\Database\Seeder;
use App\App;

class AppTableSeeder extends Seeder
{
    public $data = [
        [
            "app_name" => "DiscountHero",
            "app_slug" => "discount-hero",
            "app_key" => "138d8cedf09b6e8befd4f6492be75691",
            "app_secret" => "9de14452d4803e25bb54a9dae0820831",
            "app_scopes" => "read_products,write_products,read_script_tags, write_script_tags,read_themes, write_themes"
        ],
        [
            "app_name" => "SliderMassEffect",
            "app_slug" => "slider-mass-effect",
            "app_key" => "adsaqffasfasfadsad",
            "app_secret" => "dafsafqyf8yqfq8f",
            "app_scopes" => null
        ],
        [
            "app_name" => "TimeoutDemo",
            "app_slug" => "timeout-demo",
            "app_key" => "adsaqffasfasfadsad",
            "app_secret" => "dafsafqyf8yqfq8f",
            "app_scopes" => null
        ]
    ];

    public function run()
    {
        foreach($this->data as $data)
        {
            App::create([
                "app_name" => $data["app_name"],
                "app_slug" => $data["app_slug"],
                "app_key" => $data["app_key"],
                "app_secret" => $data["app_secret"],
                "app_scopes" => ($data["app_scopes"] !== null) ? $data["app_scopes"] : null
            ]);
        }
    }
}
