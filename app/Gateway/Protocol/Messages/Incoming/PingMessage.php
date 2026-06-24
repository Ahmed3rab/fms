<?php

namespace App\Gateway\Protocol\Messages\Incoming;

use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

final readonly class PingMessage extends IncomingMessage
{
    public static function type(): string
    {
        return 'ping';
    }

    /**
     * @param array<int,mixed> $payload
     */
    public static function fromArray(array $payload): PingMessage
    {
        return new static();
    }
}
