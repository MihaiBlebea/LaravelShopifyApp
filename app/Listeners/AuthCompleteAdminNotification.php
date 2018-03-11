<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\AuthCompleteAdminEmail;
use Illuminate\Support\Facades\Mail;

class AuthCompleteAdminNotification
{
    private $app;

    private $store;

    public function __construct($event)
    {
        $this->app   = $event->app;
        $this->store = $event->store;
    }

    public function handle($event)
    {
        Mail::to("slabestecuserban@gmail.com")->send(new AuthCompleteAdminEmail($this->app, $this->store));
    }
}
