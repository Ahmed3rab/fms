<?php

namespace App\Gateway\Connections;

class ClientRepository
{
    /**
     * @var array<string, ClientConnection>
     */
    protected array $connections = [];

    public function put(string $connectionId, ClientConnection $connection): void
    {
        $this->connections[$connectionId] = $connection;
    }

    public function get(string $connectionId): ?ClientConnection
    {
        return $this->connections[$connectionId] ?? null;
    }

    public function forget(string $connectionId): void
    {
        unset($this->connections[$connectionId]);
    }

    /**
     * @return iterable<Client>
     */
    public function all(): iterable
    {
        yield from $this->connections;
    }

    public function has(string $connectionId): bool
    {
        return isset($this->connections[$connectionId]);
    }
}
