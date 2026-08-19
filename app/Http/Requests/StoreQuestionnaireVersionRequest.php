<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreQuestionnaireVersionRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'questionnaire_template_id' => ['required', 'integer', 'exists:questionnaire_templates,id'],
            'source_version_id' => [
                'nullable',
                'integer',
                Rule::exists('questionnaire_versions', 'id')->where(
                    'questionnaire_template_id',
                    $this->integer('questionnaire_template_id'),
                ),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'default_language' => ['nullable', 'string', 'max:10'],
            'min_answers_to_show_results' => ['nullable', 'integer', 'min:1'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
