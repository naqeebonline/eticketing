<?php

namespace App\Services\Auth;

use App\Models\OtpVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpService
{
    public function generate(string $identifier, string $type = 'email'): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::where('identifier', $identifier)
            ->where('type', $type)
            ->delete();

        OtpVerification::create([
            'identifier' => $identifier,
            'type' => $type,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        if ($type === 'email') {
            Mail::raw("Your OTP is: {$otp}", function ($message) use ($identifier) {
                $message->to($identifier)->subject('Verification Code');
            });
        }

        return $otp;
    }

    public function verify(string $identifier, string $otp, string $type = 'email'): bool
    {
        $record = OtpVerification::where('identifier', $identifier)
            ->where('type', $type)
            ->latest()
            ->first();

        if (! $record?->isValid($otp)) {
            return false;
        }

        $record->update(['verified' => true]);

        return true;
    }
}
