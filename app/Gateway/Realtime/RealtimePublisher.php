<?php

namespace App\Gateway\Realtime;

use App\Data\RealtimeDeviceState;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Messages\Outgoing\TelemetryMessage;
use App\Gateway\Subscriptions\Subscription;
use App\Gateway\Subscriptions\SubscriptionManager;
use App\Enums\WebSocketTopic;
use App\Models\Device;

class RealtimePublisher
{
    public function __construct(protected Gateway $gateway, protected SubscriptionManager $subscriptions) {}

    public function publish(RealtimeDeviceState $state): void
    {
        if ($state->deviceUuid === null) {
            return;
        }

        $device = Device::query()
            ->whereUuid($state->deviceUuid)
            ->with('vehicle:id,uuid')
            ->first();

        if (! $device?->vehicle) {
            return;
        }

        $subscription = new Subscription(
            WebSocketTopic::Vehicle,
            (string) $device->vehicle->uuid,
        );
        logger()->info('Publisher manager', [
            'object' => spl_object_id($this->subscriptions),
        ]);
        foreach ($this->subscriptions->subscribers($subscription) as $client) {
            logger()->info('Publishing to subscriber', [
                'connection' => $client->connection()->id(),
            ]);

            $this->gateway->send(
                $client->connection(),
                new TelemetryMessage($subscription, $state),
            );
        }
    }

}
