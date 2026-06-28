<?php

namespace App\Gateway\Heartbeat;

use App\Enums\CloseReason;
use App\Gateway\Connections\ConnectionRepository;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Messages\Outgoing\CloseMessage;

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

            if ($client->lastHeartbeat()->diffInSeconds(now()) >= 60) {
                $gateway->send(
                    $connection,
                    new CloseMessage(CloseReason::HeartbeatTimeout, "Connection closed due to inactivity."),
                );
                $gateway->disconnectConnection($connection);
            }
        }
    }
}
