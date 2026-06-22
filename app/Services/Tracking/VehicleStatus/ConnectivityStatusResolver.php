<?php

namespace App\Services\Tracking\VehicleStatus;

use App\Enums\ConnectivityStatus;
use Illuminate\Support\Carbon;

class ConnectivityStatusResolver
{
    /**
     * @param array<string,mixed>|object|null $state
     */
    public function resolve(array|object|null $state): ConnectivityStatus
    {
        if ($state === null) {
            return ConnectivityStatus::Offline;
        }

        $timestamp = data_get($state, 'received_at')
            ?? data_get($state, 'last_synced_at');

        if ($timestamp === null) {
            return ConnectivityStatus::Offline;
        }

        return Carbon::parse($timestamp)
            ->isAfter(now()->subMinutes(5))
                ? ConnectivityStatus::Online
                : ConnectivityStatus::Offline;
    }
}
