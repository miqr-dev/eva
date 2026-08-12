<?php

namespace App\Http\Requests;

class StoreEmailTemplateRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function permission(): string
    {
        return 'emails.manage';
    }
}
