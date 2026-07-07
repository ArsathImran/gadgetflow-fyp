<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalCompleted extends Notification
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
        $gadgetName = $this->rental->gadget?->name ?? 'your rental gadget';
        $depositStatus = match ($this->rental->deposit_status) {
            'refunded' => 'Your deposit was refunded in full: ' . number_format((float) ($this->rental->deposit_refund_amount ?? $this->rental->deposit_amount ?? 0), 2) . '.',
            'partially_refunded' => 'Your deposit was partially refunded: ' . number_format((float) ($this->rental->deposit_refund_amount ?? 0), 2) . '.',
            'deducted' => 'Your deposit was deducted: ' . number_format((float) ($this->rental->deposit_amount ?? 0), 2) . '.',
            default => 'Your deposit has been processed.',
        };

        $message = (new MailMessage)
            ->subject('Rental Completed - Thank You!')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your rental for {$gadgetName} has been completed. Thank you for renting with GadgetFlow.")
            ->line($depositStatus);

        if ((float) ($this->rental->late_fee_amount ?? 0) > 0) {
            $message->line('Late fee applied: ' . number_format((float) $this->rental->late_fee_amount, 2) . '.');
        } else {
            $message->line('No late fee was applied to this rental.');
        }

        return $message
            ->line('We’d love to hear about your experience.')
            ->action('Leave a Review', route('customer.rentals.index'));
    }
}
