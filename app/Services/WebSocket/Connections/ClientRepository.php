<?php

namespace App\Services\WebSocket\Connections;

class ClientRepository
{
    /**
     * @var array<string,Client>
     */
    protected array $clients = [];

    public function put(string $connectionId, Client $client): void
    {
        $this->clients[$connectionId] = $client;
    }

    public function get(string $connectionId): ?Client
    {
        return $this->clients[$connectionId] ?? null;
    }

    public function forget(string $connectionId): void
    {
        unset($this->clients[$connectionId]);
    }

    /**
     * @return iterable<Client>
     */
    public function all(): iterable
    {
        yield from $this->clients;
    }

    public function has(string $connectionId): bool
    {
        return isset($this->clients[$connectionId]);
    }
}
