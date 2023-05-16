<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class AllOrderStatus extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable,SerializesModels;

    protected $orderData;
    protected $senderName;
    protected $message;
    protected $type;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($orderData,$senderName,$message,$type)
    {

        $this->orderData  = $orderData;
        $this->senderName  = $senderName;
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
        return [
            'status' => $this->orderData->order_status,
            'message' => $this->message,
            'type' => $this->type,
            'sender' => $this->senderName,
            'order_code' => $this->orderData->order_code,
            'order_id' => $this->orderData->id,
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
            'status' => $this->order->order_status,
        ];
    }

    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'status' => $this->orderData->order_status,
            'message' => $this->message,
            'type' => $this->type,
            'sender' => $this->senderName,
            'order_code' => $this->orderData->order_code,
            'order_id' => $this->orderData->id,
            'created_at' => now(),
        ]);
    }
}
