<?php

namespace Database\Factories;

use App\Enums\EvaluationCampaignStatus;
use App\Models\EvaluationCampaign;
use App\Models\OrganizationUnit;
use App\Models\QuestionnaireVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluationCampaign>
 */
class EvaluationCampaignFactory extends Factory
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
            'course_id' => null,
            'questionnaire_version_id' => QuestionnaireVersion::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(),
            'starts_at' => now(),
            'ends_at' => now()->addWeeks(2),
            'status' => EvaluationCampaignStatus::Draft,
            'min_answers_to_show_results' => 5,
            'created_by_id' => User::factory(),
        ];
    }
}
