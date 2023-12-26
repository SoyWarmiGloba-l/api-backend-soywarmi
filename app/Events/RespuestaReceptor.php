<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Mensaje;

class RespuestaReceptor implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public string $id;
    public array $data;

    /**
     * Create a new event instance.
     */
    public function __construct(string $id, array $data)
    {
        $this->id = $id;
        $this->data = $data;
    
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // return [
        //     new Channel('mensajes'),
        //     new PrivateChannel('' . $this->id)
        //     // new PrivateChannel("mensaje.{$this->mensaje->id}")
        //];
        //return [new PrivateChannel('mensajes.' . $this->id),new PrivateChannel('mensajes')];
        //return [new PrivateChannel('mensajes.' . $this->id),new PrivateChannel('mensajes'),new Channel('mensajes-publicos')];
        return [new Channel('mensajes-publicos')];
    }
    public function broadcastAs()
    {
        /**
         * By default the Laravel event will be something like 'App\Events\PrivateEvent', here you can set the name of the custom and cleaner event.
         * You will hear this same event in Flutter after connecting to the channel.
         */
        return 'mensaje-recibido';
    }
     public function broadcastWith()
    {
        return $this->data;
    }
}
