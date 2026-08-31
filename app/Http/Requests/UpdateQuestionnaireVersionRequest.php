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
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
