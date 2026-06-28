<?php

namespace App\Gateway\Protocol\Messages;

use App\Gateway\Exceptions\InvalidJsonException;
use App\Gateway\Exceptions\InvalidPayloadException;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use JsonException;

class MessageFactory
{
    public function __construct(protected MessageRegistry $registry) {}

    /**
     * @return array<string,mixed>
     */
    public function decode(string $json): array
    {
        try {
            $payload = json_decode(
                $json,
                true,
                flags: JSON_THROW_ON_ERROR,
            );
        } catch (JsonException) {
            throw new InvalidJsonException();
        }

        if (! is_array($payload)) {
            throw new InvalidPayloadException(
                'Payload must be a JSON object.'
            );
        }

        if (! isset($payload['type'])) {
            throw new InvalidPayloadException(
                'Missing message type.'
            );
        }

        return $payload;
    }

    /**
     * @param array<string,mixed> $payload
     * @return class-string<IncomingMessage>
     */
    public function resolve(array $payload): string
    {
        return $this->registry->resolve(
            $payload['type']
        );
    }

    /**
     * @param class-string<IncomingMessage> $class
     * @param array<string,mixed> $payload
     */
    public function hydrate(string $class, array $payload): IncomingMessage
    {
        return $class::fromArray($payload);
    }
}
