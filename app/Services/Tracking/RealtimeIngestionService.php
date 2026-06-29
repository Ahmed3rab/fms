<?php

namespace App\Services\Tracking;

use App\Data\RealtimeDeviceState;
use App\Gateway\Events\GatewayEventDispatcher;
use App\Gateway\Events\TelemetryEvent;
use App\Services\Tracking\Identifiers\Contract\TrackingVehicleRegistry;
use App\Services\Tracking\Indexers\TrackingStateIndexer;

class RealtimeIngestionService
{
    public function __construct(
        protected GatewayEventDispatcher $eventDispatcher,
        protected DeviceStateStore $store,
        protected StateResolver $resolver,
        protected TrackingVehicleRegistry $vehicles,
        protected TrackingStateIndexer $indexer,
    ) {}

    public function ingestRealTimeState(RealtimeDeviceState $state): void
    {
        if ($state->deviceUuid === null) {
            return;
        }
        $resolved = $this->resolver->realtime($state);
        $this->store->put($resolved);
        try {
            $vehicleUuid = $this->vehicles->uuidFromDevice(
                $resolved->deviceUuid(),
            );

            $this->indexer->index(
                $vehicleUuid,
                $resolved,
            );
        } catch (\RuntimeException $e) {
            logger()->warning(
                'Unable to index realtime vehicle state.',
                [
                    'device_uuid' => $resolved->deviceUuid(),
                    'exception' => $e->getMessage(),
                ],
            );
        }
        $this->eventDispatcher->dispatch(
            new TelemetryEvent($resolved),
        );
    }
}
