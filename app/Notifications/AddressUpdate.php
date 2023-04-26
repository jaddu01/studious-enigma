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

class AddressUpdate extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable, SerializesModels;
    protected $UserName;
    protected $DriverName;
    protected $shippingLocation;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($UserName,$DriverName,$shipping_location)
    {
        $this->UserName  = $UserName;
        $this->DriverName  = $DriverName;
        $this->shippingLocation  = $shipping_location;
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
        
        return [
            'user_name' => $this->UserName,
            'driver_name' =>$this->DriverName,
            'customer_id'=> $this->shippingLocation['customer_id'],
            'delivery_location_id' => $this->shippingLocation['id'],
            'address' => $this->shippingLocation['address'],
            'name' => $this->shippingLocation['name'],
            'description' => $this->shippingLocation['description'],
            'lat'=> $this->shippingLocation['lat'],
            'lng'=> $this->shippingLocation['lng'],
            'created_at' => now(),

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
            
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_name' => $this->UserName,
            'driver_name' =>$this->DriverName,
            'customer_id'=> $this->shippingLocation['customer_id'],
            'delivery_location_id' => $this->shippingLocation['id'],
            'address' => $this->shippingLocation['address'],
            'name' => $this->shippingLocation['name'],
            'description' => $this->shippingLocation['description'],
            'lat'=> $this->shippingLocation['lat'],
            'lng'=> $this->shippingLocation['lng'],
            'created_at' => now(),
        ]);
    }
}
