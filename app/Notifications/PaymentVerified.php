<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerified extends Notification
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
            ->subject('Payment Confirmed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your payment for {$gadgetName} has been confirmed.")
            ->line('Your order is now being prepared for shipping or handover.')
            ->action('View My Rentals', $this->rental->customerUrl())
            ->line('We’ll keep you updated as your rental progresses.');
    }
}
