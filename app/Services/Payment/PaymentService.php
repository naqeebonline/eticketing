<?php

namespace App\Services\Payment;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Refund;
use App\Services\Booking\BookingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(private BookingService $bookingService) {}

    public function processPayment(Booking $booking, PaymentMethod $method, float $amount, array $gatewayData = []): Payment
    {
        return DB::transaction(function () use ($booking, $method, $amount, $gatewayData) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => 'TXN-'.strtoupper(Str::random(12)),
                'method' => $method,
                'amount' => $amount,
                'status' => PaymentStatus::Pending,
                'gateway_response' => $gatewayData,
            ]);

            $verified = match ($method) {
                PaymentMethod::Cash => true,
                PaymentMethod::Stripe => $this->verifyStripe($gatewayData),
                PaymentMethod::JazzCash => $this->verifyJazzCash($gatewayData),
                PaymentMethod::Easypaisa => $this->verifyEasypaisa($gatewayData),
            };

            if ($verified) {
                $payment->update([
                    'status' => PaymentStatus::Paid,
                    'paid_at' => now(),
                    'gateway_reference' => $gatewayData['reference'] ?? null,
                ]);

                $newPaidAmount = $booking->paid_amount + $amount;
                $booking->update([
                    'paid_amount' => $newPaidAmount,
                    'payment_status' => $newPaidAmount >= $booking->total_amount
                        ? PaymentStatus::Paid
                        : PaymentStatus::Partial,
                ]);

                if ($booking->paid_amount >= $booking->total_amount) {
                    $this->bookingService->confirmBooking($booking);
                }
            } else {
                $payment->update(['status' => PaymentStatus::Failed]);
            }

            return $payment->fresh();
        });
    }

    public function refundSeatFare(Booking $booking, float $amount, int $userId, ?string $reason = null): ?Refund
    {
        if ($amount <= 0) {
            return null;
        }

        return DB::transaction(function () use ($booking, $amount, $userId, $reason) {
            $payment = $booking->payments()
                ->where('status', PaymentStatus::Paid)
                ->latest()
                ->first();

            if (! $payment) {
                return null;
            }

            $refund = $payment->refunds()->create([
                'booking_id' => $booking->id,
                'amount' => $amount,
                'reason' => $reason ?? 'Seat cancellation refund',
                'status' => 'processed',
                'processed_by' => (string) $userId,
                'processed_at' => now(),
            ]);

            $newPaidAmount = max(0, (float) $booking->paid_amount - $amount);

            $booking->update([
                'paid_amount' => $newPaidAmount,
                'payment_status' => match (true) {
                    $newPaidAmount <= 0 => PaymentStatus::Refunded,
                    $newPaidAmount < (float) $booking->total_amount => PaymentStatus::Partial,
                    default => PaymentStatus::Paid,
                },
            ]);

            $totalRefunded = (float) $payment->refunds()->sum('amount');
            if ($totalRefunded >= (float) $payment->amount) {
                $payment->update(['status' => PaymentStatus::Refunded]);
            }

            return $refund;
        });
    }

    public function refund(Payment $payment, float $amount, ?string $reason = null): void
    {
        DB::transaction(function () use ($payment, $amount, $reason) {
            $payment->refunds()->create([
                'booking_id' => $payment->booking_id,
                'amount' => $amount,
                'reason' => $reason,
                'status' => 'processed',
                'processed_at' => now(),
            ]);

            $payment->update(['status' => PaymentStatus::Refunded]);
            $payment->booking->update(['payment_status' => PaymentStatus::Refunded]);
        });
    }

    private function verifyStripe(array $data): bool
    {
        return ! empty($data['payment_intent_id']);
    }

    private function verifyJazzCash(array $data): bool
    {
        return ! empty($data['pp_TxnRefNo']) && ($data['pp_ResponseCode'] ?? '') === '000';
    }

    private function verifyEasypaisa(array $data): bool
    {
        return ! empty($data['orderId']) && ($data['status'] ?? '') === 'SUCCESS';
    }
}
