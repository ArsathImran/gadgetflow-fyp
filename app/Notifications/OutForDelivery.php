<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OutForDelivery extends Notification
{
    use Queueable;

    public function __construct(private readonly Rental $rental)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $gadgetName = $this->rental->itemName();

        return (new MailMessage)
            ->subject('Your Order Is Out for Delivery')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your order for {$gadgetName} is out for delivery and should arrive soon.")
            ->line('Please make sure someone is available to receive it.')
            ->action('View My Rentals', route('customer.rentals.index'));
    }
}
