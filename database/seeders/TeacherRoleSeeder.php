<?php

namespace Database\Seeders;

use App\Models\TeacherRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherRoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->roles() as $name) {
            TeacherRole::query()->updateOrCreate(['name' => $name]);
        }
    }

    /**
     * @return list<string>
     */
    private function roles(): array
    {
        return [
            'Sozialpädagoge',
            'Psychologe',
            'Dozent',
        ];
    }
}
