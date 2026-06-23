<?php

namespace App\Services\WebSocket\Messages\Incoming;

use App\Enums\WebSocketMessageType;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;

final readonly class PingMessage extends IncomingMessage
{
    public function __construct()
    {
        parent::__construct(
            WebSocketMessageType::Ping
        );
    }
}
