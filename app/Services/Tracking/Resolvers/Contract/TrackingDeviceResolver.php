<?php

namespace App\Services\Tracking\Resolvers\Contract;

interface TrackingDeviceResolver
{
    public function uuidFromProvider(?string $identifier): string;
}
