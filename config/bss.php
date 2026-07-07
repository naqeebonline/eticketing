<?php

return [
    'booking_hold_minutes' => (int) env('BOOKING_HOLD_MINUTES', 10),
    'booking_prefix' => env('BOOKING_PREFIX', 'BSS'),
    'loyalty_points_per_booking' => 10,
    'referral_bonus_points' => 50,
    'supported_locales' => ['en', 'ur', 'ar'],
    'payment_gateways' => [
        'stripe', 'jazzcash', 'easypaisa', 'cash',
    ],
];
