<?php

namespace App\Gateway\Protocol\Handlers;

use App\Gateway\Connections\Connection;
use App\Gateway\Protocol\Handlers\Contracts\MessageHandler;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Protocol\Messages\Outgoing\PongMessage;

class PingHandler implements MessageHandler
{
    public function __invoke(Connection $connection, IncomingMessage $message): void
    {
        $connection->client->lastHeartbeat = now();

        $connection->send(
            new PongMessage(now())
        );
    }
}
