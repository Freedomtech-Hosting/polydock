<?php

namespace App\Events;

use App\Models\PolydockAppInstance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PolydockAppInstanceReadyForLagoonDeployment
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $polydockAppInstance;

    /**
     * Create a new event instance.
     */
    public function __construct(PolydockAppInstance $polydockAppInstance)
    {
        $this->polydockAppInstance = $polydockAppInstance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
