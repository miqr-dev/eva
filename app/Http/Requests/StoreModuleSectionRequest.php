<?php

namespace App\Http\Requests;

class StoreModuleSectionRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'module_version_id' => ['required', 'integer', 'exists:module_versions,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
