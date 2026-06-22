<?php

namespace App\Enums;

enum DeviceStateSource: string
{
    case Realtime = 'realtime';
    case Database = 'database';
}
