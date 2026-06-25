<?php

namespace App\Services\Tracking\Resolvers;

use App\Models\Device;
use App\Services\Tracking\Resolvers\Contract\TrackingDeviceResolver;

class ICruiseTrackingDeviceResolver implements TrackingDeviceResolver
{
    public function uuidFromProvider(?string $identifier): string
    {
        return Device::query()->where(
            'system_no',
            $identifier,
        )->valueOrFail('uuid');
    }
}
