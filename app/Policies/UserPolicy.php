<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'super_admin',
            'system_admin',
            'company_admin',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasAnyRole([
            'super_admin',
            'system_admin',
            'company_admin',
        ]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'super_admin',
            'system_admin',
            'company_admin',
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.update') && $this->canManageUser($user, $model);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.deactivate') && $this->canManageUser($user, $model);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.activate') && $this->canManageUser($user, $model);
    }
    protected function canManageUser(
        User $user,
        User $model
    ): bool {
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
