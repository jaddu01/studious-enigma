<?php

namespace App\Notifications;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class ManageProductUpdate extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable, SerializesModels;
    protected $productId;
    protected $productName;
    protected $price;
    protected $offerprice;
    protected $message;
    protected $user;
    protected $type;
    protected $shopperName;
    protected $vendorName;
    protected $vendorId;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($shopperName,$vendorId,$vendorName,$productName,$productId,$price,$offerprice,$message,$type)
    {
        $this->productId  = $productId;
        $this->productName  = $productName;
        $this->shopperName  = $shopperName;
        $this->vendorId  = $vendorId;
        $this->vendorName  = $vendorName;
        $this->price  = $price;
        $this->offerprice  = $offerprice;
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
        
        //$data = json_decode($this->updateOrderItem['data'],true);
        return $data = [
            'message' => $this->message,
            'type' =>$this->type,
            'shopper'=> $this->shopperName,
            'vendor'=> $this->vendorName,
            'requested_price'=> $this->price,
            'requested_offer_price'=> $this->offerprice,
            'vendor_id'=>$this->vendorId,
            'product_id'=> $this->productId,
            'product_name'=>$this->productName,

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
            //'status' => $this->order->order_status,
        ];
    }

    public function toBroadcast($notifiable)
    {

        //s$data = json_decode($this->updateOrderItem['data'],true);
        return new BroadcastMessage([
            'message' => $this->message,
            'type' =>$this->type,
            'shopper'=> $this->shopperName,
            'vendor'=> $this->vendorName,
            'requested_price'=> $this->price,
            'requested_offer_price'=> $this->offerprice,
            'vendor_id'=>$this->vendorId,
             'product_id'=> $this->productId,
            'product_name'=>$this->productName,
            'created_at' => now(),
        ]);
    }
}
