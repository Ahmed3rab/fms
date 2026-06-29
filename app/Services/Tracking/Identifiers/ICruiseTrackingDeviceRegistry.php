<?php

namespace App\Services\Tracking\Identifiers;

use App\Models\Device;
use App\Services\Tracking\Identifiers\Contract\TrackingDeviceRegistry;
use Illuminate\Support\Collection;
use RuntimeException;

class ICruiseTrackingDeviceRegistry implements TrackingDeviceRegistry
{
    public function __construct(protected DeviceLookupIndex $index) {}

    public function uuidFromIdentifier(string $identifier): string
    {
        $uuid = $this->index->uuidFromDeviceIdentifier($identifier);

        if ($uuid === null) {
            throw new RuntimeException(
                "Unable to resolve device for SystemNo [{$identifier}]"
            );
        }

        return $uuid;
    }

    /**
     * @param Collection<int,Device> $devices
     */
    public function synchronize(Collection $devices): void
    {
        $this->index->replace(
            $devices->mapWithKeys(fn(Device $device) => [
                $device->system_no => $device->uuid,
            ])->all(),
        );
    }
}
