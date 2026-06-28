<?php

namespace App\Gateway\Protocol\Messages\Contracts;

use App\Enums\GatewayPermission;

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

    public static function requiredPermission(): ?GatewayPermission
    {
        return null;
    }
}
