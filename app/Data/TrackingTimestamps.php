<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use JsonSerializable;

final readonly class TrackingTimestamps implements Arrayable, JsonSerializable
{
    public function __construct(
        private ?Carbon $gps,
        private ?Carbon $received,
        private ?Carbon $lastSynced,
    ) {}

    public function gps(): ?Carbon
    {
        return $this->gps;
    }

    public function received(): ?Carbon
    {
        return $this->received;
    }

    public function lastSynced(): ?Carbon
    {
        return $this->lastSynced;
    }

    /**
     * @param array<int,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            gps: isset($data['gps']) ? Carbon::parse($data['gps']) : null,
            received: isset($data['received']) ? Carbon::parse($data['received']) : null,
            lastSynced: isset($data['last_synced']) ? Carbon::parse($data['last_synced']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'gps' => $this->gps(),
            'received' => $this->received(),
            'last_synced' => $this->lastSynced(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
