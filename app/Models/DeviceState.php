<?php

namespace App\Models;

use App\Casts\AsGeoLocationAddress;
use App\Services\Tracking\VehicleStatus\ConnectivityStatusResolver;
use App\Services\Tracking\VehicleStatus\MovementStatusResolver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceState extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
        'gps_time' => 'datetime',
        'last_synced_at' => 'datetime',
        'gps_status' => 'boolean',
        'geo_address'   => AsGeoLocationAddress::class,
    ];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'connection' => app(ConnectivityStatusResolver::class)
                    ->resolve($this)
                    ->value,

                'movement' => app(MovementStatusResolver::class)
                    ->resolve($this)
                    ->value,
            ]
        );
    }

    /**
     * @return BelongsTo<Device,DeviceState>
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
