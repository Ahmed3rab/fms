<?php

namespace App\Gateway\Routing;

use App\Gateway\Messages\Contracts\IncomingMessage;
use App\Gateway\Connections\ClientConnection;

class MessageRouter
{
    /**
     * @param array<class-string<IncomingMessage>, MessageHandler> $handlers
     */
    public function __construct(protected array $handlers) {}

    public function route(ClientConnection $connection, IncomingMessage $message): void
    {
        $handler = $this->handlers[$message::class] ?? null;
        if ($handler === null) {
            return;
        }
        $handler($connection, $message);
    }
}
