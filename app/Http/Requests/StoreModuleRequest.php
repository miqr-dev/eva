<?php

namespace App\Http\Requests;

class StoreModuleRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function permission(): string
    {
        return 'questionnaires.manage';
    }
}
