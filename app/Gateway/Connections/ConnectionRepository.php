<?php

namespace App\Gateway\Connections;

class ConnectionRepository
{
    /**
     * @var array<int, Connection>
     */
    protected array $connections = [];

    public function put(Connection $connection): void
    {
        $this->connections[$connection->id()] = $connection;
    }

    public function get(int $connectionId): ?Connection
    {
        return $this->connections[$connectionId] ?? null;
    }

    public function forget(int $connectionId): void
    {
        unset($this->connections[$connectionId]);
    }

    /**
     * @return iterable<Connection>
     */
    public function all(): iterable
    {
        yield from $this->connections;
    }

    public function has(int $connectionId): bool
    {
        return isset($this->connections[$connectionId]);
    }
}
