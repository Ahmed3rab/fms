<?php

namespace App\Services\Tracking\Contracts;

use App\Data\Coordinates;
use App\Data\Distance;
use App\Data\GeoLocationAddress;
use App\Data\Ignition;
use App\Data\Speed;
use App\Data\TrackingTimestamps;

interface TracksVehicleState
{
    public function deviceUuid(): ?string;

    public function coordinates(): ?Coordinates;

    public function geoAddress(): ?GeoLocationAddress;

    public function speed(): ?Speed;

    public function gpsStatus(): ?bool;

    public function angle(): ?int;

    public function altitude(): ?float;

    public function ignition(): ?Ignition;

    public function oil(): ?float;

    public function voltage(): ?float;

    public function mileage(): ?Distance;

    public function temperature(): ?string;

    public function timestamps(): TrackingTimestamps;
}
