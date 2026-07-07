<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
        private PaymentService $paymentService,
    ) {}

    public function store(Request $request, string $bookingUuid): JsonResponse
    {
        $request->validate([
            'method' => 'required|in:cash,stripe,jazzcash,easypaisa',
            'amount' => 'required|numeric|min:0.01',
            'gateway_data' => 'nullable|array',
        ]);

        $booking = $this->bookingRepository->findByUuid($bookingUuid);

        if (! $booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        $payment = $this->paymentService->processPayment(
            $booking,
            PaymentMethod::from($request->method),
            $request->amount,
            $request->gateway_data ?? []
        );

        return response()->json([
            'message' => 'Payment processed.',
            'data' => new PaymentResource($payment),
        ]);
    }
}
