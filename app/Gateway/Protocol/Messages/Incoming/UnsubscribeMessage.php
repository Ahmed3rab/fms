<?php

namespace App\Gateway\Protocol\Messages\Incoming;

use App\Enums\WebSocketTopic;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Subscriptions\Subscription;

final readonly class UnsubscribeMessage extends IncomingMessage
{
    /**
     * @param list<Subscription> $subscriptions
     */
    public function __construct(public array $subscriptions) {}

    public static function type(): string
    {
        return 'unsubscribe';
    }

    /**
     * @param array<string,mixed> $payload
     */
    public static function fromArray(array $payload): static
    {
        return new static(
            subscriptions: collect($payload['subscriptions'])
                ->map(
                    fn(array $item) => new Subscription(
                        topic: WebSocketTopic::from($item['topic']),
                        identifier: $item['identifier'],
                    )
                )->all(),
        );
    }
}
