<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            StoreTableSeeder::class,
            AppTableSeeder::class,
            // AppStoreTableSeeder::class,
            AssetTableSeeder::class,
            PaymentTableSeeder::class
        ]);
    }
}
