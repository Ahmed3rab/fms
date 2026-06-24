<?php

namespace App\Gateway\Handlers;

use App\Gateway\Connections\ClientConnection;
use App\Gateway\Messages\Outgoing\PongMessage;
use App\Gateway\Messages\Contracts\IncomingMessage;
use App\Gateway\Handlers\Contracts\MessageHandler;

class PingHandler implements MessageHandler
{
    public function __invoke(ClientConnection $connection, IncomingMessage $message): void
    {
        $connection->client->lastHeartbeat = now();

        $connection->send(
            new PongMessage(now())
        );
    }
}
