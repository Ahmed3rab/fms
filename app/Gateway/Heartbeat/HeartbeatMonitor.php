<?php

namespace App\Gateway\Heartbeat;

use App\Enums\CloseReason;
use App\Gateway\Connections\ConnectionRepository;
use App\Gateway\Gateway;

class HeartbeatMonitor
{
    public function __construct(protected ConnectionRepository $connections) {}

    public function sweep(Gateway $gateway): void
    {
        foreach ($this->connections->all() as $connection) {
            $client = $connection->client();

            if (! $client->authenticated()) {
                continue;
            }

            if ($client->lastHeartbeat()->isBefore(now()->subSeconds(config('tracking.gateway.heartbeat_idle_time')))) {
                $gateway->disconnectConnection(
                    $connection,
                    CloseReason::HeartbeatTimeout,
                    "Connection closed due to inactivity.",
                );
            }
        }
    }
}
