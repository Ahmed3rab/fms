<?php

namespace App\Gateway\Routing;

use App\Data\ResolvedDeviceState;
use App\Enums\WebSocketTopic;
use App\Gateway\Subscriptions\Subscription;
use App\Services\Tracking\Identifiers\Contract\TrackingVehicleRegistry;

class EventSubscriptionLocator
{
    public function __construct(
        protected TrackingVehicleRegistry $vehicles,
    ) {}

    /**
     * @return iterable<Subscription>
     */
    public function locate(ResolvedDeviceState $state): iterable
    {
        if ($state->deviceUuid() === null) {
            return [];
        }

        try {
            $vehicleUuid = $this->vehicles->uuidFromDevice(
                $state->deviceUuid()
            );
        } catch (\RuntimeException) {
            return [];
        }

        return [
            new Subscription(
                WebSocketTopic::Vehicle,
                $vehicleUuid,
            ),
        ];
    }
}
