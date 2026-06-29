<?php

namespace App\Services\Tracking\Indexers;

use Illuminate\Support\Facades\Redis;

class TrackingStateIndex
{
    protected const PREFIX = 'tracking:state';

    protected function key(string $category, string $value): string
    {
        return sprintf(
            '%s:%s:%s',
            self::PREFIX,
            $category,
            $value,
        );
    }

    /**
     * @param callable $callback
     */
    public function pipeline(callable $callback): void
    {
        Redis::pipeline($callback);
    }

    /**
     * @param list<string> $possibleValues
     * @param mixed $redis
     */
    public function replaceInPipeline($redis, string $category, string $value, string $vehicleUuid, array $possibleValues): void
    {
        foreach ($possibleValues as $candidate) {
            $redis->sRem(
                $this->key($category, $candidate),
                $vehicleUuid,
            );
        }

        $redis->sAdd(
            $this->key($category, $value),
            $vehicleUuid,
        );
    }


    /**
     * @return list<string>
     */
    public function members(string $category, string $value): array
    {
        return Redis::sMembers(
            $this->key($category, $value),
        );
    }

    /**
     * @param list<string> $keys
     * @return list<string>
     */
    public function intersect(array $keys): array
    {
        return Redis::sInter(
            ...$keys,
        );
    }
}
