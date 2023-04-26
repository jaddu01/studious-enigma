<?php

namespace App\Events;
use App\ProductOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductUnavailable
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $productOrder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
   public function __construct(ProductOrder $productOrder)
    {
        $this->productOrder = $productOrder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
