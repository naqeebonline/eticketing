<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Vehicle;
use App\Models\Seat;
use App\Services\Booking\BookingService;
use App\Services\Booking\SeatAvailabilityService;
use App\Services\Payment\PaymentService;
use App\Traits\BelongsToBusStand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    use BelongsToBusStand;

    private const SEAT_SESSION_KEY = 'admin_booking_seats';

    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
        private ScheduleRepositoryInterface $scheduleRepository,
        private SeatAvailabilityService $seatService,
        private BookingService $bookingService,
        private PaymentService $paymentService,
    ) {}

    public function index(): View
    {
        $standIds = auth()->user()->manageableBusStandIds();

        $filters = request()->only([
            'status',
            'payment_status',
            'search',
            'vehicle_id',
            'route_id',
            'departure_date',
        ]);

        if ($standIds !== null) {
            $filters['bus_stand_ids'] = $standIds;
        }

        if (empty($filters['departure_date']) && ! request()->hasAny(['status', 'payment_status', 'search', 'vehicle_id', 'route_id'])) {
            $filters['departure_date'] = today()->toDateString();
        }

        $bookings = $this->bookingRepository->paginate($filters);

        $vehiclesQuery = Vehicle::query()->where('is_active', true)->orderBy('name');
        $routesQuery = Route::query()->where('is_active', true)->orderBy('name');

        if ($standIds !== null) {
            $vehiclesQuery->whereIn('bus_stand_id', $standIds);
            $routesQuery->whereIn('bus_stand_id', $standIds);
        }

        $vehicles = $vehiclesQuery->get(['id', 'name', 'bus_number']);
        $routes = $routesQuery->get(['id', 'name', 'departure_city', 'destination_city']);

        return view('admin.bookings.index', compact('bookings', 'vehicles', 'routes', 'filters'));
    }

    public function create(): View
    {
        $standIds = auth()->user()->manageableBusStandIds() ?? [];
        $date = request('date', today()->toDateString());

        $schedules = Schedule::query()
            ->with(['route', 'vehicle'])
            ->whereHas('route', fn ($q) => $q->whereIn('bus_stand_id', $standIds))
            ->where('departure_date', $date)
            ->bookable()
            ->orderBy('departure_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.create', compact('schedules', 'date'));
    }

    public function seats(Schedule $schedule): View|RedirectResponse
    {
        $this->authorizeSchedule($schedule);

        if ($redirect = $this->rejectIfNotBookable($schedule)) {
            return $redirect;
        }

        $seatMap = $this->seatService->getSeatMapWithStatus($schedule);

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

        return view('admin.bookings.seats', [
            'schedule' => $schedule->load(['route', 'vehicle']),
            'seatMap' => $seatMap,
            'seatsById' => $seatsById,
            'initialSelected' => array_values(array_map('intval', session(self::SEAT_SESSION_KEY, []))),
        ]);
    }

    public function holdSeats(Request $request, Schedule $schedule): RedirectResponse
    {
        $this->authorizeSchedule($schedule);

        if ($redirect = $this->rejectIfNotBookable($schedule)) {
            return $redirect;
        }

        $maxSeats = max(1, (int) $schedule->available_seats);

        $request->validate([
            'seat_ids' => 'required|array|min:1|max:'.$maxSeats,
            'seat_ids.*' => 'integer|distinct',
        ]);

        $seatIds = array_map('intval', $request->seat_ids);

        $this->bookingService->holdSeats(
            $schedule,
            $seatIds,
            auth()->id()
        );

        session([self::SEAT_SESSION_KEY => $seatIds]);

        return redirect()->route('admin.bookings.passengers', $schedule);
    }

    public function passengers(Schedule $schedule): View|RedirectResponse
    {
        $this->authorizeSchedule($schedule);

        if ($redirect = $this->rejectIfNotBookable($schedule)) {
            return $redirect;
        }

        $seatIds = session(self::SEAT_SESSION_KEY, []);

        if ($seatIds === []) {
            return redirect()->route('admin.bookings.seats', $schedule);
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

        $totalFare = $seats->sum(fn ($seat) => $seat->fareForSchedule($schedule));

        return view('admin.bookings.passengers', [
            'schedule' => $schedule->load(['route', 'vehicle']),
            'seatIds' => $orderedSeatIds,
            'seats' => $seats,
            'totalFare' => $totalFare,
        ]);
    }

    public function store(Request $request, Schedule $schedule): RedirectResponse
    {
        $this->authorizeSchedule($schedule);

        if ($redirect = $this->rejectIfNotBookable($schedule)) {
            return $redirect;
        }

        $seatIds = array_map('intval', session(self::SEAT_SESSION_KEY, []));

        if ($seatIds === []) {
            return redirect()->route('admin.bookings.seats', $schedule);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'cnic' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
            'child_count' => 'required|integer|min:0',
            'payment_method' => 'required|in:cash',
            'booking_action' => 'required|in:held,confirm',
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
            $schedule,
            $passengers,
            [
                'user_id' => null,
                'source' => 'offline',
                'booked_by' => auth()->id(),
                'hold_expires_at' => $validated['booking_action'] === 'held' ? null : now()->addMinutes(5),
            ]
        );

        if ($validated['booking_action'] === 'confirm') {
            $this->paymentService->processPayment(
                $booking,
                PaymentMethod::Cash,
                $booking->total_amount,
            );

            $booking->refresh();
            session()->forget(self::SEAT_SESSION_KEY);

            return redirect()
                ->route('admin.bookings.receipt', $booking->uuid)
                ->with('success', 'Booking confirmed — payment received · '.$booking->booking_number);
        }

        session()->forget(self::SEAT_SESSION_KEY);

        return redirect()
            ->route('admin.bookings.receipt', $booking->uuid)
            ->with('success', 'Booking held — payment pending · '.$booking->booking_number);
    }

    public function confirm(string $booking): RedirectResponse
    {
        $bookingModel = $this->bookingRepository->findByUuid($booking);
        abort_unless($bookingModel, 404);

        $bookingModel->loadMissing('schedule.route');

        $standId = $bookingModel->schedule?->route?->bus_stand_id;
        abort_unless($standId && auth()->user()->ownsBusStand($standId), 403);

        if (! $bookingModel->canReceiveCounterPayment()) {
            return back()->with('error', 'Is booking par payment receive nahi ho sakti.');
        }

        if ($redirect = $this->rejectIfNotBookable($bookingModel->schedule)) {
            return $redirect;
        }

        $this->paymentService->processPayment(
            $bookingModel,
            PaymentMethod::Cash,
            $bookingModel->total_amount - $bookingModel->paid_amount,
        );

        return redirect()
            ->route('admin.bookings.receipt', $bookingModel->uuid)
            ->with('success', 'Booking confirmed — cash payment received.');
    }

    public function receipt(string $booking): View
    {
        $bookingModel = $this->bookingRepository->findByUuid($booking);
        abort_unless($bookingModel, 404);

        $standId = $bookingModel->schedule?->route?->bus_stand_id;
        abort_unless($standId && auth()->user()->ownsBusStand($standId), 403);

        $bookingModel->load([
            'passengers.seat',
            'schedule.route',
            'schedule.vehicle',
        ]);

        return view('admin.bookings.receipt', ['booking' => $bookingModel]);
    }

    public function show(string $booking): View
    {
        $bookingModel = $this->bookingRepository->findByUuid($booking);
        abort_unless($bookingModel, 404);

        $standId = $bookingModel->schedule?->route?->bus_stand_id;
        abort_unless($standId && auth()->user()->ownsBusStand($standId), 403);

        $bookingModel->load([
            'passengers.seat',
            'passengers.cancelledByUser',
            'passengerCancellations.cancelledByUser',
        ]);

        return view('admin.bookings.show', ['booking' => $bookingModel]);
    }

    public function cancelPassengers(Request $request, string $booking): RedirectResponse
    {
        $bookingModel = $this->bookingRepository->findByUuid($booking);
        abort_unless($bookingModel, 404);

        $standId = $bookingModel->schedule?->route?->bus_stand_id;
        abort_unless($standId && auth()->user()->ownsBusStand($standId), 403);

        $validated = $request->validate([
            'passenger_ids' => 'required|array|min:1',
            'passenger_ids.*' => 'integer|distinct',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $logs = $this->bookingService->cancelPassengers(
                $bookingModel,
                $validated['passenger_ids'],
                auth()->user(),
                $validated['reason'] ?? null,
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        $refundedCount = collect($logs)->where('action', 'refunded')->count();
        $cancelledCount = collect($logs)->where('action', 'cancelled')->count();
        $totalSeats = count($logs);

        $parts = ["{$totalSeats} seat(s) cancelled."];
        if ($refundedCount > 0) {
            $parts[] = "{$refundedCount} refunded.";
        }
        if ($cancelledCount > 0) {
            $parts[] = "{$cancelledCount} unpaid (no refund).";
        }

        return back()->with('success', implode(' ', $parts));
    }

    private function authorizeSchedule(Schedule $schedule): void
    {
        $schedule->loadMissing('route');
        $standId = $schedule->route?->bus_stand_id;
        abort_unless($standId && auth()->user()->ownsBusStand($standId), 403);
    }

    private function rejectIfNotBookable(Schedule $schedule): ?RedirectResponse
    {
        if ($schedule->hasDeparted()) {
            return redirect()
                ->route('admin.bookings.create', ['date' => $schedule->departure_date->format('Y-m-d')])
                ->with('error', 'Departure time guzar chuka hai — is trip par booking nahi ho sakti.');
        }

        if ($schedule->available_seats < 1 || $schedule->status !== 'scheduled') {
            return redirect()
                ->route('admin.bookings.create', ['date' => $schedule->departure_date->format('Y-m-d')])
                ->with('error', 'This departure is not available for booking.');
        }

        return null;
    }
}
