<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Artisan;

trait FreshAndSeeded
{
    public function refreshAndSeedDatabase()
    {
        Artisan::call("migrate:fresh");
        Artisan::call("db:seed");
    }
}
