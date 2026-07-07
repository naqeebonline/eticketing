<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'booking_number' => $this->booking_number,
            'status' => $this->status?->value ?? $this->status,
            'payment_status' => $this->payment_status?->value ?? $this->payment_status,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'qr_code' => $this->when($this->status?->value === 'confirmed', $this->qr_code),
            'hold_expires_at' => $this->hold_expires_at?->toIso8601String(),
            'confirmed_at' => $this->confirmed_at?->toIso8601String(),
            'schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'passengers' => $this->whenLoaded('passengers', fn () => $this->passengers->map(fn ($p) => [
                'full_name' => $p->full_name,
                'gender' => $p->gender,
                'passenger_type' => $p->passenger_type,
                'fare' => $p->fare,
                'seat' => $p->relationLoaded('seat') ? [
                    'seat_number' => $p->seat->seat_number,
                    'type' => $p->seat->type,
                ] : null,
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
