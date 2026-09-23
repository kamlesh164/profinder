<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class NewBookingNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Booking $booking) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'new_booking_request',
            'booking_id'    => $this->booking->id,
            'customer_id'   => $this->booking->user_id,
            'customer_name' => $this->booking->customer->name ?? null,
            'service_id'    => $this->booking->service_id,
            'service_name'  => $this->booking->service->name ?? null,
            'slot_date'     => $this->booking->slot_date,
            'slot_time'     => $this->booking->slot_time,
            'total_amount'  => $this->booking->total_amount,
            'message'       => 'New booking request received',
        ];
    }
}
