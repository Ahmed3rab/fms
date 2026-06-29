<?php

namespace App\Services\Tracking\Identifiers;

use Illuminate\Support\Facades\Redis;

class VehicleLookupIndex
{
    protected const KEY = 'vehicles:index:uuid';

    /**
     * @param array<string,string> $map
     */
    public function replace(array $map): void
    {
        Redis::pipeline(function ($redis) use ($map) {
            $redis->del(self::KEY);

            if ($map !== []) {
                $redis->hMSet(self::KEY, $map);
            }
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
