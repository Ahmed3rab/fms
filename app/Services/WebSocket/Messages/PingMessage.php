<?php

namespace App\Services\WebSocket\Messages;

use App\Services\WebSocket\Messages\Contracts\HandlesWebSocketMessage;
use App\Services\WebSocket\WebSocketClient;

class PingMessage implements HandlesWebSocketMessage
{
    public function type(): string
    {
        return 'ping';
    }

    public function handle(WebSocketClient $client, array $payload): void
    {
        $client->heartbeat();

        $client->connection->write(
            json_encode(['type' => 'pong'])
        );
    }
}
