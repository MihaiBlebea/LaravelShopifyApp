<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\App;
use App\Models\Store;

class AuthCompleteAdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $app;

    protected $store;

    public function __construct(App $app, Store $store)
    {
        $this->app   = $app;
        $this->store = $store;
    }

    public function build()
    {
        return $this->from("mihaiserban.blebea@gmail.com")
                    ->view("emails.admin.auth_completed")
                    ->with([
                        "app"   => $this->app,
                        "store_domain" => $this->store->store_domain,
                    ]);
    }
}
