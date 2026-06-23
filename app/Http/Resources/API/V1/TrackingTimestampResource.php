<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'gps' => $this->gps(),
            'received' => $this->received(),
            'last_synced' => $this->lastSynced(),
        ];
    }
}
