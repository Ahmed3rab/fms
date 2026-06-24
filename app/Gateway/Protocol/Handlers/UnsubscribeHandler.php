<?php

namespace App\Gateway\Protocol\Handlers;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Handlers\Contracts\MessageHandler;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Subscriptions\SubscriptionManager;

class UnsubscribeHandler implements MessageHandler
{
    public function __construct(protected SubscriptionManager $subscriptions) {}

    public function handle(Gateway $gateway, Connection $connection, IncomingMessage $message): void
    {
        /** @var UnsubscribeMessage $message */
        foreach ($message->subscriptions as $subscription) {
            $this->subscriptions->unsubscribe(
                $connection->client,
                $subscription,
            );
        }
    }
}
