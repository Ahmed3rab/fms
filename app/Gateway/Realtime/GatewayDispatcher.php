<?php

namespace App\Gateway\Realtime;

use App\Data\RealtimeDeviceState;
use App\Gateway\Protocol\Messages\Outgoing\TelemetryMessage;
use App\Gateway\Subscriptions\Subscription;
use App\Gateway\Subscriptions\SubscriptionManager;
use App\Enums\WebSocketTopic;
use App\Gateway\Transport\Contracts\GatewayTransport;
use App\Services\Tracking\Identifiers\Contract\TrackingVehicleRegistry;

class GatewayDispatcher
{
    public function __construct(
        protected GatewayTransport $transport,
        protected SubscriptionManager $subscriptions,
        protected TrackingVehicleRegistry $trackingVehicleRegistry
    ) {}

    public function dispatch(RealtimeDeviceState $state): void
    {
        if ($state->deviceUuid === null) {
            return;
        }

        $vehicleUuid = $this->trackingVehicleRegistry->uuidFromDevice($state->deviceUuid());

        if ($vehicleUuid === null) {
            return;
        }

        $subscription = new Subscription(
            WebSocketTopic::Vehicle,
            (string) $vehicleUuid,
        );

        logger()->info('Publisher manager', [
            'object' => spl_object_id($this->subscriptions),
        ]);
        foreach ($this->subscriptions->subscribers($subscription) as $client) {
            logger()->info('Publishing to subscriber', [
                'connection' => $client->connection()->id(),
            ]);

            $this->transport->send(
                $client->connection(),
                new TelemetryMessage($subscription, $state),
            );
        }
    }

}
