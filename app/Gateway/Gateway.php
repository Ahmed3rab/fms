<?php

namespace App\Gateway;

use App\Gateway\Connections\Connection;

class Gateway
{
    public function connect(Connection $connection): void {}

    public function receive(Connection $connection, string $message): void {}

    public function disconnect(Connection $connection): void {}
}
