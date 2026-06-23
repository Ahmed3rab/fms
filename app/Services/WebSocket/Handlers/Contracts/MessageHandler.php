<?php

namespace App\Services\WebSocket\Handlers\Contracts;

use App\Services\WebSocket\Connections\ClientConnection;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;

interface MessageHandler
{
    public function __invoke(ClientConnection $connection, IncomingMessage $message): void;
}
