<?php

namespace App\Gateway\Connections;

class ClientRepository
{
    /**
     * @var array<string, Connection>
     */
    protected array $connections = [];

    public function put(string $connectionId, Connection $connection): void
    {
        $this->connections[$connectionId] = $connection;
    }

    public function get(string $connectionId): ?Connection
    {
        return $this->connections[$connectionId] ?? null;
    }

    public function forget(string $connectionId): void
    {
        unset($this->connections[$connectionId]);
    }

    /**
     * @return array<string,Connection>*/
    public function all(): iterable
    {
        yield from $this->connections;
    }

    public function has(string $connectionId): bool
    {
        return isset($this->connections[$connectionId]);
    }
}
