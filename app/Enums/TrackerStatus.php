<?php

namespace App\Enums;

enum TrackerStatus: int
{
    case Offline = 0;
    case Online = 1;
}
