<?php

namespace App\Services\Tracking\VehicleStatus;

use App\Enums\ConnectivityStatus;
use App\Services\Tracking\Contracts\TracksVehicleState;

class ConnectivityStatusResolver
{
    /**
     * @param array<string,mixed>|object|null $state
     */
    public function resolve(?TracksVehicleState $state): ConnectivityStatus
    {
        if ($state === null) {
            return ConnectivityStatus::Offline;
        }

        $timestamp = $state->receivedAt()
            ?? $state->lastSyncedAt();

        if ($timestamp === null) {
            return ConnectivityStatus::Offline;
        }

        return $timestamp->isAfter(now()->subMinutes(5)) ? ConnectivityStatus::Online : ConnectivityStatus::Offline;
    }
}
