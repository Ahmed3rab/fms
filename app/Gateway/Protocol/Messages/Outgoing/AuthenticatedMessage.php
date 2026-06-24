<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Enums\WebSocketMessageType;
use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;

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
