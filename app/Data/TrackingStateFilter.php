<?php

namespace App\Data;

use App\Enums\ConnectivityStatus;
use App\Enums\IgnitionStatus;
use App\Enums\MovementStatus;

final readonly class TrackingStateFilter
{
    public function __construct(
        public ?ConnectivityStatus $connection = null,
        public ?MovementStatus $movement = null,
        public ?IgnitionStatus $ignition = null,
        public ?bool $gps = null,
    ) {}
}
