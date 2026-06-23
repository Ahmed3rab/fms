<?php

namespace App\Services\Tracking\Contracts;

use App\Models\Vehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface HistoryProvider
{
    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): Collection;
}
