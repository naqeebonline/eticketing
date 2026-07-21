<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Services\Booking\BookingService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
        private ScheduleRepositoryInterface $scheduleRepository,
        private BookingService $bookingService,
        private PaymentService $paymentService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'search']);
        $filters['user_id'] = $request->user()->id;

        $bookings = $this->bookingRepository->paginate(
            $filters,
            $request->integer('per_page', 50)
        );

        return response()->json(BookingResource::collection($bookings)->response()->getData(true));
    }

    public function store(CreateBookingRequest $request): JsonResponse
    {
        $schedule = $this->scheduleRepository->findByUuid($request->schedule_uuid);

        if (! $schedule) {
            return response()->json(['message' => 'Schedule not found.'], 404);
        }

        $booking = $this->bookingService->createBooking(
            $schedule,
            $request->passengers,
            [
                'user_id' => $request->user()?->id,
                'coupon_code' => $request->coupon_code,
                'source' => 'api',
            ]
        );

        if ($request->payment_method) {
            $this->paymentService->processPayment(
                $booking,
                PaymentMethod::from($request->payment_method),
                $request->float('payment_amount', $booking->total_amount),
                $request->gateway_data ?? []
            );
            $booking->refresh();
        }

        return response()->json([
            'message' => 'Booking created successfully.',
            'data' => new BookingResource($booking->load('passengers.seat', 'schedule.route')),
        ], 201);
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        $booking = $this->bookingRepository->findByUuid($uuid);

        if (! $booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json(new BookingResource($booking));
    }

    public function cancel(Request $request, string $uuid): JsonResponse
    {
        $booking = $this->bookingRepository->findByUuid($uuid);

        if (! $booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $booking = $this->bookingService->cancelBooking($booking, $request->reason);

        return response()->json([
            'message' => 'Booking cancelled.',
            'data' => new BookingResource($booking),
        ]);
    }

    public function verifyQr(Request $request): JsonResponse
    {
        $request->validate(['qr_code' => 'required|string']);

        $booking = \App\Models\Booking::where('qr_code', $request->qr_code)
            ->where('status', 'confirmed')
            ->first();

        return response()->json([
            'valid' => (bool) $booking,
            'booking' => $booking ? new BookingResource($booking->load('passengers.seat')) : null,
        ]);
    }
}
