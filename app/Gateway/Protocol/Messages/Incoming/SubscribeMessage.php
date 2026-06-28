<?php

namespace App\Gateway\Protocol\Messages\Incoming;

use App\Enums\GatewayPermission;
use App\Enums\WebSocketTopic;
use App\Gateway\Exceptions\InvalidPayloadException;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Subscriptions\Subscription;

final readonly class SubscribeMessage extends IncomingMessage
{
    /**
     * @param list<Subscription> $subscriptions
     */
    public function __construct(public array $subscriptions) {}

    public static function type(): string
    {
        return 'subscribe';
    }

    /**
     * @param array<int,mixed> $payload
     */

    public static function fromArray(array $payload): static
    {
        if (! array_key_exists('subscriptions', $payload)) {
            throw new InvalidPayloadException(
                'Missing subscriptions.'
            );
        }

        if (! is_array($payload['subscriptions'])) {
            throw new InvalidPayloadException(
                'Subscriptions must be an array.'
            );
        }

        $subscriptions = [];

        foreach ($payload['subscriptions'] as $index => $item) {

            if (! is_array($item)) {
                throw new InvalidPayloadException(
                    "Subscription {$index} must be an object."
                );
            }

            if (! isset($item['topic'])) {
                throw new InvalidPayloadException(
                    "Subscription {$index} is missing topic."
                );
            }

            if (! isset($item['identifier'])) {
                throw new InvalidPayloadException(
                    "Subscription {$index} is missing identifier."
                );
            }

            if (! is_string($item['identifier'])) {
                throw new InvalidPayloadException(
                    "Subscription {$index} identifier must be a string."
                );
            }

            if (trim($item['identifier']) === '') {
                throw new InvalidPayloadException(
                    "Subscription {$index} identifier cannot be empty."
                );
            }

            $topic = WebSocketTopic::tryFrom($item['topic']);

            if ($topic === null) {
                throw new InvalidPayloadException(
                    "Unknown topic [{$item['topic']}]."
                );
            }

            $subscriptions[] = new Subscription(
                $topic,
                $item['identifier'],
            );
        }

        return new static($subscriptions);
    }

    public static function requiresAuthentication(): bool
    {
        return true;
    }

    public static function requiredPermission(): ?GatewayPermission
    {
        return GatewayPermission::TelemetrySubscribe;
    }
}
