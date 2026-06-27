<?php

namespace App\Gateway;

use App\Services\ICruise\Realtime\ICruiseRealtimeClient;

class GatewayRuntime
{
    public function __construct(
        protected Gateway $gateway,
        protected ICruiseRealtimeClient $realtime,
    ) {}

    public function start(): void
    {
        $this->gateway->start(
            fn() => $this->boot(),
        );
    }

    protected function boot(): void
    {
        $this->realtime->connect();
    }
}
