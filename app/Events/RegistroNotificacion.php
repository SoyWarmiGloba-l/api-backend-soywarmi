<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegistroNotificacion implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public string $id;

    /**
     * Create a new event instance.
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('notificacion'.$this->id),
        ];
    }
    public function broadcastAs()
    {
      
        return 'registro-notificacion';
    }
}
