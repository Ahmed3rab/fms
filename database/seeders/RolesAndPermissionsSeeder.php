<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super_admin',
            'system_admin',
            'user_manager',
            'api_consumer',
        ];

        $permissions = [
            'api.devices.view-any',
            'api.devices.view',
            'api.devices.profile',
            'api.devices.history',

            'api.companies.view-any',
            'api.companies.view',

            'tokens.view-any',
            'tokens.view',
            'tokens.create',
            'tokens.revoke',

            'portals.view-any',
            'portals.view',
            'portals.create',
            'portals.update',
            'portals.delete',
            'portals.bulk-delete',

            'logs.view-any',
            'logs.view',

            'users.view-any',
            'users.view',
            'users.create',
            'users.update',
            'users.activate',
            'users.deactivate',
            // 'users.delete',
            // 'users.bulk-delete',

            'roles.view-any',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            'permissions.view-any',
            'permissions.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $permissions = collect($permissions);
        Role::findByName('super_admin')->syncPermissions($permissions->toArray());

        Role::findByName('system_admin')->syncPermissions($permissions->filter(function ($permission) {
            return Str::startsWith($permission, 'users') || Str::startsWith($permission, 'portals') || Str::startsWith($permission, 'logs') || Str::startsWith($permission, 'roles') || Str::startsWith($permission, 'permissions');
        }));

        Role::findByName('user_manager')->syncPermissions($permissions->filter(function ($permission) {
            return Str::startsWith($permission, 'users');
        }));

        Role::findByName('api_consumer')->syncPermissions($permissions->filter(function ($permission) {
            return Str::startsWith($permission, 'api') || Str::startsWith($permission, 'tokens');
        }));

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
