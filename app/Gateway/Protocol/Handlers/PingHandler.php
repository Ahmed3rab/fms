<?php

namespace App\Gateway\Protocol\Handlers;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Handlers\Contracts\MessageHandler;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Protocol\Messages\Outgoing\PongMessage;

class PingHandler implements MessageHandler
{
    public function handle(Gateway $gateway, Connection $connection, IncomingMessage $message): void
    {
        $connection->client()->heartbeat();

        $gateway->send(
            $connection,
            new PongMessage(now())
        );
    }
}
