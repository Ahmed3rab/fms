<?php

namespace App\Casts;

use App\Data\Ignition;
use App\Enums\IgnitionStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AsIgnition implements CastsAttributes
{
    /**
     * @param array<int,mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Ignition
    {
        return Ignition::fromProvider($value);
    }
    /**
     * @param array<int,mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof Ignition) {
            return $value->status === IgnitionStatus::On ? '1' : '0';
        }

        return $value;
    }
}
