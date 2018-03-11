<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\AuthCompleteAdminEmail;
use App\Events\AuthSetupCompletedEvent;
use Illuminate\Support\Facades\Mail;

class AuthCompleteAdminNotification
{
    public $app;

    public $store;

    public function __construct(AuthSetupCompletedEvent $event)
    {
        $this->app   = $event->app;
        $this->store = $event->store;
    }

    public function handle($event)
    {
        Mail::to("slabestecuserban@gmail.com")->send(new AuthCompleteAdminEmail($this->app, $this->store));
    }
}
