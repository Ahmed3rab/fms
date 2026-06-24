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
    public function put(RealtimeDeviceState $state): void
    {
        Redis::connection('default')->setex(
            "device-state:{$state->deviceUuid()}",
            21600, // (6) Hours
            json_encode($state)
        );
    }

    /**
     * @return array<string,mixed>|null
     */
    public function get(string $deviceUuid): ?RealtimeDeviceState
    {
        $value = Redis::connection('default')->get(
            "device-state:{$deviceUuid}"
        );
        if (! $value) {
            return null;
        }

        return RealtimeDeviceState::fromArray(
            json_decode($value, true)
        );
    }

    /**
     * @param array<int,string> $deviceUuids
     * @return array<string, RealtimeDeviceState>
     */
    public function many(array $deviceUuids): array
    {
        if ($deviceUuids === []) {
            return [];
        }

        $keys = array_map(
            fn(string $deviceUuid) => "device-state:{$deviceUuid}",
            $deviceUuids,
        );

        $values = Redis::connection('default')->mget($keys);

        $states = [];

        foreach ($deviceUuids as $index => $deviceUuid) {
            if (! $values[$index]) {
                continue;
            }
            $states[$deviceUuid] = RealtimeDeviceState::fromArray(
                json_decode($values[$index], true)
            );
        }

        return $states;
    }

    public function forget(string $deviceUuid): void
    {
        Redis::connection('default')->del(
            "device-state:{$deviceUuid}"
        );
    }

    public function getByDevice(Device $device): ?RealtimeDeviceState
    {
        return $this->get($device->uuid);
    }
}
