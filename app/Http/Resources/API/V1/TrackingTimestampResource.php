<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @mixin \App\Data\TrackingTimestamps
 */
class TrackingTimestampResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'gps' => $this->format($this->gps()),
            'received' => $this->format($this->received()),
            'last_synced' => $this->format($this->lastSynced()),
        ];
    }

    protected function format(?Carbon $date): ?string
    {
        return $date?->setTimezone(config('app.timezone'))
            ->toIso8601String();
    }
}
