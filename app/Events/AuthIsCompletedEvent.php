<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\App;
use App\Models\Store;

class AuthIsCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $store = null;

    public $app = null;

    public function __construct(App $app, Store $store)
    {
        $this->app = $app;
        $this->store = $store;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
