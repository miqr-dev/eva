<?php

namespace Database\Factories;

use App\Models\TeacherRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeacherRole>
 */
class TeacherRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->jobTitle(),
        ];
    }
}
