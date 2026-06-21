<?php

namespace App\Services\Tracking;

use App\Enums\VehicleStatus;

class VehicleStatusResolver
{
    /**
     * @param array<string,mixed>|object|null $state
     */
    public function resolve(array|object|null $state): VehicleStatus {}
}
