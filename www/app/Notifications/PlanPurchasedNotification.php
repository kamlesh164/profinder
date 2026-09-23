<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanPurchasedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $purchase;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

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
    public function toArray($notifiable)
    {
        return [
            'type' => 'plan_purchased',
            'title' => 'Plan Purchased Successfully',
            'message' => "You purchased {$this->purchase->plan->name} plan with {$this->purchase->bids} bids.",
            'purchase_id' => $this->purchase->id,
            'plan_name' => $this->purchase->plan->name,
            'ends_at' => $this->purchase->ends_at->format('M d, Y'),
        ];
    }
}
