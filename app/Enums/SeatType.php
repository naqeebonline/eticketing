<?php

namespace App\Enums;

enum SeatType: string
{
    case Seater = 'seater';
    case Sleeper = 'sleeper';
    case Vip = 'vip';
    case Reserved = 'reserved';
    case Male = 'male';
    case Female = 'female';
}
