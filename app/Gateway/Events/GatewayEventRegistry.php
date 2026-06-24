<?php

namespace App\Gateway\Events;

use App\Gateway\Events\Contracts\GatewayEvent;
use InvalidArgumentException;

class GatewayEventRegistry
{
    /**
     * @var array<string, class-string<GatewayEvent>>
     */
    protected array $events = [];

    /**
     * @param class-string<GatewayEvent> $event
     */
    public function register(string $event): void
    {
        $this->events[$event::type()] = $event;
    }

    /**
     * @return class-string<GatewayEvent>
     */
    public function resolve(string $type): string
    {
        return $this->events[$type]
            ?? throw new InvalidArgumentException(
                "Unknown gateway event [{$type}]."
            );
    }
}
