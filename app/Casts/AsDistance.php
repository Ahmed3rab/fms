<?php

namespace App\Casts;

use App\Data\Distance;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<mixed,mixed>
 */
class AsDistance implements CastsAttributes
{
    /**
     * Cast the given value.
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Distance
    {
        if ($value === null) {
            return null;
        }
        return Distance::fromProvider((float) $value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?float
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Distance) {
            return $value->kilometers;
        }

        return (float) $value;
    }
}
