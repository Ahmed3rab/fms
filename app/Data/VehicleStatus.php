<?php

namespace App\Data;

use App\Enums\ConnectivityStatus;
use App\Enums\MovementStatus;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class VehicleStatus implements Arrayable, JsonSerializable
{
    public function __construct(
        public ConnectivityStatus $connection,
        public MovementStatus $movement,
    ) {}

    public function toArray(): array
    {
        return [
            'connection' => $this->connection->value,
            'movement' => $this->movement->value,
        ];
    }
    /**
     * @param array<int,mixed> $status
     */
    public static function fromArray(array $status): self
    {
        return new self(
            connection: ConnectivityStatus::from($status['connection']),
            movement: MovementStatus::from($status['movement']),
        );
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
