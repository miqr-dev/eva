<?php

namespace App\Http\Requests;

class UpdateReportTemplateRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function permission(): string
    {
        return 'reports.manage';
    }
}
