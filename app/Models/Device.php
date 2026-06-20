<?php

namespace App\Models;

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

    protected $casts = [
        'payload' => 'array',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * @return BelongsTo<Company,Device>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return HasOne<DeviceState,Device>
     */
    public function state(): HasOne
    {
        return $this->hasOne(DeviceState::class);
    }

    #[Scope]
    public function visibleTo(Builder $query, User $user): Builder
    {
        $companyIds = $user->company
            ->visibleCompanies()
            ->pluck('companies.id');

        return $query->whereIn(
            'company_id',
            $companyIds,
        );
    }
}
