<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Rawilk\ProfileFilament\Auth\Multifactor\App\Concerns\InteractsWithAppAuthentication;
use Rawilk\ProfileFilament\Auth\Multifactor\App\Contracts\HasAppAuthentication;
use Rawilk\ProfileFilament\Auth\Multifactor\Concerns\InteractsWithMultiFactorAuthentication;
use Rawilk\ProfileFilament\Auth\Multifactor\Contracts\HasMultiFactorAuthentication;
use Rawilk\ProfileFilament\Auth\Multifactor\Recovery\Concerns\InteractsWithAuthenticationRecovery;
use Rawilk\ProfileFilament\Auth\Multifactor\Recovery\Contracts\HasMultiFactorAuthenticationRecovery;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasTenants, FilamentUser, HasMultiFactorAuthentication, HasAppAuthentication, HasMultiFactorAuthenticationRecovery
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use HasApiTokens;
    use InteractsWithMultiFactorAuthentication;
    use InteractsWithAppAuthentication;
    use InteractsWithAuthenticationRecovery;

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasAnyRole([
                'super_admin',
                'system_admin',
            ]);
        }

        if ($panel->getId() === 'portal') {
            return $this->hasAnyRole([
                'company_admin',
                'api_consumer',
            ]);
        }
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'timestamp',
        ];
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->hasAnyRole(['super_admin', 'system_admin'])) {
            return Company::query()->whereNull('deleted_at')->get();
        }

        return Company::query()->whereKey($this->company_id)->get();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->hasAnyRole(['super_admin', 'system_admin'])) {
            return true;
        }

        return $tenant->id === $this->company_id;
    }

    /**
     * @return BelongsTo<Company,User>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    /**
     * @return array<int,string>
     */
    public static function allowedRoles(): array
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return [
                'super_admin',
                'system_admin',
                'company_admin',
                'api_consumer',
            ];
        }

        if ($user->hasRole('system_admin')) {
            return [
                'system_admin',
                'company_admin',
                'api_consumer',
            ];
        }

        return [
            'company_admin',
            'api_consumer',
        ];
    }

    protected function canManageUser(User $user, User $model): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        if ($user->hasRole('system_admin')) {
            return ! $model->hasRole('super_admin');
        }

        if ($user->hasRole('company_admin')) {

            if ($user->company_id !== $model->company_id) {
                return false;
            }

            return ! $model->hasAnyRole([
                'super_admin',
                'system_admin',
            ]);
        }

        return false;
    }
}
