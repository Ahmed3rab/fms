<?php

namespace App\Gateway\PubSub;

use Illuminate\Support\Facades\Redis;

class GatewayPublisher
{
    /**
     * @param array<int,mixed> $payload
     */
    public function publish(string $channel, array $payload): void
    {
        Redis::publish(
            $channel,
            json_encode($payload, JSON_THROW_ON_ERROR),
        );
    }
}
