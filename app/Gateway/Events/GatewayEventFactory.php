<?php

namespace App\Gateway\Events;

use App\Gateway\Events\Contracts\GatewayEvent;
use InvalidArgumentException;

class GatewayEventFactory
{
    /**
     * @var array<string,class-string<GatewayEvent>>
     */
    protected array $events = [];

    public function __construct()
    {
        $this->events = [
            TelemetryEvent::class::type() => TelemetryEvent::class,
        ];
    }

    public function make(string $json): GatewayEvent
    {
        $payload = json_decode(
            $json,
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        $type = $payload['type'] ?? null;

        if ($type === null) {
            throw new InvalidArgumentException(
                'Missing event type.'
            );
        }

        $class = $this->events[$type]
            ?? throw new InvalidArgumentException(
                "Unknown gateway event [$type]."
            );

        return $class::fromArray($payload);
    }
}
