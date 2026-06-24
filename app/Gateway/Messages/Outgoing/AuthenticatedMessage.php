<?php

namespace App\Gateway\Messages\Outgoing;

use App\Enums\WebSocketMessageType;
use App\Gateway\Messages\Contracts\OutgoingMessage;

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
