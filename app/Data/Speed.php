<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class Speed implements Arrayable, JsonSerializable
{
    public function __construct(
        public float $kilometersPerHour,
    ) {}

    public static function fromProvider(float $value): self
    {
        // iCruise reports km/h
        return self::fromKilometersPerHour($value);
    }

    public static function fromKilometersPerHour(float $kmh): self
    {
        return new self($kmh);
    }

    public static function fromMetersPerSecond(float $mps): self
    {
        return new self($mps * 3.6);
    }

    public function kmh(): float
    {
        return $this->kilometersPerHour;
    }

    public function metersPerSecond(): float
    {
        return $this->kilometersPerHour / 3.6;
    }

    public function isStopped(float $threshold = 3): bool
    {
        return $this->kilometersPerHour < $threshold;
    }

    public function isMoving(float $threshold = 3): bool
    {
        return ! $this->isStopped($threshold);
    }

    /**
     * @param array{kmh:float|int,mps:float|int} $data
     */
    public static function fromArray(array $data): self
    {
        return self::fromKilometersPerHour(
            (float) $data['kmh']
        );
    }

    public function toArray(): array
    {
        return [
            'kmh' => round($this->kmh(), 1),
            'mps' => round($this->metersPerSecond(), 2),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
