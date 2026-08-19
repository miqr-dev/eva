<?php

namespace App\Http\Requests;

class StoreBenchmarkGroupRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'scope_type' => ['required', 'string', 'max:100'],
            'organization_unit_id' => ['nullable', 'integer', 'exists:organization_units,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function permission(): string
    {
        return 'benchmarks.manage';
    }
}
