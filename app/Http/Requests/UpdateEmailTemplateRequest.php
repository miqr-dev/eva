<?php

namespace App\Http\Requests;

class UpdateEmailTemplateRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'string', 'max:100'],
            'subject' => ['sometimes', 'required', 'string', 'max:255'],
            'body' => ['sometimes', 'required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function permission(): string
    {
        return 'emails.manage';
    }
}
