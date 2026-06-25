<?php

namespace App\Services\Tracking\Resolvers\Contract;

use App\Models\Device;

interface TrackingDeviceResolver
{
    public function resolve(?string $identifier): ?Device;
}
