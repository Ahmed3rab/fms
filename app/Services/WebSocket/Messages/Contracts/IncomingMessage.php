<?php

namespace App\Services\WebSocket\Messages\Contracts;

use App\Enums\WebSocketMessageType;

abstract readonly class IncomingMessage
{
    public function __construct(public WebSocketMessageType $type) {}
}
