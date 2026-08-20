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
            $superAdministrationRole = Role::query()
                ->where('name', 'super-admin')
                ->where('guard_name', 'web')
                ->firstOrFail();

            foreach ($this->users() as $attributes) {
                $user = User::query()->updateOrCreate(
                    ['email' => $attributes['email']],
                    [
                        'organization_unit_id' => null,
                        'name' => $attributes['name'],
                        'password' => $attributes['password'],
                        'is_active' => true,
                    ],
                );

                $user->forceFill([
                    'email_verified_at' => now(),
                ])->save();

                $user->roles()->syncWithoutDetaching([$superAdministrationRole->id]);
            }
        });
    }

    /**
     * @return array<int, array{name: string, email: string, password: string}>
     */
    private function users(): array
    {
        return [
            [
                'name' => 'Ara',
                'email' => 'ara.matoyan@miqr.de',
                'password' => '123qwe!',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@eva.de',
                'password' => 'Miqr.2020~',
            ],
        ];
    }
}
