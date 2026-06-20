<?php

namespace App\Services\Tracking;

use App\Models\Device;
use Illuminate\Support\Facades\Redis;

class DeviceStateStore
{
    /**
     * @param array<string,mixed> $state
     */
    public function put(string $systemNo, array $state): void
    {
        Redis::setex(
            "device-state:{$systemNo}",
            21600, // (6) Hours
            json_encode($state)
        );
    }

    /**
     * @return array<string,mixed>|null
     */
    public function get(string $systemNo): ?array
    {
        $value = Redis::get(
            "device-state:{$systemNo}"
        );

        return $value
            ? json_decode($value, true)
            : null;
    }

    public function getByDevice(Device $device): ?array
    {
        return $this->get($device->system_no);
    }

    public function forget(string $systemNo): void
    {
        Redis::del(
            "device-state:{$systemNo}"
        );
    }
}
