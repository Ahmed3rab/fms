<?php

namespace App\Gateway;

use App\Gateway\Connections\Connection;
use App\Gateway\Connections\ConnectionRepository;
use App\Gateway\Transport\Contracts\GatewayTransport;

class Gateway
{
    public function __construct(protected GatewayTransport $transport, protected ConnectionRepository $connections) {}

    public function start(): void
    {
        $this->transport->start($this);
    }

    public function connect(Connection $connection): void
    {
        $this->connections->put($connection);

        logger()->info('Gateway connected', [
            'connection' => $connection->id(),
            'ip' => $connection->ip(),
        ]);
    }

    public function receive(Connection $connection, string $message): void
    {
        logger()->info('Gateway received message', [
            'connection' => $connection->id(),
            'message' => $message,
        ]);
    }

    public function disconnect(Connection $connection): void
    {
        $this->connections->forget($connection->id());

        logger()->info('Gateway disconnected', [
            'connection' => $connection->id(),
        ]);
    }

    public function connection(int $id): ?Connection
    {
        return $this->connections->get($id);
    }
}
