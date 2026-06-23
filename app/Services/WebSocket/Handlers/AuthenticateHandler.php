<?php

namespace App\Services\WebSocket\Handlers;

use App\Services\WebSocket\Connections\ClientConnection;
use App\Services\WebSocket\Handlers\Contracts\MessageHandler;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;

class AuthenticateHandler implements MessageHandler
{
    public function __invoke(ClientConnection $connection, IncomingMessage $message): void
    {

        /** @var AuthenticateMessage $message */

        //
        // Authentication logic will go here
        //
    }
}
