<?php

namespace App\Http\Requests;

class UpdateModuleSectionRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
