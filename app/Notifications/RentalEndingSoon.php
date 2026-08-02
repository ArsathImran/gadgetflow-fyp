<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalEndingSoon extends Notification
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
        $dueDate = $this->rental->end_date ?? 'soon';

        return (new MailMessage)
            ->subject('Your Rental Is Due Back Soon')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Just a reminder that your rental for {$gadgetName} is due back on {$dueDate}.")
            ->line('Please arrange to return it on time to avoid a late fee.')
            ->action('View My Rentals', route('customer.rentals.index'))
            ->line('Thank you for choosing GadgetFlow.');
    }
}
