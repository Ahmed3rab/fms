<?php

namespace App\Services\WebSocket\Messages\Contracts;

use App\Services\WebSocket\WebSocketClient;

interface HandlesWebSocketMessage
{
    /**
     * @param array<string,mixed> $payload
     */
    public function handle(WebSocketClient $client, array $payload): void;
}
