<?php

namespace App\Services\WebSocket\Messages\Incoming;

use App\Enums\WebSocketMessageType;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;
use App\Services\WebSocket\Subscription;

final readonly class SubscribeMessage extends IncomingMessage
{
    /**
     * @param list<Subscription> $subscriptions
     */
    public function __construct(public array $subscriptions)
    {
        parent::__construct(
            WebSocketMessageType::Subscribe
        );
    }
}
