<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceStateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'source'    => data_get($this->resource, 'source'),
            'latitude' => data_get($this->resource, 'latitude'),
            'longitude' => data_get($this->resource, 'longitude'),
            'speed' => data_get($this->resource, 'speed'),
            'gps_time' => data_get($this->resource, 'gps_time'),
            'gps_status' => data_get($this->resource, 'gps_status'),
            'angle' => data_get($this->resource, 'angle'),
            'altitude' => data_get($this->resource, 'altitude'),
            'acc' => data_get($this->resource, 'acc'),
            'oil' => data_get($this->resource, 'oil'),
            'voltage' => data_get($this->resource, 'voltage'),
            'mileage' => data_get($this->resource, 'mileage'),
            'temperature' => data_get($this->resource, 'temperature'),
            'last_synced_at' => data_get($this->resource, 'last_synced_at'),
            'received_at' => data_get($this->resource, 'received_at'),
        ];
    }
}
