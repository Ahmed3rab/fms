<?php

namespace App\Gateway\Heartbeat;

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

            if ($client->lastHeartbeat()->diffInSeconds(now()) < 60) {
                continue;
            }

            $gateway->disconnectConnection($connection);
        }
    }
}
