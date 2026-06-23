<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use JsonSerializable;

final readonly class TrackingTimestamps implements Arrayable, JsonSerializable
{
    public function __construct(
        public ?Carbon $gps,
        public ?Carbon $received,
        public ?Carbon $lastSynced,
    ) {}

    public function toArray(): array
    {
        return [
            'gps' => $this->gps,
            'received' => $this->received,
            'last_synced' => $this->lastSynced,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
