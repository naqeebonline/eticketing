<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBookingConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function handle(): void
    {
        $user = $this->booking->user;
        if ($user) {
            $user->notify(new BookingConfirmedNotification($this->booking));
        }

        // SMS / WhatsApp integration hooks
        // app(SmsService::class)->send($phone, $message);
        // app(WhatsAppService::class)->send($phone, $ticketUrl);
    }
}
