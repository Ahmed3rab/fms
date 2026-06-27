<?php

namespace App\Services\Tracking\Identifiers;

use App\Models\Device;
use App\Services\Tracking\Identifiers\Contract\TrackingVehicleRegistry;
use Illuminate\Support\Collection;
use RuntimeException;

class ICruiseTrackingVehicleRegistry implements TrackingVehicleRegistry
{
    public function __construct(protected VehicleLookupIndex $index) {}

    public function uuidFromDevice(string $deviceUuid): string
    {
        $uuid = $this->index->uuidFromDeviceIdentifier($deviceUuid);

        if ($uuid === null) {
            throw new RuntimeException(
                "Unable to resolve vehicle for device [{$deviceUuid}]"
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
            $devices
                ->filter(fn(Device $device) => $device->vehicle)
                ->mapWithKeys(fn(Device $device) => [
                    $device->uuid => $device->vehicle->uuid,
                ])
                ->all(),
        );
    }
}
