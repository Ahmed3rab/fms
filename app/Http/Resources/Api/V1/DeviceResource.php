<?php

namespace App\Http\Resources\Api\V1;

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
