<?php

namespace Database\Factories;

use App\Enums\OrganizationUnitType;
use App\Models\OrganizationUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganizationUnit>
 */
class OrganizationUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => null,
            'name' => fake()->unique()->company(),
            'type' => fake()->randomElement(OrganizationUnitType::cases()),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
