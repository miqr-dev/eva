<?php

namespace Database\Factories;

use App\Models\ModuleSection;
use App\Models\ModuleVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ModuleSection>
 */
class ModuleSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'module_version_id' => ModuleVersion::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'sort_order' => 0,
        ];
    }
}
