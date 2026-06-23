<?php

namespace App\Services\WebSocket\Handlers;

use App\Services\WebSocket\Connections\Client;
use App\Services\WebSocket\Handlers\Contracts\MessageHandler;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;

class PingHandler implements MessageHandler
{
    public function __invoke(Client $client, IncomingMessage $message): void
    {
        $client->lastHeartbeat = now();

        //
        // Later we'll send a PongMessage
        //
    }
}
