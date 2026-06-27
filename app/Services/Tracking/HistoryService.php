<?php

namespace App\Services\Tracking;

use App\Data\History;
use App\Models\Vehicle;
use App\Services\Tracking\Contracts\TrackingProvider;
use Illuminate\Support\Carbon;

class HistoryService
{
    public function __construct(
        protected TrackingProvider $provider,
    ) {}

    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): History
    {
        return $this->provider->history($vehicle, $from, $to);
    }
}
