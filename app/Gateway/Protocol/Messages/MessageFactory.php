<?php

namespace App\Gateway\Protocol\Messages;

use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use InvalidArgumentException;

class MessageFactory
{
    public function __construct(protected MessageRegistry $registry) {}

    public function make(string $json): IncomingMessage
    {
        $payload = json_decode(
            $json,
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        if (! isset($payload['type'])) {
            throw new InvalidArgumentException('Missing message type.');
        }

        $class = $this->registry->resolve($payload['type']);

        if (! method_exists($class, 'fromArray')) {
            throw new InvalidArgumentException(
                "{$class} cannot be constructed from an array."
            );
        }

        return $class::fromArray($payload);
    }
}
