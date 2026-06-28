<?php

namespace App\Gateway\Events;

use App\Gateway\Events\Contracts\GatewayEvent;
use App\Gateway\Events\Contracts\GatewayEventHandler;
use RuntimeException;

class GatewayEventDispatcher
{
    /**
     * @param array<class-string<GatewayEvent>, GatewayEventHandler> $handlers
     */
    public function __construct(protected array $handlers) {}

    public function dispatch(GatewayEvent $event): void
    {
        $handler = $this->handlers[$event::class] ?? null;

        if ($handler === null) {
            throw new RuntimeException(
                'No handler registered for ' . $event::class,
            );
        }

        $handler->handle($event);
    }
}
