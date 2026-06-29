<?php

namespace App\Services\Tracking\Repositories;

use App\Data\TrackingStateFilter;
use App\Enums\ConnectivityStatus;
use App\Enums\IgnitionStatus;
use App\Enums\MovementStatus;
use App\Services\Tracking\Indexers\TrackingStateIndex;

class TrackingStateRepository
{
    public function __construct(protected TrackingStateIndex $index) {}

    /**
     * @return list<string>|null
     */
    public function matchingVehicleUuids(TrackingStateFilter $filter): ?array
    {
        $matching = null;

        if ($filter->connection) {
            $matching = $this->intersect(
                $matching,
                match ($filter->connection) {
                    ConnectivityStatus::Online
                        => $this->index->onlineVehicles(),

                    ConnectivityStatus::Offline
                        => $this->index->offlineVehicles(),
                },
            );
        }

        if ($filter->movement) {
            $matching = $this->intersect(
                $matching,
                match ($filter->movement) {
                    MovementStatus::Moving
                        => $this->index->movingVehicles(),

                    MovementStatus::Parked
                        => $this->index->parkedVehicles(),

                    MovementStatus::Idling
                        => $this->index->idlingVehicles(),
                },
            );
        }

        if ($filter->ignition) {
            $matching = $this->intersect(
                $matching,
                match ($filter->ignition) {
                    IgnitionStatus::On
                        => $this->index->ignitionOnVehicles(),

                    IgnitionStatus::Off
                        => $this->index->ignitionOffVehicles(),
                },
            );
        }

        if ($filter->gps !== null) {
            $matching = $this->intersect(
                $matching,
                $filter->gps
                    ? $this->index->gpsVehicles()
                    : $this->index->gpsMissingVehicles(),
            );
        }

        return $matching;
    }

    /**
     * @param list<string>|null $left
     * @param list<string> $right
     * @return list<string>
     */
    protected function intersect(?array $left, array $right): array
    {
        if ($left === null) {
            return $right;
        }

        return array_values(
            array_intersect(
                $left,
                $right,
            ),
        );
    }
}
