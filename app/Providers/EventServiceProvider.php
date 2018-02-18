<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        "App\Events\PaymentSetupCompletedEvent" => [
            "App\Listeners\InstallAssets",
            "App\Listeners\NewAppInstalledNotification"
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
