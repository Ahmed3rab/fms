<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Device extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected mixed $resolvedState = null;

    protected $casts = [
        'payload' => 'array',
        'last_synced_at' => 'datetime',
    ];

    public function setResolvedState(mixed $state): void
    {
        $this->resolvedState = $state;
    }

    protected function currentState(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->resolvedState !== null) {
                    return $this->resolvedState;
                }
                if ($this->state) {
                    $this->state->source = 'database';
                }
                return $this->state;
            }
        );
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * @return HasOne<DeviceState,Device>
     */
    public function state(): HasOne
    {
        return $this->hasOne(DeviceState::class);
    }

    /**
     * @return BelongsTo<Vehicle,Device>
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    #[Scope]
    public function visibleTo(Builder $query, User $user): Builder
    {
        return $query->whereHas(
            'vehicle',
            fn(Builder $query) => $query->visibleTo($user)
        );
    }
}
