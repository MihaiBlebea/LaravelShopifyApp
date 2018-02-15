<?php

use Illuminate\Database\Seeder;
use App\Store;

class StoreTableSeeder extends Seeder
{
    public $data = [
        [
            "store_name" => "Mihaidev",
            "store_plan" => "Basic",
            "store_token" => "dasdasfefgsgvsdvs",
            "store_owner" => "Mihai Blebea",
            "store_domain" => "mihaidev.myshopify.com",
            "store_email" => "mihaiserban.blebea@gmail.com",
            "store_phone" => "0757103898"
        ],
        [
            "store_name" => "Canton",
            "store_plan" => "Profesional",
            "store_token" => "dasdasfefgsgvsdvs",
            "store_owner" => "Jen",
            "store_domain" => "canton-tea.myshopify.com",
            "store_email" => "canton@gmail.com",
            "store_phone" => "0757103898"
        ],
        [
            "store_name" => "ChristinaStore",
            "store_plan" => "Basic",
            "store_token" => "dasdasfefgsgvsdvs",
            "store_owner" => "Cristina Aliman",
            "store_domain" => "ctistina-store.myshopify.com",
            "store_email" => "mihaiserban.blebea@gmail.com",
            "store_phone" => "0757103898"
        ]
    ];


    public function run()
    {
        foreach($this->data as $data)
        {
            Store::create([
                "store_name" => $data['store_name'],
                "store_plan" => $data['store_plan'],
                "store_token" => $data['store_token'],
                "store_owner" => $data['store_owner'],
                "store_domain" => $data['store_domain'],
                "store_email" => $data['store_email'],
                "store_phone" => $data['store_phone']
            ]);
        }
    }
}
