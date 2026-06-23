<?php

namespace App\Models;

use App\Casts\AsDistance;
use App\Casts\AsGeoLocationAddress;
use App\Casts\AsSpeed;
use App\Data\Coordinates;
use App\Data\Distance;
use App\Data\GeoLocationAddress;
use App\Data\Speed;
use App\Data\TrackingTimestamps;
use App\Services\Tracking\Contracts\TracksVehicleState;
use App\Services\Tracking\VehicleStatus\ConnectivityStatusResolver;
use App\Services\Tracking\VehicleStatus\MovementStatusResolver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceState extends Model implements TracksVehicleState
{
    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
        'gps_time' => 'datetime',
        'last_synced_at' => 'datetime',
        'gps_status' => 'boolean',
        'geo_address'   => AsGeoLocationAddress::class,
        'mileage' => AsDistance::class,
        'speed' =>  AsSpeed::class,
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

    public function coordinates(): ?Coordinates
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        return Coordinates::fromProvider($this->latitude, $this->longitude);
    }

    public function geoAddress(): ?GeoLocationAddress
    {
        return $this->geo_address;
    }

    public function speed(): ?Speed
    {
        return $this->speed;
    }

    public function gpsStatus(): ?bool
    {
        return $this->gps_status;
    }

    public function angle(): ?int
    {
        return $this->angle;
    }

    public function altitude(): ?float
    {
        return $this->altitude;
    }

    public function acc(): ?string
    {
        return $this->acc;
    }

    public function oil(): ?float
    {
        return $this->oil;
    }

    public function voltage(): ?float
    {
        return $this->voltage;
    }

    public function mileage(): ?Distance
    {
        return $this->mileage;
    }

    public function temperature(): ?string
    {
        return $this->temperature;
    }

    public function timestamps(): TrackingTimestamps
    {
        return new TrackingTimestamps(
            gps: $this->gps_time,
            received: null,
            lastSynced: $this->last_synced_at,
        );
    }
}
