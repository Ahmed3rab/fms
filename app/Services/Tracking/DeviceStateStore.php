<?php

namespace App\Services\Tracking;

use App\Data\RealtimeDeviceState;
use App\Models\Device;
use Illuminate\Support\Facades\Redis;

class DeviceStateStore
{
    /**
     * @param array<string,mixed> $state
     */
    public function put(string $systemNo, RealtimeDeviceState $state): void
    {
        Redis::connection('default')->setex(
            "device-state:{$systemNo}",
            21600, // (6) Hours
            json_encode($state)
        );
    }

    /**
     * @return array<string,mixed>|null
     */
    public function get(string $systemNo): ?RealtimeDeviceState
    {
        $value = Redis::connection('default')->get(
            "device-state:{$systemNo}"
        );
        if (! $value) {
            return null;
        }

        return RealtimeDeviceState::fromArray(
            json_decode($value, true)
        );
    }

    /**
     * @param array<int,string> $systemNos
     * @return array<string, RealtimeDeviceState>
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

        $values = Redis::connection('default')->mget($keys);

        $states = [];

        foreach ($systemNos as $index => $systemNo) {
            if (! $values[$index]) {
                continue;
            }
            $states[$systemNo] = RealtimeDeviceState::fromArray(
                json_decode($values[$index], true)
            );
        }

        return $states;
    }

    public function forget(string $systemNo): void
    {
        Redis::connection('default')->del(
            "device-state:{$systemNo}"
        );
    }

    public function getByDevice(Device $device): ?RealtimeDeviceState
    {
        return $this->get($device->system_no);
    }
}
