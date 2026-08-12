<?php

namespace Database\Factories;

use App\Enums\ModuleTargetType;
use App\Enums\PublicationStatus;
use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ModuleVersion>
 */
class ModuleVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'module_id' => Module::factory(),
            'version_number' => 1,
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'status' => PublicationStatus::Draft,
            'default_language' => 'en',
            'target_type' => ModuleTargetType::None,
            'created_by_id' => User::factory(),
            'published_at' => null,
        ];
    }
}
