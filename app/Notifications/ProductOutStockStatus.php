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

class ProductOutStockStatus extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable, SerializesModels;
    protected $order;
    protected $outStockOrderItem;
    protected $price;
    protected $offerprice;
    protected $message;
    protected $user;
    protected $type;
    protected $shopperName;
    protected $vendorName;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ProductOrder $order,$shopperName,$vendorName,$message,$type)
    {
        $this->order  = $order;
        $this->shopperName  = $shopperName;
        $this->vendorName  = $vendorName;
        $this->message  = $message;
        $this->type  = $type;
        $this->outStockOrderItem  = $this->order->ProductOrderItem->first();

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
        
        $data = json_decode($this->outStockOrderItem['data'],true);
        return [
            'message' => $this->message,
            'type' =>$this->type,
            'image' => $data['vendor_product']['product']['image']['name'],
            'order_code' => $this->order->order_code,
            'order_id' => $this->order->id,
            'shopper'=> $this->shopperName,
            'vendor'=> $this->vendorName,
            'requested_price'=> $data['vendor_product']['price'],
            'requested_offer_price'=>  $data['vendor_product']['offer_price'],
            'vendor_id'=>$data['vendor_product']['id'],
            'product_id'=>$data['vendor_product']['product']['id'],
            'product_name'=>$data['vendor_product']['product']['name'],

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

        $data = json_decode($this->outStockOrderItem['data'],true);
        return new BroadcastMessage([
           'message' => $this->message,
            'type' =>$this->type,
            'image' => $data['vendor_product']['product']['image']['name'],
            'order_code' => $this->order->order_code,
            'order_id' => $this->order->id,
            'shopper'=> $this->shopperName,
            'vendor'=> $this->vendorName,
            'requested_price'=> $data['vendor_product']['price'],
            'requested_offer_price'=>  $data['vendor_product']['offer_price'],
            'vendor_id'=>$data['vendor_product']['id'],
            'product_id'=>$data['vendor_product']['product']['id'],
            'product_name'=>$data['vendor_product']['product']['name'],
            'created_at' => now(),
        ]);
    }
}
