<?php

namespace App\Enums;

enum WebSocketTopic: string
{
    case Broadcast = 'broadcast';
    case Vehicle = 'vehicle';
    case Company = 'company';
    case Trip = 'trip';
    case Device = 'device';
    case Alert = 'alert';
    case Fleet = 'fleet';
}
