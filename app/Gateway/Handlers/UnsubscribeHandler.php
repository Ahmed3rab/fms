<?php

namespace App\Gateway\Handlers;

use App\Gateway\Messages\Contracts\IncomingMessage;
use App\Gateway\Connections\ClientConnection;
use App\Gateway\Subscriptions\SubscriptionManager;
use App\Gateway\Handlers\Contracts\MessageHandler;

class UnsubscribeHandler implements MessageHandler
{
    public function __construct(protected SubscriptionManager $subscriptions) {}

    public function __invoke(ClientConnection $connection, IncomingMessage $message): void
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
