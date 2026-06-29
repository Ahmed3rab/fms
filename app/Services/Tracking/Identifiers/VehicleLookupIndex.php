<?php

namespace App\Services\Tracking\Identifiers;

use Illuminate\Support\Facades\Redis;

class VehicleLookupIndex
{
    protected const KEY = 'vehicles:index:uuid';
    public const VEHICLE_SET = 'tracking:vehicles';

    /**
     * @param array<string,string> $map
     */
    public function replace(array $map): void
    {
        Redis::pipeline(function ($redis) use ($map) {

            $redis->del(self::KEY);
            $redis->del(self::VEHICLE_SET);

            if ($map === []) {
                return;
            }

            $redis->hMSet(self::KEY, $map);

            $redis->sAdd(
                self::VEHICLE_SET,
                ...array_values($map),
            );
        });
    }

    public function uuidFromDeviceIdentifier(string $uuid): ?string
    {
        $value = Redis::connection()->hGet(
            self::KEY,
            $uuid,
        );

        if ($value === false || $value === null) {
            return null;
        }

        return (string) $value;
    }

}
