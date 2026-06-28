<?php

namespace App\Gateway\Protocol\Messages\Contracts;

abstract readonly class IncomingMessage
{
    abstract public static function type(): string;

    /**
    * @param array<string,mixed> $payload
    */
    abstract public static function fromArray(array $payload): static;

    public static function requiresAuthentication(): bool
    {
        return true;
    }
}
