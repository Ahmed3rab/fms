<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'super admin',
            'email' => 'info@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->call(RolesAndPermissionsSeeder::class);

        $user->assignRole(Role::findByName('super_admin'));
    }
}
