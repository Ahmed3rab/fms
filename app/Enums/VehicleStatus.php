<?php

namespace App\Enums;

enum VehicleStatus: string
{
    case Parked = 'parked';
    case Idling = 'idling';
    case Moving = 'moving';
    case NoGps = 'no_gps';
}
