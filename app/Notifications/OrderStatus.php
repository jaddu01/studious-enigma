<?php

namespace App\Notifications;

use App\ProductOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Helper;

class OrderStatus extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable,SerializesModels;

    protected $order;
    protected $sendor;
    protected $firstOrderItem;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ProductOrder $order, $sendor=null)
    {
        $this->order  = $order;
        $this->sendor  = $sendor;
        $this->firstOrderItem  = $this->order->ProductOrderItem->first();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        $data = json_decode($this->firstOrderItem['data'],true);
        return [
            'status' => $this->order->order_status,
            'sender' =>$this->sendor,
            'message' => "Order successfully ".Helper::$order_status[ $this->order->order_status],
            'image' => $data['vendor_product']['product']['image']['name'],
            'order_code' => $this->order->order_code,
            'order_id' => $this->order->id,

        ];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'status' => $this->order->order_status,
        ];
    }

    public function toBroadcast($notifiable)
    {

        $data = json_decode($this->firstOrderItem['data'],true);
        return new BroadcastMessage([
            'status' => $this->order->order_status,
             'sender' =>$this->sendor,
            'message' => "Order successfully ".Helper::$order_status[ $this->order->order_status],
            //'message' => "Order staus updated",
            'image' => $data['vendor_product']['product']['image']['name'],
            'order_code' => $this->order->order_code,
            'order_id' => $this->order->id,
            'created_at' => now(),
        ]);
    }
}
