<?php

namespace App\Http\Requests;

class UpdateBenchmarkGroupRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'scope_type' => ['sometimes', 'required', 'string', 'max:100'],
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
