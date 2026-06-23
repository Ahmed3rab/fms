<?php

namespace App\Services\Tracking\Contracts;

use App\Data\Distance;
use App\Data\GeoLocationAddress;
use App\Data\TrackingTimestamps;

interface TracksVehicleState
{
    public function latitude(): ?float;

    public function longitude(): ?float;

    public function geoAddress(): ?GeoLocationAddress;

    public function speed(): ?float;

    public function gpsStatus(): ?bool;

    public function angle(): ?int;

    public function altitude(): ?float;

    public function acc(): ?string;

    public function oil(): ?float;

    public function voltage(): ?float;

    public function mileage(): ?Distance;

    public function temperature(): ?string;

    public function timestamps(): TrackingTimestamps;
}
