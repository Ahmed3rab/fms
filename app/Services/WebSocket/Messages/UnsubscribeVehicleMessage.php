<?php

namespace App\Services\WebSocket\Messages;

use App\Services\WebSocket\Messages\Contracts\HandlesWebSocketMessage;
use App\Services\WebSocket\WebSocketClient;

class UnsubscribeVehicleMessage implements HandlesWebSocketMessage
{
    public const TYPE = 'unsubscribe';

    /**
     * @param array<string,mixed> $payload
     */
    public function handle(WebSocketClient $client, array $payload): void
    {
        $vehicleId = filter_var(
            $payload['vehicle_id'] ?? null,
            FILTER_VALIDATE_INT,
        );

        if ($vehicleId === false) {
            return;
        }

        $client->subscriptions->unsubscribeVehicle($vehicleId);

        $client->connection->write(json_encode([
            'type' => 'unsubscribed',
            'vehicle_id' => $vehicleId,
        ]));
    }
}
