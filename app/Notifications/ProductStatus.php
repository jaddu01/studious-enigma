<?php

namespace App\Notifications;
use App\ProductOrder;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class ProductStatus extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable, SerializesModels;
    protected $order;
    protected $unavailableOrderItem;
    protected $message;
    protected $user;
    protected $type;
    protected $shopperName;
    protected $productData;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ProductOrder $order,$productData,$shopperName,$message,$type)
    {
        $this->order  = $order;
        $this->productData  = $productData;
        $this->shopperName  = $shopperName;
        $this->message  = $message;
        $this->type  = $type;
        $this->unavailableOrderItem  = $this->order->ProductOrderItem->first();
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
        $data = json_decode($this->unavailableOrderItem['data'],true);
        return [
            'status' => 'U',
            'message' => $this->message,
            'type' =>$this->type,
            'image' => $data['vendor_product']['product']['image']['name'],
            'order_code' => $this->order->order_code,
            'order_id' => $this->order->id,
            'shopper'=> $this->shopperName,
            'vendor_id'=> $this->productData->id,
            'product_id'=> $this->productData->product->id,
            'product_name'=> $this->productData->product->name,

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

        $data = json_decode($this->unavailableOrderItem['data'],true);
        return new BroadcastMessage([
             'status' => 'U',
            'message' => $this->message,
            'type' =>$this->type,
            'image' => $data['vendor_product']['product']['image']['name'],
            'order_code' => $this->order->order_code,
            'order_id' => $this->order->id,
            'shopper'=> $this->shopperName,
            'vendor_id'=> $this->productData->id,
            'product_id'=> $this->productData->product->id,
            'product_name'=> $this->productData->product->name,
            'created_at' => now(),
        ]);
    }
}
