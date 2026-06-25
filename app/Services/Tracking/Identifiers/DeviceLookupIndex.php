<?php

namespace App\Services\Tracking\Identifiers;

use Illuminate\Support\Facades\Redis;

class DeviceLookupIndex
{
    protected const KEY = 'tracking:index:system-no';

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

    public function uuidFromSystemNo(string $systemNo): ?string
    {
        return Redis::hGet(self::KEY, $systemNo) ?: null;
    }
}
