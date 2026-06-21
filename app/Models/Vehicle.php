<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    use HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
        'purchase_date' => 'date',
        'installation_date' => 'date',
        'last_synced_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
    /**
     * @return BelongsTo<Company,Vehicle>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return HasOne<Device,Vehicle>
     */
    public function device(): HasOne
    {
        return $this->hasOne(Device::class);
    }
}
