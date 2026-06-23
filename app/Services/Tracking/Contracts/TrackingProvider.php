<?php

namespace App\Services\Tracking\Contracts;

interface TrackingProvider extends RealtimeProvider, HistoryProvider, VehicleHydrator {}
