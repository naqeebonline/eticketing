<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $schedule = $this->booking->schedule;

        return (new MailMessage)
            ->subject('Booking Confirmed - '.$this->booking->booking_number)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Your bus ticket has been confirmed.')
            ->line('Booking: '.$this->booking->booking_number)
            ->line('Route: '.$schedule->route->departure_city.' → '.$schedule->route->destination_city)
            ->line('Date: '.$schedule->departure_date->format('M d, Y').' at '.$schedule->departure_time)
            ->action('View Ticket', url('/bookings/'.$this->booking->uuid.'/ticket'))
            ->line('Thank you for choosing BSS Booking!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number,
            'message' => 'Your booking '.$this->booking->booking_number.' has been confirmed.',
        ];
    }
}
