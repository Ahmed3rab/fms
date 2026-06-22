<?php

namespace App\Casts;

use App\Data\GeoLocationAddress;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @implements CastsAttributes<mixed,mixed>
 */
class AsGeoLocationAddress implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?GeoLocationAddress
    {
        if ($value === null) {
            return null;
        }

        return GeoLocationAddress::fromArray(json_decode($value, true));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! $value instanceof GeoLocationAddress) {
            throw new InvalidArgumentException(
                'The given value is not an Address instance.'
            );
        }

        return json_encode($value->toArray());
    }
}
