<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'identifier', 'type', 'otp', 'expires_at', 'verified',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified' => 'boolean',
        ];
    }

    public function isValid(string $otp): bool
    {
        return ! $this->verified
            && $this->otp === $otp
            && $this->expires_at->isFuture();
    }
}
