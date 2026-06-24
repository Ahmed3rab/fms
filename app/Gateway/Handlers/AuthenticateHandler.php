<?php

namespace App\Gateway\Handlers;

use App\Gateway\Connections\ClientConnection;
use App\Gateway\Messages\Contracts\IncomingMessage;
use App\Gateway\Handlers\Contracts\MessageHandler;

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
