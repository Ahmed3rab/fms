<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'plate_number' => $this->plate_number,
            'brand' => $this->brand,
            'model' => $this->model,
            'color' => $this->color,
            'chassis_number' => $this->chassis_number,
            'engine_number' => $this->engine_number,
            'company' => CompanyResource::make($this->company),
            'location' => DeviceStateResource::make(
                $this->device?->current_state
            ),
        ];
    }
}
