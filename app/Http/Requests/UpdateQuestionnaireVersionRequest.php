<?php

namespace App\Http\Requests;

class UpdateQuestionnaireVersionRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'default_language' => ['required', 'string', 'max:10'],
            'min_answers_to_show_results' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
