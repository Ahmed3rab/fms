<?php

namespace App\Gateway\Protocol\Messages\Contracts;

use App\Enums\WebSocketMessageType;

abstract readonly class IncomingMessage
{
    public function __construct(public WebSocketMessageType $type) {}

    /**
    * @param array<string,mixed> $payload
    */
    abstract public static function fromArray(array $payload): static;
}
