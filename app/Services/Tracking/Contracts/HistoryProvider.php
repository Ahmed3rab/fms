<?php

namespace App\Services\Tracking\Contracts;

use App\Data\History;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;

interface HistoryProvider
{
    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): History;
}
