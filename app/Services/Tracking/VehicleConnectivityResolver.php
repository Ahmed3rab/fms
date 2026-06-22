<?php

namespace App\Services\Tracking;

use App\Enums\ConnectivityStatus;
use Illuminate\Support\Carbon;

class VehicleConnectivityResolver
{
    /**
     * @param array<string,mixed>|object|null $state
     */
    public function resolve(array|object|null $state): ConnectivityStatus
    {
        if ($state === null) {
            return ConnectivityStatus::Offline;
        }

        $receivedAt = Carbon::parse(
            data_get($state, 'received_at')
                ?? data_get($state, 'last_synced_at')
        );

        return $receivedAt->gt(now()->subMinutes(5))
            ? ConnectivityStatus::Online
            : ConnectivityStatus::Offline;
    }
}
