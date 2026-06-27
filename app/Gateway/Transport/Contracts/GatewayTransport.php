<?php

namespace App\Gateway\Transport\Contracts;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;

interface GatewayTransport
{
    /**
     * @param callable(): mixed $boot
     */
    public function start(Gateway $gateway, callable $boot): void;

    public function stop(): void;

    public function send(Connection $connection, string $payload): void;

    public function disconnect(Connection $connection): void;
}
