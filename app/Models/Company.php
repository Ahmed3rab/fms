<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug'])]
class Company extends Model
{
    use SoftDeletes;

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
}
