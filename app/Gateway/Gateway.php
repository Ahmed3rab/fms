<?php

namespace App\Gateway;

use App\Gateway\Connections\Connection;
use App\Gateway\Transport\Contracts\GatewayTransport;

class Gateway
{
    public function __construct(protected GatewayTransport $transport) {}

    public function start(): void
    {
        $this->transport->start($this);
    }

    public function connect(Connection $connection): void {}

    public function receive(Connection $connection, string $message): void {}

    public function disconnect(Connection $connection): void {}
}
