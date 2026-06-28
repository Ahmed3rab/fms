<?php

namespace App\Gateway\Protocol\Messages\Incoming;

use App\Gateway\Exceptions\InvalidPayloadException;
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
    public static function fromArray(array $payload): static
    {
        if (! array_key_exists('token', $payload)) {
            throw new InvalidPayloadException(
                'Missing token.'
            );
        }

        if (! is_string($payload['token'])) {
            throw new InvalidPayloadException(
                'Token must be a string.'
            );
        }

        if (trim($payload['token']) === '') {
            throw new InvalidPayloadException(
                'Token cannot be empty.'
            );
        }

        return new static(
            accessToken: $payload['token'],
        );
    }

    public static function requiresAuthentication(): bool
    {
        return false;
    }
}
