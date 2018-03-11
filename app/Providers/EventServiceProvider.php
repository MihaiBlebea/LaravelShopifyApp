<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        "App\Events\AuthSetupCompletedEvent" => [
            "App\Listeners\AuthCompleteAdminNotification"
        ],
        "App\Events\PaymentSetupCompletedEvent" => [
            "App\Listeners\PaymentCompleteAdminNotification"
        ],
        "App\Events\AssetSetupCompletedEvent" => [
            "App\Listeners\AssetCompleteAdminNotification"
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
