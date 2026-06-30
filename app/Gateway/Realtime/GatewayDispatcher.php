<?php

namespace App\Gateway\Realtime;

use App\Data\ResolvedDeviceState;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Messages\Outgoing\TelemetryMessage;
use App\Gateway\Routing\EventSubscriptionLocator;
use App\Gateway\Subscriptions\SubscriptionManager;

class GatewayDispatcher
{
    public function __construct(
        protected Gateway $gateway,
        protected SubscriptionManager $subscriptions,
        protected EventSubscriptionLocator $locator,
    ) {}

    public function dispatch(ResolvedDeviceState $state): void
    {
        foreach ($this->locator->locate($state) as $subscription) {
            foreach ($this->subscriptions->subscribers($subscription) as $client) {
                $this->gateway->send(
                    $client->connection(),
                    new TelemetryMessage(
                        $subscription,
                        $state,
                        $subscription->identifier
                    ),
                );
            }
        }
    }
}
