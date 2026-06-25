<?php

namespace App\Services\Tracking\Identifiers;

use App\Models\Device;
use App\Services\Tracking\Identifiers\Contract\TrackingDeviceResolver;

class ICruiseTrackingDeviceResolver implements TrackingDeviceResolver
{
    public function uuidFromIdentifier(?string $identifier): string
    {
        return Device::query()->where(
            'system_no',
            $identifier,
        )->valueOrFail('uuid');
    }

    public function synchronize(iterable $devices): void {}
}
