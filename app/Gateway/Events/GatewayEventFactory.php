<?php

namespace App\Gateway\Events;

use App\Gateway\Events\Contracts\GatewayEvent;
use InvalidArgumentException;

class GatewayEventFactory
{
    public function __construct(
        protected GatewayEventRegistry $registry,
    ) {}

    public function make(string $json): GatewayEvent
    {
        $payload = json_decode(
            $json,
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        if (! isset($payload['type'])) {
            throw new InvalidArgumentException(
                'Missing event type.'
            );
        }

        $class = $this->registry->resolve(
            $payload['type'],
        );

        return $class::fromArray($payload);
    }
}
