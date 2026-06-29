<?php

namespace App\Services\Tracking;

use App\Data\History;
use App\Models\Vehicle;
use App\Services\Tracking\Contracts\HistoryProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
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
    /**
     * @return LengthAwarePaginator<<missing>,<missing>>
     */
    public function pagiante(Vehicle $vehicle, Carbon $from, Carbon $to, int $page, int $perPage): LengthAwarePaginator
    {
        $history = $this->history(
            $vehicle,
            $from,
            $to,
        );

        return new LengthAwarePaginator(
            items: $history->forPage($page, $perPage)->values(),
            total: $history->count(),
            perPage: $perPage,
            currentPage: $page,
            options: [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ],
        )
        ->withQueryString();
    }
}
