<?php

namespace App\Services\WebSocket\Messages;

use App\Services\WebSocket\Messages\Contracts\HandlesWebSocketMessage;
use App\Services\WebSocket\WebSocketClient;

class MessageDispatcher
{
    /**
     * @param iterable<HandlesWebSocketMessage> $handlers
     */
    public function __construct(protected iterable $handlers) {}

    /**
     * @param array<string,mixed> $payload
     */
    public function dispatch(WebSocketClient $client, array $payload): void
    {
        $type = $payload['type'] ?? null;

        foreach ($this->handlers as $handler) {
            if ($handler->type() !== $type) {
                continue;
            }

            $handler->handle($client, $payload);

            return;
        }
    }
}
