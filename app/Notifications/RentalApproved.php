<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalApproved extends Notification
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
            : 'your scheduled rental period';

        $message = (new MailMessage)
            ->subject('Your Rental Request Has Been Approved')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Good news, your rental request for {$gadgetName} has been approved.")
            ->line("Rental period: {$rentalPeriod}.")
            ->line('Total amount: ' . number_format((float) $this->rental->total_amount, 2) . '.');

        if ($this->rental->pickup_type === 'walk_in') {
            $message
                ->line('Next step: please come to collect your gadget and complete the handover in person.')
                ->action('View My Rentals', route('customer.rentals.index'));
        } else {
            $message
                ->line('Next step: please upload your payment proof so we can prepare your order for delivery.')
                ->action('Upload Payment Proof', route('customer.rentals.payment.create', $this->rental));
        }

        return $message->line('Thank you for choosing GadgetFlow.');
    }
}
