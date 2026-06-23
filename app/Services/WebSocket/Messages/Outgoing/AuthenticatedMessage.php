<?php

namespace App\Services\WebSocket\Messages\Outgoing;

use App\Enums\WebSocketMessageType;
use App\Services\WebSocket\Messages\Contracts\OutgoingMessage;

final readonly class AuthenticatedMessage extends OutgoingMessage
{
    public function __construct()
    {
        parent::__construct(
            WebSocketMessageType::Authenticated,
        );
    }

    protected function payload(): array
    {
        return [];
    }
}
