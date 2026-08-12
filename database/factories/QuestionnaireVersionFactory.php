<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\QuestionnaireTemplate;
use App\Models\QuestionnaireVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuestionnaireVersion>
 */
class QuestionnaireVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'questionnaire_template_id' => QuestionnaireTemplate::factory(),
            'version_number' => 1,
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(),
            'status' => PublicationStatus::Draft,
            'default_language' => 'en',
            'min_answers_to_show_results' => 5,
            'created_by_id' => User::factory(),
            'published_at' => null,
        ];
    }
}
