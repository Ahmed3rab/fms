<?php

namespace App\Casts;

use App\Data\Address;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Exceptions\InvalidArgumentException;

/**
 * @implements CastsAttributes<mixed,mixed>
 */
class AsAddress implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */

    public function get(Model $model, string $key, mixed $value, array $attributes): ?Address
    {
        if ($value === null) {
            return null;
        }
        $data = json_decode($value, true);

        return new Address(
            displayName: $data['display_name'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? null,
            countryCode: $data['country_code'] ?? null,
            placeId: $data['place_id'] ?? null,
            osmType: $data['osm_type'] ?? null,
            osmId: $data['osm_id'] ?? null,
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return null;
        }

        if (! $value instanceof Address) {
            throw new InvalidArgumentException(
                'The given value is not an Address instance.'
            );
        }

        return json_encode($value->toArray());
    }
}
