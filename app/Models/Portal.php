<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'icon',
        'sort_order',
    ];

    /**
     * @return BelongsToMany<Company,PortalLink,Pivot>
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }
}
