<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;
use App\Gateway\Subscriptions\Subscription;

final readonly class SubscribedMessage extends OutgoingMessage
{
    public function __construct(public Subscription $subscription) {}

    public static function type(): string
    {
        return 'subscribed';
    }

    protected function data(): array
    {
        return [
            'subscription' => [
                'topic' => $this->subscription->topic->value,
                'identifier' => $this->subscription->identifier,
            ],
        ];
    }
}
