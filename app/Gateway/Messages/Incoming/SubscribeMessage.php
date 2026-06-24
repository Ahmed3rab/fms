<?php

namespace App\Gateway\Messages\Incoming;

use App\Enums\WebSocketMessageType;
use App\Enums\WebSocketTopic;
use App\Gateway\Messages\Contracts\IncomingMessage;
use App\Gateway\Subscriptions\Subscription;

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
    /**
     * @param array<int,mixed> $payload
     */
    public static function fromArray(array $payload): SubscribeMessage
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
