<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\OrganizationUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_unit_id' => OrganizationUnit::factory(),
            'name' => fake()->sentence(3),
            'code' => strtoupper(fake()->unique()->bothify('??-###')),
            'starts_at' => now()->startOfMonth(),
            'ends_at' => now()->addMonths(4)->endOfMonth(),
            'is_active' => true,
        ];
    }
}
