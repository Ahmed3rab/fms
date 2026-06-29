<?php

namespace App\Filters;

use App\Data\TrackingStateFilter;
use App\Enums\ConnectivityStatus;
use App\Enums\IgnitionStatus;
use App\Enums\MovementStatus;

class TrackingStateFilterBuilder
{
    /**
     * @param array<string,mixed> $input
     */
    public function build(array $input): TrackingStateFilter
    {
        return new TrackingStateFilter(
            connection: isset($input['connection']) ? ConnectivityStatus::from($input['connection']) : null,
            movement: isset($input['movement']) ? MovementStatus::from($input['movement']) : null,
            ignition: isset($input['ignition']) ? IgnitionStatus::from($input['ignition']) : null,
            gps: isset($input['gps']) ? filter_var($input['gps'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : null,
        );
    }
}
