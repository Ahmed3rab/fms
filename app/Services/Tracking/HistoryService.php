<?php

namespace App\Services\Tracking;

use App\Data\History;
use App\Models\Vehicle;
use App\Services\Tracking\Contracts\HistoryProvider;
use Illuminate\Support\Carbon;

class HistoryService
{
    public function __construct(
        protected HistoryProvider $provider,
    ) {}

    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): History
    {
        return $this->provider->history($vehicle, $from, $to);
    }
}
