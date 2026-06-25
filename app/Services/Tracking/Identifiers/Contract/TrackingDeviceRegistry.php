<?php

namespace App\Services\Tracking\Identifiers\Contract;

use Illuminate\Support\Collection;

interface TrackingDeviceRegistry
{
    public function uuidFromIdentifier(string $identifier): string;

    /**
    * @param Collection<int,Device> $devices
    */
    public function synchronize(Collection $devices): void;
}
