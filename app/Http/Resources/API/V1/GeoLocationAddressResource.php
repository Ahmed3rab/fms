<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoLocationAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource === null) {
            return null;
        }

        return [
            'display_name' => data_get($this->resource, 'displayName'),
            'city' => data_get($this->resource, 'city'),
            'state' => data_get($this->resource, 'state'),
            'country' => data_get($this->resource, 'country'),
            'country_code' => data_get($this->resource, 'countryCode'),
        ];
    }
}
