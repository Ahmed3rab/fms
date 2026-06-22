<?php

namespace App\Models;

use App\Casts\AsAddress;
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
        'address'   => AsAddress::class,
    ];

    /**
     * @return BelongsTo<Device,DeviceState>
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
