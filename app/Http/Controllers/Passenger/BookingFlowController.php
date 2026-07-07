<?php

namespace App\Http\Controllers\Passenger;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Enums\BookingStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Services\Booking\BookingService;
use App\Services\Booking\SeatAvailabilityService;
use App\Models\Seat;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingFlowController extends Controller
{
    public function __construct(
        private ScheduleRepositoryInterface $scheduleRepository,
        private BookingRepositoryInterface $bookingRepository,
        private SeatAvailabilityService $seatService,
        private BookingService $bookingService,
        private PaymentService $paymentService,
    ) {}

    public function results(Request $request): View
    {
        $schedules = $this->scheduleRepository->searchAvailable(
            $request->get('from', ''),
            $request->get('to', ''),
            $request->get('date', today()->format('Y-m-d'))
        );

        return view('passenger.results', [
            'schedules' => $schedules,
            'from' => $request->from,
            'to' => $request->to,
            'date' => $request->date,
        ]);
    }

    public function seats(string $schedule): View
    {
        $scheduleModel = $this->scheduleRepository->findByUuid($schedule);
        abort_unless($scheduleModel, 404);

        $seatMap = $this->seatService->getSeatMapWithStatus($scheduleModel);

        $seatsById = collect($seatMap['seats'] ?? [])
            ->keyBy('id')
            ->map(fn ($s) => [
                'id' => $s['id'],
                'seat_number' => $s['seat_number'],
                'type' => $s['type'] ?? 'normal',
                'fare' => (float) $s['fare'],
                'status' => $s['status'],
            ])
            ->all();

        return view('passenger.seats', [
            'schedule' => $scheduleModel,
            'seatMap' => $seatMap,
            'seatsById' => $seatsById,
            'initialSelected' => array_values(array_map('intval', session('selected_seats', []))),
        ]);
    }

    public function holdSeats(Request $request, string $schedule): RedirectResponse
    {
        $scheduleModel = $this->scheduleRepository->findByUuid($schedule);
        abort_unless($scheduleModel, 404);

        $maxSeats = max(1, (int) $scheduleModel->available_seats);

        $request->validate([
            'seat_ids' => 'required|array|min:1|max:'.$maxSeats,
            'seat_ids.*' => 'integer|distinct',
        ]);

        $seatIds = array_map('intval', $request->seat_ids);

        $this->bookingService->holdSeats(
            $scheduleModel,
            $seatIds,
            auth()->id()
        );

        session(['selected_seats' => $seatIds]);

        return redirect()->route('book.passengers', $schedule);
    }

    public function passengers(string $schedule): View
    {
        $scheduleModel = $this->scheduleRepository->findByUuid($schedule);
        abort_unless($scheduleModel, 404);

        $seatIds = session('selected_seats', []);

        if ($seatIds === []) {
            return redirect()->route('book.seats', $schedule);
        }

        $seats = Seat::query()
            ->whereIn('id', $seatIds)
            ->orderBy('row')
            ->orderBy('column')
            ->get()
            ->keyBy('id');

        $orderedSeatIds = collect($seatIds)
            ->sortBy(fn ($id) => ($seats->get($id)?->row ?? 0) * 1000 + ($seats->get($id)?->column ?? 0))
            ->values()
            ->all();

        $totalFare = $seats->sum(fn ($seat) => $seat->fareForSchedule($scheduleModel));

        return view('passenger.passengers', [
            'schedule' => $scheduleModel,
            'seatIds' => $orderedSeatIds,
            'seats' => $seats,
            'totalFare' => $totalFare,
        ]);
    }

    public function storePassengers(Request $request, string $schedule): RedirectResponse
    {
        $scheduleModel = $this->scheduleRepository->findByUuid($schedule);
        abort_unless($scheduleModel, 404);

        $seatIds = array_map('intval', session('selected_seats', []));

        if ($seatIds === []) {
            return redirect()->route('book.seats', $schedule);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'cnic' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
            'child_count' => 'required|integer|min:0',
        ]);

        $travelerTotal = $validated['male_count'] + $validated['female_count'] + $validated['child_count'];

        if ($travelerTotal !== count($seatIds)) {
            return back()
                ->withErrors(['travelers' => 'Male, female aur child ka total ('.$travelerTotal.') selected seats ('.count($seatIds).') ke barabar hona chahiye.'])
                ->withInput();
        }

        if ($travelerTotal < 1) {
            return back()
                ->withErrors(['travelers' => 'Kam az kam ek traveler specify karein.'])
                ->withInput();
        }

        try {
            $passengers = $this->bookingService->buildPassengersFromGroup(
                $seatIds,
                [
                    'full_name' => $validated['full_name'],
                    'cnic' => $validated['cnic'],
                    'phone' => $validated['phone'] ?? null,
                ],
                $validated['male_count'],
                $validated['female_count'],
                $validated['child_count'],
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['travelers' => $e->getMessage()])->withInput();
        }

        $booking = $this->bookingService->createBooking(
            $scheduleModel,
            $passengers,
            [
                'user_id' => auth()->id(),
                'source' => 'online',
                'hold_expires_at' => null,
            ]
        );

        session()->forget('selected_seats');

        return redirect()
            ->route('book.ticket', $booking->uuid)
            ->with('success', 'Booking hold ho gayi hai. Payment counter par karein.');
    }

    public function payment(string $booking): View|RedirectResponse
    {
        $bookingModel = $this->bookingRepository->findByUuid($booking);
        abort_unless($bookingModel, 404);

        if ($bookingModel->status === BookingStatus::Held && $bookingModel->payment_status === PaymentStatus::Pending) {
            return redirect()->route('book.ticket', $bookingModel->uuid);
        }

        return view('passenger.payment', ['booking' => $bookingModel]);
    }

    public function processPayment(Request $request, string $booking): RedirectResponse
    {
        $bookingModel = $this->bookingRepository->findByUuid($booking);
        abort_unless($bookingModel, 404);

        $request->validate(['method' => 'required|in:cash,stripe,jazzcash,easypaisa']);

        $this->paymentService->processPayment(
            $bookingModel,
            PaymentMethod::from($request->method),
            $bookingModel->total_amount,
            $request->only(['payment_intent_id', 'pp_TxnRefNo', 'orderId'])
        );

        return redirect()->route('book.ticket', $bookingModel->uuid);
    }

    public function ticket(string $booking): View
    {
        $bookingModel = $this->bookingRepository->findByUuid($booking);
        abort_unless($bookingModel, 404);

        return view('passenger.ticket', ['booking' => $bookingModel->load([
            'passengers.seat',
            'schedule.route',
            'schedule.vehicle',
        ])]);
    }
}
