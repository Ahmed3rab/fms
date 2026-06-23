<?php

namespace App\Services\WebSocket\Handlers;

use App\Services\WebSocket\Connections\Client;
use App\Services\WebSocket\Handlers\Contracts\MessageHandler;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;
use App\Services\WebSocket\Messages\Incoming\UnsubscribeMessage;
use App\Services\WebSocket\Subscriptions\SubscriptionManager;

class UnsubscribeHandler implements MessageHandler
{
    public function __construct(protected SubscriptionManager $subscriptions) {}

    public function __invoke(Client $client, IncomingMessage $message): void
    {
        /** @var UnsubscribeMessage $message */
        foreach ($message->subscriptions as $subscription) {
            $this->subscriptions->unsubscribe(
                $client,
                $subscription,
            );
        }
    }
}
