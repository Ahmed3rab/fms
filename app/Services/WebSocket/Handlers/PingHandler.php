<?php

namespace App\Services\WebSocket\Handlers;

use App\Services\WebSocket\Connections\ClientConnection;
use App\Services\WebSocket\Handlers\Contracts\MessageHandler;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;
use App\Services\WebSocket\Messages\Outgoing\PongMessage;

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
