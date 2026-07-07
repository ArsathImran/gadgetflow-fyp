<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalRejected extends Notification
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
        $gadgetName = $this->rental->gadget?->name ?? 'your selected gadget';

        return (new MailMessage)
            ->subject('Your Rental Request Was Not Approved')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("We’re sorry, but your rental request for {$gadgetName} was not approved.")
            ->line('You can browse other available gadgets and submit another request anytime.')
            ->action('Browse Available Gadgets', route('customer.gadgets.index'))
            ->line('Thank you for your understanding.');
    }
}
