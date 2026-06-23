<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class Distance implements Arrayable, JsonSerializable
{
    public function __construct(public float $kilometers) {}

    /**
     * @param mixed $value
     */
    public static function fromProvider($value): self
    {
        return self::fromKilometers($value);
    }

    public static function fromKilometers(float $km): self
    {
        return new self($km);
    }

    public static function fromMeters(float $meters): self
    {
        return new self($meters / 1000);
    }

    public function meters(): float
    {
        return $this->kilometers * 1000;
    }

    public function toArray(): array
    {
        return [
            'km' => round($this->kilometers, 3),
            'meters' => round($this->meters(), 0),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
