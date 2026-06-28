<?php

namespace App\Gateway\Events\Handlers;

use App\Gateway\Events\Contracts\GatewayEvent;
use App\Gateway\Events\Contracts\GatewayEventHandler;
use App\Gateway\Realtime\GatewayDispatcher;

class TelemetryEventHandler implements GatewayEventHandler
{
    public function __construct(protected GatewayDispatcher $dispatcher) {}

    public function handle(GatewayEvent $event): void
    {
        /** @var TelemetryEvent $event */
        $this->dispatcher->dispatch(
            $event->state,
        );
    }
}
