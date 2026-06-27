<?php

namespace App\Gateway\PubSub;

use App\Data\RealtimeDeviceState;
use App\Gateway\Realtime\GatewayDispatcher;
use OpenSwoole\Coroutine\Redis;

class GatewaySubscriber
{
    public function __construct(protected GatewayDispatcher $dispatcher) {}

    public function listen(): void
    {
        logger()->info('Opening redis subscription');
        Redis::connection('default')->subscribe(
            ['tracking:realtime'],
            function (string $payload) {
                $this->handle($payload);
            },
        );
    }

    protected function handle(string $payload): void
    {
        logger()->info('Gateway event received');
        $event = json_decode($payload, true);

        if (! is_array($event)) {
            return;
        }

        if (($event['type'] ?? null) !== 'telemetry') {
            return;
        }

        $this->dispatcher->dispatch(
            RealtimeDeviceState::fromArray($event['state']),
        );
    }
}
