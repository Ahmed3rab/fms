<?php

namespace App\Gateway\Events\Contracts;

interface GatewayEventHandler
{
    public function handle(GatewayEvent $event): void;
}
