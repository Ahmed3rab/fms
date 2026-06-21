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

    /**
     * @param array<int,string> $systemNos
     * @return array<string,array<string,mixed>>
     */
    public function many(array $systemNos): array
    {
        if ($systemNos === []) {
            return [];
        }

        $keys = array_map(
            fn(string $systemNo) => "device-state:{$systemNo}",
            $systemNos,
        );

        $values = Redis::mget($keys);

        $states = [];

        foreach ($systemNos as $index => $systemNo) {
            if (! $values[$index]) {
                continue;
            }

            $states[$systemNo] = json_decode($values[$index], true);
        }

        return $states;
    }

    public function forget(string $systemNo): void
    {
        Redis::del(
            "device-state:{$systemNo}"
        );
    }

    public function getByDevice(Device $device): ?array
    {
        return $this->get($device->system_no);
    }
}
