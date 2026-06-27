<?php

namespace App\Gateway\Realtime;

use App\Data\RealtimeDeviceState;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Messages\Outgoing\TelemetryMessage;
use App\Gateway\Subscriptions\Subscription;
use App\Gateway\Subscriptions\SubscriptionManager;
use App\Enums\WebSocketTopic;
use App\Gateway\Transport\Contracts\GatewayTransport;
use App\Services\Tracking\Identifiers\Contract\TrackingVehicleRegistry;

class GatewayDispatcher
{
    public function __construct(
        protected Gateway $gateway,
        protected SubscriptionManager $subscriptions,
        protected GatewayTransport $transport,
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
        $subscribers = iterator_to_array(
            $this->subscriptions->subscribers($subscription)
        );

        foreach ($this->subscriptions->subscribers($subscription) as $client) {
            $this->gateway->send(
                $client->connection(),
                new TelemetryMessage($subscription, $state),
            );
        }
    }

}
