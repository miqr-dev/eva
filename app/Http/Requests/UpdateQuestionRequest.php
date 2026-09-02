<?php

namespace App\Http\Requests;

use App\Enums\QuestionType;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends AdminRequest
{
    public function rules(): array
    {
        $questionType = $this->string('question_type')->toString();
        $isScale = $questionType === QuestionType::Scale->value;
        $hasOptions = $questionType === QuestionType::SingleChoice->value;

        return [
            'module_section_id' => ['required', 'integer', 'exists:module_sections,id'],
            'question_text' => ['required', 'string'],
            'question_type' => ['required', Rule::enum(QuestionType::class)],
            'scale_min' => [Rule::requiredIf($isScale), 'nullable', 'integer'],
            'scale_max' => [Rule::requiredIf($isScale), 'nullable', 'integer', 'gt:scale_min'],
            'scale_min_label' => ['nullable', 'string', 'max:255'],
            'scale_max_label' => ['nullable', 'string', 'max:255'],
            'scale_labels' => ['nullable', 'array'],
            'scale_labels.*' => ['nullable', 'string', 'max:255'],
            'is_required' => ['sometimes', 'boolean'],
            'options' => [
                Rule::when(
                    $hasOptions,
                    ['required', 'array', 'min:2'],
                    ['nullable', 'array'],
                ),
            ],
            'options.*' => ['required', 'string', 'max:255', 'distinct'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
