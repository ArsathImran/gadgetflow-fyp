<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejected extends Notification
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
            ->subject('Payment Proof Rejected')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your payment proof for {$gadgetName} could not be accepted.")
            ->line('Please re-upload a valid payment proof so we can continue processing your rental.')
            ->action('Upload New Payment Proof', route('customer.rentals.payment.create', $this->rental))
            ->line('If you need help, please contact the GadgetFlow team.');
    }
}
