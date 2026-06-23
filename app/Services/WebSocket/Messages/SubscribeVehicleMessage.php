<?php

namespace App\Services\WebSocket\Messages;

use App\Services\WebSocket\Messages\Contracts\HandlesWebSocketMessage;
use App\Services\WebSocket\WebSocketClient;

class SubscribeVehicleMessage implements HandlesWebSocketMessage
{
    public const TYPE = 'subscribe';

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
            $client->connection->write(json_encode([
                'type' => 'error',
                'message' => 'Invalid vehicle_id.',
            ]));

            return;
        }

        $client->subscriptions->subscribeVehicle($vehicleId);

        $client->connection->write(json_encode([
            'type' => 'subscribed',
            'vehicle_id' => $vehicleId,
        ]));
    }
}
