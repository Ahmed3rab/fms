<?php

namespace App\Services\Tracking;

use App\Models\Device;

class DeviceResolver
{
    public function uuidFromSystemNo(string $systemNo): ?string
    {
        return Device::where(
            'system_no',
            $systemNo,
        )->value('uuid');
    }
}
