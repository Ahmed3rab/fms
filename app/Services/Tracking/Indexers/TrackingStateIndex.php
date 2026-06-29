<?php

namespace App\Services\Tracking\Indexers;

use App\Enums\ConnectivityStatus;
use App\Enums\IgnitionStatus;
use App\Enums\MovementStatus;
use App\Services\Tracking\Identifiers\VehicleLookupIndex;
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

    /*
    |--------------------------------------------------------------------------
    | Public lookup API
    |--------------------------------------------------------------------------
    */

    /**
     * @return list<string>
     */
    public function onlineVehicles(): array
    {
        return $this->members(
            'connection',
            ConnectivityStatus::Online->value,
        );
    }

    /**
     * @return list<string>
     */
    public function offlineVehicles(): array
    {
        return $this->difference(
            VehicleLookupIndex::VEHICLE_SET,
            $this->key(
                'connection',
                ConnectivityStatus::Online->value,
            ),
        );
    }

    /**
     * @return list<string>
     */
    public function movingVehicles(): array
    {
        return $this->members(
            'movement',
            MovementStatus::Moving->value,
        );
    }

    /**
     * @return list<string>
     */
    public function parkedVehicles(): array
    {
        return $this->members(
            'movement',
            MovementStatus::Parked->value,
        );
    }

    /**
     * @return list<string>
     */
    public function idlingVehicles(): array
    {
        return $this->members(
            'movement',
            MovementStatus::Idling->value,
        );
    }

    /**
     * @return list<string>
     */
    public function ignitionOnVehicles(): array
    {
        return $this->members(
            'ignition',
            IgnitionStatus::On->value,
        );
    }

    /**
     * @return list<string>
     */
    public function ignitionOffVehicles(): array
    {
        return $this->members(
            'ignition',
            IgnitionStatus::Off->value,
        );
    }

    /**
     * @return list<string>
     */
    public function gpsVehicles(): array
    {
        return $this->members(
            'gps',
            'true',
        );
    }

    /**
     * @return list<string>
     */
    public function gpsMissingVehicles(): array
    {
        return $this->difference(
            VehicleLookupIndex::VEHICLE_SET,
            $this->key(
                'gps',
                'true',
            ),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Index writer
    |--------------------------------------------------------------------------
    */

    /**
     * @param callable $callback
     */
    public function pipeline(callable $callback): void
    {
        Redis::pipeline($callback);
    }

    /**
     * @param mixed $redis
     * @param list<string> $possibleValues
     */
    public function replaceInPipeline(
        $redis,
        string $category,
        string $value,
        string $vehicleUuid,
        array $possibleValues,
    ): void {
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

    /*
    |--------------------------------------------------------------------------
    | Internal Redis helpers
    |--------------------------------------------------------------------------
    */

    /**
     * @return list<string>
     */
    protected function members(
        string $category,
        string $value,
    ): array {
        return Redis::sMembers(
            $this->key($category, $value),
        );
    }

    /**
     * @return list<string>
     */
    protected function difference(
        string $first,
        string $second,
    ): array {
        return Redis::sDiff(
            $first,
            $second,
        );
    }
}
