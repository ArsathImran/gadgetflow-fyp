<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GadgetShipped extends Notification
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
            ->subject('Your Order Has Shipped')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your order for {$gadgetName} has shipped and is on its way to you.")
            ->action('View My Rentals', route('customer.rentals.index'))
            ->line('We\'ll let you know when it\'s out for delivery.');
    }
}
