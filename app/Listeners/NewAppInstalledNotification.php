<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\PaymentSetupCompletedEvent;

class NewAppInstalledNotification
{
    public function __construct()
    {
        //
    }

    public function handle(PaymentSetupCompletedEvent $event)
    {
        //
    }
}
