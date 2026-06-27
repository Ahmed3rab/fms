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
        return Redis::hGet(self::KEY, $uuid) ?: null;
    }
}
