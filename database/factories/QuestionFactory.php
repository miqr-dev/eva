<?php

namespace Database\Factories;

use App\Enums\QuestionType;
use App\Models\ModuleSection;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'module_section_id' => ModuleSection::factory(),
            'question_text' => fake()->sentence().'?',
            'question_type' => QuestionType::Scale,
            'scale_min' => 1,
            'scale_max' => 5,
            'scale_min_label' => 'Strongly disagree',
            'scale_max_label' => 'Strongly agree',
            'is_required' => true,
            'sort_order' => 0,
        ];
    }
}
