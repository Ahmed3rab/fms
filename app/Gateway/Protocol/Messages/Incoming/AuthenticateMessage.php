<?php

namespace App\Gateway\Protocol\Messages\Incoming;

use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

final readonly class AuthenticateMessage extends IncomingMessage
{
    public function __construct(public string $accessToken) {}

    public static function type(): string
    {
        return 'authenticate';
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

    public static function requiresAuthentication(): bool
    {
        return false;
    }
}
