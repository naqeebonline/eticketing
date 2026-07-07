<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_uuid' => 'required|uuid|exists:schedules,uuid',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'payment_method' => 'nullable|in:cash,stripe,jazzcash,easypaisa',
            'payment_amount' => 'nullable|numeric|min:0',
            'gateway_data' => 'nullable|array',
            'passengers' => 'required|array|min:1',
            'passengers.*.seat_id' => 'required|integer|exists:seats,id',
            'passengers.*.full_name' => 'required|string|max:255',
            'passengers.*.cnic' => 'nullable|string|max:20',
            'passengers.*.phone' => 'nullable|string|max:20',
            'passengers.*.email' => 'nullable|email',
            'passengers.*.gender' => 'required|in:male,female',
            'passengers.*.passenger_type' => 'nullable|in:adult,child',
        ];
    }
}
