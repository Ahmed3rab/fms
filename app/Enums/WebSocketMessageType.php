<?php

namespace App\Enums;

enum WebSocketMessageType: string
{
    case Authenticate = 'authenticate';
    case Authenticated = 'authenticated';
    case Subscribe = 'subscribe';
    case Unsubscribe = 'unsubscribe';
    case Telemetry = 'telemetry';
    case Ping = 'ping';
    case Pong = 'pong';
    case Error = 'error';
}
