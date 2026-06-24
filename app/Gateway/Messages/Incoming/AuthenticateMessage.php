<?php

namespace App\Gateway\Messages\Incoming;

use App\Enums\WebSocketMessageType;
use App\Gateway\Messages\Contracts\IncomingMessage;

final readonly class AuthenticateMessage extends IncomingMessage
{
    public function __construct(public string $accessToken)
    {
        parent::__construct(
            WebSocketMessageType::Authenticate
        );
    }
    /**
     * @param array<int,mixed> $payload
     */
    public static function fromArray(array $payload): AuthenticateMessage
    {
        return new static(
            token: $payload['token'],
        );
    }

}
