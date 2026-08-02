<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GadgetDelivered extends Notification
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
        $dueDate = $this->rental->end_date ?? 'the agreed return date';

        return (new MailMessage)
            ->subject('Your Order Has Been Delivered')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your order for {$gadgetName} has been delivered. Enjoy your rental!")
            ->line("Please remember to return it by {$dueDate}.")
            ->action('View My Rentals', route('customer.rentals.index'));
    }
}
