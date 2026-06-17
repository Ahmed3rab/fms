<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $a = Company::firstOrCreate([
            'slug' => 'company-a',
        ], [
            'name' => 'Company A',
        ]);
        $ua = $a->users()->create([
            'name'  => 'test a',
            'email' => 'a@example.com',
            'password'  => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $ua->assignRole('company_admin');

        $b = Company::firstOrCreate([
            'slug' => 'company-b',
        ], [
            'name' => 'Company B',
        ]);

        $ub = $b->users()->create([
            'name'  => 'test b',
            'email' => 'b@example.com',
            'password'  => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $ub->assignRole('company_admin');
    }
}
