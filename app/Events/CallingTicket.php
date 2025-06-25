<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallingTicket implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queueCode; // Dynamic queue name
    public $ticketNumber; // Ticket number to be called
    public $windowName; // Window name (optional)

    /**
     * Create a new event instance.
     *
     * @param string $queueId
     * @param int $ticketNumber
     */


    public function __construct($queueId, $ticketNumber)
    {
        $this->queueCode = $queueId;
        $this->ticketNumber = $ticketNumber;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastOn()
    {
        return [new Channel("live-queue.{$this->queueCode}")];
    }
    public function broadcastWith()
    {
        return [
            'ticketNumber' => $this->ticketNumber,
        ];
    }
}
