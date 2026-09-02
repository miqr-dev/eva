<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fixed baseline: roles/permissions, Standorte, Lehrenden-Rollen,
        // and the two admin users. Keep these four as-is; append any future
        // (e.g. demo or test) seeders below rather than editing them.
        $this->call([
            AccessControlSeeder::class,
            OrganizationUnitSeeder::class,
            TeacherRoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
