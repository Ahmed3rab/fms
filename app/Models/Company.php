<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'icruise_company_id', 'payload', 'last_synced_at'])]
class Company extends Model
{
    use SoftDeletes;
    use HasUuids;

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
     * @return HasMany<User,Company>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    /**
     * @return BelongsToMany<Portal,Company,Pivot>
     */
    public function portals(): BelongsToMany
    {
        return $this->belongsToMany(Portal::class, 'company_portal');
    }

    /**
     * @return BelongsToMany<Company,Company,Pivot>
     */
    public function visibleCompanies(): BelongsToMany
    {
        return $this->belongsToMany(
            Company::class,
            'company_visibility',
            'company_id',
            'visible_company_id',
        );
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
