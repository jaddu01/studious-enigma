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

class NewProduct extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable, SerializesModels;
    protected $shopperName;
    protected $vendorName;
    protected $productName;
    protected $price;
    protected $offerPrice;
    protected $message;
    protected $user;
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($shopperName,$vendorName,$productName,$price,$offerPrice,$message,$type)
    {
        $this->shopperName  = $shopperName;
        $this->vendorName  = $vendorName;
        $this->productName = $productName;
        $this->price  = $price;
        $this->offerPrice  = $offerPrice;
        $this->message  = $message;
        $this->type  = $type;

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
        
        //$data = json_decode($this->outStockOrderItem['data'],true);
        return $data = [
            'message' => $this->message,
            'type' =>$this->type,
            'product_name' =>$this->productName,
            'shopper'=> $this->shopperName,
            'vendor'=> $this->vendorName,
            'requested_price'=> $this->price,
            'requested_offer_price'=>  $this->offerPrice,
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

        //$data = json_decode($this->outStockOrderItem['data'],true);
        return new BroadcastMessage([
            'message' => $this->message,
            'type' =>$this->type,
            'product_name' =>$this->productName,
            'shopper'=> $this->shopperName,
            'vendor'=> $this->vendorName,
            'requested_price'=> $this->price,
            'requested_offer_price'=>  $this->offerPrice,
            'created_at' => now(),
        ]);
    }
}
