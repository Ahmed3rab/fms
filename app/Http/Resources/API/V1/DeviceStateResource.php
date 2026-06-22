<?php

namespace App\Http\Resources\API\V1;

use App\Data\ResolvedDeviceState;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ResolvedDeviceState
 */
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
            'source' => $this->source,
            'status' => [
                'connection' => $this->status->connection->value,
                'movement' => $this->status->movement->value,
            ],
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'geo_address' => GeoLocationAddressResource::make(
                $this->geoAddress
            ),
            'speed' => $this->speed,
            'gps_time' => $this->gpsTime,
            'gps_status' => $this->gpsStatus,
            'angle' => $this->angle,
            'altitude' => $this->altitude,
            'acc' => $this->acc,
            'oil' => $this->oil,
            'voltage' => $this->voltage,
            'mileage' => $this->mileage,
            'temperature' => $this->temperature,
            'received_at' => $this->receivedAt,
            'last_synced_at' => $this->lastSyncedAt,
        ];
    }
}
