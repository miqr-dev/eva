<?php

namespace Database\Factories;

use App\Models\BenchmarkGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BenchmarkGroup>
 */
class BenchmarkGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'scope_type' => 'organization_unit',
            'organization_unit_id' => null,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
