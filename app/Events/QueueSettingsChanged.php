<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueSettingsChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $queueId; // Dynamic queue name

    /**
     * Create a new event instance.
     *
     * @param string $queueId
     */
    public function __construct($queueId)
    {
        $this->queueId = $queueId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    //Broadcast an event on a channel
    public function broadcastOn()
    {
        return [new Channel('live-queue.'.$this->queueId)];
    }

    public function broadcastWith()
    {
        return [
        ];
    }
}
