<?php

namespace App\Events;

use App\Models\PolydockAppInstance;
use App\Models\PolydockAppInstanceDeployment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PolydockAppInstanceDeploymentSuccess
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $polydockAppInstanceDeployment;

    /**
     * Create a new event instance.
     */
    public function __construct(PolydockAppInstanceDeployment $polydockAppInstanceDeployment)
    {
        $this->polydockAppInstanceDeployment = $polydockAppInstanceDeployment;
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
