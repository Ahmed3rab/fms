<?php

namespace App\Services\WebSocket\Handlers;

use App\Services\WebSocket\Connections\Client;
use App\Services\WebSocket\Handlers\Contracts\MessageHandler;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;
use App\Services\WebSocket\Messages\Incoming\AuthenticateMessage;

class AuthenticateHandler implements MessageHandler
{
    public function __invoke(Client $client, IncomingMessage $message): void
    {

        /** @var AuthenticateMessage $message */

        //
        // Authentication logic will go here
        //
    }
}
