<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessControlSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect([
            'organization_units.manage',
            'users.manage',
            'courses.manage',
            'questionnaires.manage',
            'campaigns.manage',
            'responses.view',
            'reports.manage',
            'benchmarks.manage',
            'emails.manage',
        ])->mapWithKeys(function (string $name): array {
            $permission = Permission::query()->updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
            );

            return [$name => $permission];
        });

        $rolePermissions = [
            'super-admin' => $permissions->keys()->all(),
            'administrator' => $permissions->keys()->all(),
            'questionnaire-editor' => [
                'questionnaires.manage',
                'campaigns.manage',
                'responses.view',
            ],
            'report-viewer' => [
                'responses.view',
                'reports.manage',
                'benchmarks.manage',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::query()->updateOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
            );

            $role->permissions()->sync(
                $permissions->only($permissionNames)->pluck('id')->all(),
            );
        }
    }
}
