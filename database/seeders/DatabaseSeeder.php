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
        // Lehrende, and the two admin users. Keep these five as-is; append
        // any future (e.g. demo or test) seeders below rather than editing
        // them. TeacherSeeder depends on OrganizationUnitSeeder and
        // TeacherRoleSeeder having already run.
        $this->call([
            AccessControlSeeder::class,
            OrganizationUnitSeeder::class,
            TeacherRoleSeeder::class,
            TeacherSeeder::class,
            UserSeeder::class,
        ]);
    }
}
