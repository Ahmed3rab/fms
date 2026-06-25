<?php

namespace App\Services\Tracking\Identifiers\Contract;

interface TrackingDeviceResolver
{
    public function uuidFromIdentifier(?string $identifier): string;

    /**
     * @param iterable<int,mixed> $devices
     */
    public function synchronize(iterable $devices): void;
}
