<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Held = 'held';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case Refunded = 'refunded';
}
