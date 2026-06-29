<?php

namespace App\Services\Tracking\Indexers;

use App\Data\ResolvedDeviceState;
use App\Enums\ConnectivityStatus;
use App\Enums\IgnitionStatus;
use App\Enums\MovementStatus;

class TrackingStateIndexer
{
    public function __construct(protected TrackingStateIndex $index) {}

    public function index(string $vehicleUuid, ResolvedDeviceState $state): void
    {

        $this->index->pipeline(function ($redis) use (
            $vehicleUuid,
            $state
        ) {

            $this->index->replaceInPipeline(
                $redis,
                'connection',
                $state->status->connection->value,
                $vehicleUuid,
                $this->values(
                    ConnectivityStatus::cases(),
                ),
            );

            $this->index->replaceInPipeline(
                $redis,
                'movement',
                $state->status->movement->value,
                $vehicleUuid,
                $this->values(
                    MovementStatus::cases(),
                ),
            );

            $this->index->replaceInPipeline(
                $redis,
                'ignition',
                $state->ignition()?->status->value ?? 'unknown',
                $vehicleUuid,
                [
                    ...$this->values(
                        IgnitionStatus::cases(),
                    ),
                    'unknown',
                ],
            );

            $this->index->replaceInPipeline(
                $redis,
                'gps',
                $state->gpsStatus()
                    ? 'true'
                    : 'false',
                $vehicleUuid,
                [
                    'true',
                    'false',
                ],
            );
        });
    }

    /**
     * @param array<int,\BackedEnum> $cases
     * @return list<string>
     */
    protected function values(array $cases): array
    {
        return collect($cases)
            ->pluck('value')
            ->all();
    }
}
