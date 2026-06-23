<?php

namespace App\Services\WebSocket;

use Illuminate\Support\Collection;

class SubscriptionManager
{
    /**
     * Connected websocket clients.
     *
     * @var Collection<string, WebSocketClient>
     */
    protected Collection $clients;

    public function __construct()
    {
        $this->clients = collect();
    }

    public function add(WebSocketClient $client): void
    {
        $this->clients->put(
            spl_object_hash($client->connection),
            $client,
        );
    }

    public function remove(WebSocketClient $client): void
    {
        $this->clients->forget(
            spl_object_hash($client->connection),
        );
    }

    /**
     * @return Collection<int,WebSocketClient>
     */
    public function all(): Collection
    {
        return $this->clients->values();
    }

    /**
     * @return Collection<int,WebSocketClient>
     */
    public function authenticated(): Collection
    {
        return $this->clients
            ->filter(fn(WebSocketClient $client) => $client->authenticated())
            ->values();
    }

    /**
     * @return Collection<int,WebSocketClient>
     */
    public function subscribedToVehicle(int $vehicleId): Collection
    {
        return $this->authenticated()
            ->filter(
                fn(WebSocketClient $client)
                    => $client->subscriptions
                        ->isSubscribedToVehicle($vehicleId)
            )
            ->values();
    }

    /**
     * @return Collection<int,WebSocketClient>
     */
    public function subscribedToVehicleForCompany(int $vehicleId, int $companyId): Collection
    {
        return $this->subscribedToVehicle($vehicleId)
            ->filter(fn(WebSocketClient $client) => $client->company?->id === $companyId)
            ->values();
    }
}
