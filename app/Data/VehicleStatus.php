<?php

namespace App\Data;

use App\Enums\ConnectivityStatus;
use App\Enums\MovementStatus;

final readonly class VehicleStatus
{
    public function __construct(
        public ConnectivityStatus $connection,
        public MovementStatus $movement,
    ) {}
}
