<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'transaction_id' => $this->transaction_id,
            'method' => $this->method?->value ?? $this->method,
            'amount' => $this->amount,
            'status' => $this->status?->value ?? $this->status,
            'paid_at' => $this->paid_at?->toIso8601String(),
        ];
    }
}
