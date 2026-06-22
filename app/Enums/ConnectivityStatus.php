<?php

namespace App\Enums;

enum ConnectivityStatus: string
{
    case Online = 'online';
    case Offline = 'offline';
}
