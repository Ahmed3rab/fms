<?php

namespace App\Services\WebSocket;

final class SubscriptionBag
{
    /**
     * Vehicle subscriptions.
     *
     * @var array<int,true>
     */
    private array $vehicles = [];

    public function subscribeVehicle(int $vehicleId): void
    {
        $this->vehicles[$vehicleId] = true;
    }

    public function unsubscribeVehicle(int $vehicleId): void
    {
        unset($this->vehicles[$vehicleId]);
    }

    public function isSubscribedToVehicle(int $vehicleId): bool
    {
        return isset($this->vehicles[$vehicleId]);
    }

    /**
     * @return list<int>
     */
    public function vehicles(): array
    {
        return array_keys($this->vehicles);
    }

    public function clear(): void
    {
        $this->vehicles = [];
    }
}
