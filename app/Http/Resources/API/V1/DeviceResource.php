<?php

namespace App\Http\Resources\API\V1;

use App\Models\DeviceState;
use App\Services\Tracking\DeviceStateStore;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
            'system_no' => $this->system_no,
            'name' => $this->name,
            'model' => $this->model,
            'brand' => $this->brand,
            'imei' => $this->imei,
            'phone_number' => $this->phone_number,
            'company' => [
                'id' => $this->company?->uuid,
                'name' => $this->company?->name,
            ],
            'location' => DeviceStateResource::make($this->current_state),
        ];
    }
}
