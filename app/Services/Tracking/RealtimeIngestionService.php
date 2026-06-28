<?php

namespace App\Services\Tracking;

use App\Data\RealtimeDeviceState;
use App\Gateway\Events\GatewayEventDispatcher;
use App\Gateway\Events\TelemetryEvent;

class RealtimeIngestionService
{
    public function __construct(
        protected GatewayEventDispatcher $eventDispatcher,
        protected DeviceStateStore $store,
        protected StateResolver $resolver
    ) {}

    public function ingestRealTimeState(RealtimeDeviceState $state): void
    {
        if ($state->deviceUuid === null) {
            return;
        }
        $resolved = $this->resolver->realtime($state);
        $this->store->put($resolved);
        $this->eventDispatcher->dispatch(
            new TelemetryEvent($resolved),
        );
    }
}
