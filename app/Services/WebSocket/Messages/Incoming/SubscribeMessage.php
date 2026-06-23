<?php

namespace App\Services\WebSocket\Messages\Incoming;

use App\Enums\WebSocketMessageType;
use App\Enums\WebSocketTopic;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;
use App\Services\WebSocket\Subscriptions\Subscription;

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

    public static function fromArray(array $payload): static
    {
        return new static(
            subscriptions: collect($payload['subscriptions'])
                ->map(fn(array $item) => new Subscription(
                    topic: WebSocketTopic::from($item['topic']),
                    identifier: $item['identifier'],
                )),
        );
    }
}
