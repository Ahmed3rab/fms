<?php

namespace App\Gateway;

use App\Gateway\PubSub\GatewaySubscriber;
use Illuminate\Contracts\Container\Container;
use OpenSwoole\Coroutine;

class GatewayRuntime
{
    public function __construct(
        protected Gateway $gateway,
        protected Container $container,
    ) {}

    public function start(): void
    {
        Coroutine::create(function () {
            $this->container
                ->make(GatewaySubscriber::class)
                ->listen();
        });

        $this->gateway->start();
    }
}
