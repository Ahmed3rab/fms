<?php

namespace App\Gateway\Subscriptions;

use App\Enums\WebSocketTopic;

final readonly class Subscription
{
    public function __construct(public WebSocketTopic $topic, public string $identifier) {}

    public function key(): string
    {
        return "{$this->topic->value}:{$this->identifier}";
    }

    public function equals(self $other): bool
    {
        return $this->key() === $other->key();
    }
}
