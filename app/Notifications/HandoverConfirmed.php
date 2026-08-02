<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HandoverConfirmed extends Notification
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
            ->subject('Pickup Confirmed - Enjoy Your Rental')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("We've confirmed pickup of {$gadgetName}. Enjoy your rental!")
            ->line("Please remember to return it by {$dueDate}.")
            ->action('View My Rentals', $this->rental->customerUrl());
    }
}
