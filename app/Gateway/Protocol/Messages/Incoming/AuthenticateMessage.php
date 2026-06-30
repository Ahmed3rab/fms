<?php

namespace App\Gateway\Protocol\Messages\Incoming;

use App\Enums\GatewayPermission;
use App\Gateway\Exceptions\InvalidPayloadException;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

final readonly class AuthenticateMessage extends IncomingMessage
{
    public function __construct(public string $accessToken) {}

    public static function type(): string
    {
        return 'authenticate';
    }

    public static function fromArray(array $payload): static
    {
        if (! isset($payload['data']) || ! is_array($payload['data'])) {
            throw new InvalidPayloadException(
                'Missing data object.'
            );
        }

        $data = $payload['data'];

        if (! array_key_exists('token', $data)) {
            throw new InvalidPayloadException(
                'Missing token.'
            );
        }

        if (! is_string($data['token'])) {
            throw new InvalidPayloadException(
                'Token must be a string.'
            );
        }

        if (trim($data['token']) === '') {
            throw new InvalidPayloadException(
                'Token cannot be empty.'
            );
        }

        return new static(
            accessToken: $data['token'],
        );
    }

    public static function requiresAuthentication(): bool
    {
        return false;
    }

    public static function requiredPermission(): ?GatewayPermission
    {
        return null;
    }
}
