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
        Company::firstOrCreate([
            'slug' => 'acme',
        ], [
            'name' => 'Acme Company',
        ]);
        Company::firstOrCreate([
            'slug' => 'company-a',
        ], [
            'name' => 'Company A',
        ]);

        Company::firstOrCreate([
            'slug' => 'company-b',
        ], [
            'name' => 'Company B',
        ]);
    }
}
