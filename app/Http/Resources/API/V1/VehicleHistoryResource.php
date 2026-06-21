<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'gps_time' => $this['DateTime'],
            'latitude' => $this['Latitude'],
            'longitude' => $this['Longitude'],
            'speed' => $this['Velocity'],
            'angle' => $this['Angle'],
            'altitude' => $this['Altitude'],
            'gps_status' => (bool) $this['GpsStatus'],
            'acc' => (bool) $this['Acc'],
            'oil' => $this['Oil'],
            'voltage' => $this['Voltage'],
            'mileage' => $this['Mileage'],
            'temperature' => $this['Temperature'],
            'address' => $this['Address'],
        ];
    }
}
