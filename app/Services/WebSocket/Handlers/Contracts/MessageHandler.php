<?php

namespace App\Services\WebSocket\Handlers\Contracts;

use App\Services\WebSocket\Connections\Client;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;

interface MessageHandler
{
    public function __invoke(Client $client, IncomingMessage $message): void;
}
