<?php

namespace App\Gateway\Transport\Contracts;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;

interface GatewayTransport
{
    public function start(Gateway $gateway): void;

    public function stop(): void;

    public function send(Connection $connection, string $payload): void;

    public function disconnect(Connection $connection): void;
}
