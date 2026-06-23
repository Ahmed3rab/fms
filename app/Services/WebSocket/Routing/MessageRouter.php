<?php

namespace App\Services\WebSocket\Routing;

use App\Services\WebSocket\Connections\Client;
use App\Services\WebSocket\Handlers\Contracts\MessageHandler;
use App\Services\WebSocket\Messages\Contracts\IncomingMessage;

class MessageRouter
{
    /**
     * @param array<class-string<IncomingMessage>, MessageHandler> $handlers
     */
    public function __construct(protected array $handlers) {}

    public function route(Client $client, IncomingMessage $message): void
    {
        $handler = $this->handlers[$message::class] ?? null;
        if ($handler === null) {
            return;
        }
        $handler($client, $message);
    }
}
