<?php

namespace App\Gateway\Protocol\Handlers;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Handlers\Contracts\MessageHandler;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Protocol\Messages\Outgoing\TelemetryMessage;
use App\Gateway\Routing\SubscriptionSnapshotResolver;
use App\Gateway\Subscriptions\SubscriptionManager;

class SubscribeHandler implements MessageHandler
{
    public function __construct(
        protected SubscriptionManager $subscriptions,
        protected SubscriptionSnapshotResolver $subscriptionSnapshotResolver
    ) {}

    public function handle(Gateway $gateway, Connection $connection, IncomingMessage $message): void
    {
        /** @var SubscribeMessage $message */
        foreach ($message->subscriptions as $subscription) {
            $this->subscriptions->subscribe(
                $connection->client(),
                $subscription,
            );

            $snapshot = $this->subscriptionSnapshotResolver->snapshot($subscription);

            if ($snapshot === null) {
                continue;
            }

            $gateway->send(
                $connection,
                new TelemetryMessage(
                    $subscription,
                    $snapshot,
                ),
            );
        }
    }
}
