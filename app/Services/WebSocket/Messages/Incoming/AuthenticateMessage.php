<?php

namespace App\Services\WebSocket\Messages\Incoming;

use App\Enums\WebSocketMessageType;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;

final readonly class AuthenticateMessage extends IncomingMessage
{
    public function __construct(public string $accessToken)
    {
        parent::__construct(
            WebSocketMessageType::Authenticate
        );
    }

    public static function fromArray(array $payload): static
    {
        return new static(
            token: $payload['token'],
        );
    }

}
