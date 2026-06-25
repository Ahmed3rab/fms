<?php

namespace App\Services\Tracking\Resolvers\Contract;

interface TrackingDeviceResolver
{
    public function uuidFromIdentifier(?string $identifier): string;
}
