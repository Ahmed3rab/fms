<?php

namespace App\Services\WebSocket;

use React\Socket\ConnectionInterface;
use SplObjectStorage;

class TrackingBroadcaster
{
    /**
     * @var SplObjectStorage<ConnectionInterface, null>
     */
    protected SplObjectStorage $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }

    public function attach(ConnectionInterface $connection): void
    {
        $this->connections->attach($connection);
    }

    public function detach(ConnectionInterface $connection): void
    {
        $this->connections->detach($connection);
    }

    /**
     * @param array<string,mixed> $payload
     */
    public function broadcast(array $payload): void
    {
        $message = json_encode($payload);

        foreach ($this->connections as $connection) {
            $connection->send($message);
        }
    }
}
