<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            // Companies
            'companies.view-any',
            'companies.view',
            'companies.create',
            'companies.update',
            'companies.deactivate',
            'companies.activate',

            // Users
            'users.view-any',
            'users.view',
            'users.create',
            'users.update',
            'users.deactivate',
            'users.activate',

            // Roles
            'roles.view-any',
            'roles.view',

            // Permissions
            'permissions.view-any',
            'permissions.view',

            // Portals
            'portals.view-any',
            'portals.view',
            'portals.create',
            'portals.update',
            'portals.delete',

            // Logs
            'logs.view-any',
            'logs.view',

            // Tokens
            'tokens.view-any',
            'tokens.view',
            'tokens.create',
            'tokens.revoke',

            // API
            'api.devices.view-any',
            'api.devices.view',
            'api.devices.profile',
            'api.devices.history',

            'api.companies.view-any',
            'api.companies.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $superAdmin = Role::findOrCreate('super_admin');
        $systemAdmin = Role::findOrCreate('system_admin');
        $companyAdmin = Role::findOrCreate('company_admin');
        $apiConsumer = Role::findOrCreate('api_consumer');

        /*
         * Super Admin
         */
        $superAdmin->syncPermissions(Permission::all());

        /*
         * System Admin
         * Internal operations team
         */
        $systemAdmin->syncPermissions([

            'companies.view-any',
            'companies.view',
            'companies.create',
            'companies.update',
            'companies.deactivate',
            'companies.activate',

            'users.view-any',
            'users.view',
            'users.create',
            'users.update',
            'users.deactivate',
            'users.activate',

            'roles.view-any',
            'roles.view',

            'permissions.view-any',
            'permissions.view',

            'portals.view-any',
            'portals.view',
            'portals.create',
            'portals.update',
            'portals.delete',

            'logs.view-any',
            'logs.view',

            'tokens.view-any',
            'tokens.view',
            'tokens.create',
            'tokens.revoke',
        ]);

        /*
         * Company Admin
         * Customer administrator
         */
        $companyAdmin->syncPermissions([
            'users.view-any',
            'users.view',
            'users.create',
            'users.update',
            'users.deactivate',
            'users.activate',

            'roles.view-any',
            'roles.view',

            'tokens.view-any',
            'tokens.view',
            'tokens.create',
            'tokens.revoke',

            'api.devices.view-any',
            'api.devices.view',
            'api.devices.profile',
            'api.devices.history',

            'api.companies.view-any',
            'api.companies.view',
        ]);

        /*
         * API Consumer
         * Integration accounts
         */
        $apiConsumer->syncPermissions([
            'tokens.view',
            'tokens.create',

            'api.devices.view-any',
            'api.devices.view',
            'api.devices.profile',
            'api.devices.history',

            'api.companies.view-any',
            'api.companies.view',
        ]);
    }
}
