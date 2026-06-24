<?php

namespace App\Gateway\Protocol\Routing;

use App\Gateway\Connections\Connection;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;

class MessageRouter
{
    /**
     * @param array<class-string<IncomingMessage>, MessageHandler> $handlers
     */
    public function __construct(protected array $handlers) {}

    public function route(Connection $connection, IncomingMessage $message): void
    {
        $handler = $this->handlers[$message::class] ?? null;
        if ($handler === null) {
            return;
        }
        $handler($connection, $message);
    }
}
