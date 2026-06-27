<?php

namespace App\Services\Tracking;

use App\Data\RealtimeDeviceState;
use App\Gateway\Realtime\GatewayDispatcher;

class RealtimeIngestionService
{
    public function __construct(
        protected GatewayDispatcher $dispatcher,
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
        $this->dispatcher->dispatch($resolved);
    }
}
