<?php

namespace App\Gateway\Protocol\Handlers;

use App\Gateway\Connections\Connection;
use App\Gateway\Protocol\Handlers\Contracts\MessageHandler;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

class AuthenticateHandler implements MessageHandler
{
    public function __invoke(Connection $connection, IncomingMessage $message): void {}
}
