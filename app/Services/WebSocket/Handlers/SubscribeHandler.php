<?php

namespace App\Services\WebSocket\Handlers;

use App\Services\WebSocket\Connections\ClientConnection;
use App\Services\WebSocket\Handlers\Contracts\MessageHandler;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;
use App\Services\WebSocket\Messages\Incoming\SubscribeMessage;
use App\Services\WebSocket\Subscriptions\SubscriptionManager;

class SubscribeHandler implements MessageHandler
{
    public function __construct(protected SubscriptionManager $subscriptions) {}

    public function __invoke(ClientConnection $connection, IncomingMessage $message): void
    {
        /** @var SubscribeMessage $message */
        foreach ($message->subscriptions as $subscription) {
            $this->subscriptions->subscribe(
                $connection->client,
                $subscription,
            );
        }
    }
}
