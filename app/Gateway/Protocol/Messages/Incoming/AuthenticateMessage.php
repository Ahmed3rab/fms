<?php

namespace App\Gateway\Protocol\Messages\Incoming;

use App\Enums\WebSocketMessageType;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

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
            accessToken: $payload['token'],
        );
    }

}
