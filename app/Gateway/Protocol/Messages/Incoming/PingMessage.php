<?php

namespace App\Gateway\Protocol\Messages\Incoming;

use App\Enums\WebSocketMessageType;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

final readonly class PingMessage extends IncomingMessage
{
    public function __construct()
    {
        parent::__construct(
            WebSocketMessageType::Ping
        );
    }
    /**
     * @param array<int,mixed> $payload
     */
    public static function fromArray(array $payload): PingMessage
    {
        return new static();
    }
}
