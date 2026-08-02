<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalSubmitted extends Notification
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
        $rentalPeriod = $this->rental->start_date && $this->rental->end_date
            ? $this->rental->start_date . ' to ' . $this->rental->end_date
            : 'your requested rental period';

        return (new MailMessage)
            ->subject("We've Received Your Rental Request")
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Thanks for submitting a rental request for {$gadgetName}.")
            ->line("Requested period: {$rentalPeriod}.")
            ->line('Our team will review your request and let you know as soon as it\'s approved.')
            ->action('View My Rentals', route('customer.rentals.index'))
            ->line('Thank you for choosing GadgetFlow.');
    }
}
