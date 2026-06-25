<?php

namespace App\Gateway\PubSub;

use App\Gateway\Events\Contracts\GatewayEvent;
use Illuminate\Support\Facades\Redis;

class GatewayPublisher
{
    public function publish(GatewayEvent $event): void
    {
        logger()->info('Publishing GatewayEvent');
        Redis::publish(
            'tracking:realtime',
            $event->toJson(),
        );
    }
}
