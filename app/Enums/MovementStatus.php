<?php

namespace App\Enums;

enum MovementStatus: string
{
    case Parked = 'parked';
    case Idling = 'idling';
    case Moving = 'moving';
    case NoGps = 'no_gps';
}
