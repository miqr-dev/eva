<?php

namespace Database\Factories;

use App\Models\OrganizationUnit;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'organization_unit_id' => OrganizationUnit::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'is_active' => true,
        ];
    }
}
