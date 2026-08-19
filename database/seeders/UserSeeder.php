<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $user = User::query()->updateOrCreate(
                ['email' => 'ara.matoyan@miqr.de'],
                [
                    'organization_unit_id' => null,
                    'name' => 'Ara',
                    'password' => '123qwe!',
                    'is_active' => true,
                ],
            );

            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();

            $superAdministrationRole = Role::query()
                ->where('name', 'super-admin')
                ->where('guard_name', 'web')
                ->firstOrFail();

            $user->roles()->syncWithoutDetaching([$superAdministrationRole->id]);
        });
    }
}
