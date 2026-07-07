<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Enums\BookingStatus;
use Illuminate\View\View;

class TicketVerificationController extends Controller
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
    ) {}

    public function show(string $booking): View
    {
        $bookingModel = $this->bookingRepository->findByUuid($booking);
        abort_unless($bookingModel, 404);

        $bookingModel->load([
            'passengers' => fn ($q) => $q->whereNull('cancelled_at')->with('seat'),
            'schedule.route',
            'schedule.vehicle',
        ]);

        return view('ticket.verify', [
            'booking' => $bookingModel,
            'isValid' => $bookingModel->isTicketVerifiable(),
        ]);
    }
}
