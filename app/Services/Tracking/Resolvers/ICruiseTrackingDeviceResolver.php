<?php

namespace App\Services\Tracking\Resolvers;

use App\Models\Device;
use App\Services\Tracking\Resolvers\Contract\TrackingDeviceResolver;

class ICruiseTrackingDeviceResolver implements TrackingDeviceResolver
{
    public function resolve(?string $identifier): ?Device
    {
        return Device::query()->where(
            'system_no',
            $identifier,
        )->firstOrFail();
    }
}
